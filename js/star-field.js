(function () {
    "use strict";

    const selector = ".lkc-external-wrap, .lkc-internal-wrap";
    const readyClass = "pz-lkc-stars-field-ready";
    const root = document.documentElement;
    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
    const heartPath = "M12 21 3 12C-4 4 7-2 12 5 17-2 28 4 21 12Z";
    let cards = [];
    let frame = 0;
    let needsScan = true;

    const createHeartLayer = (width, height) => {
        const layer = document.createElement("span");
        layer.className = "pz-lkc-hearts-layer";
        layer.setAttribute("aria-hidden", "true");
        layer.dataset.width = String(width);
        layer.dataset.height = String(height);
        const count = Math.max(4, Math.min(36, Math.round(width * height * 36 / (640 * 600))));
        for (let index = 0; index < count; index++) {
            const size = 15.6 + Math.random() * 9.6;
            const swayDistance = 5 + Math.random() * 11;
            const swayDirection = Math.random() < .5 ? 1 : -1;
            const fall = document.createElement("span");
            fall.className = "pz-lkc-heart-fall";
            fall.style.left = `${(Math.random() * Math.max(0, width - size)).toFixed(2)}px`;
            fall.style.top = `${(Math.random() * Math.max(0, height - size)).toFixed(2)}px`;
            fall.style.setProperty("--pz-lkc-heart-size", `${size.toFixed(2)}px`);
            fall.style.setProperty("--pz-lkc-heart-delay", `${(-Math.random() * 27).toFixed(2)}s`);
            fall.style.setProperty("--pz-lkc-heart-rise-x", `${Math.min(width * .65, height * 640 / 600).toFixed(2)}px`);
            fall.style.setProperty("--pz-lkc-heart-rise-y", `${(-height - size * 2).toFixed(2)}px`);
            fall.style.setProperty("--pz-lkc-heart-sway-duration", `${(2 + Math.random() * 4).toFixed(2)}s`);
            fall.style.setProperty("--pz-lkc-heart-sway-delay", `${(-Math.random() * 6).toFixed(2)}s`);
            fall.style.setProperty("--pz-lkc-heart-sway-from", `${-swayDirection * swayDistance}px`);
            fall.style.setProperty("--pz-lkc-heart-sway-to", `${swayDirection * swayDistance}px`);

            const sway = document.createElement("span");
            sway.className = "pz-lkc-heart-sway";
            const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            svg.setAttribute("viewBox", "-5 -3 29 27");
            svg.setAttribute("focusable", "false");
            svg.style.transform = `rotate(${(-35 + Math.random() * 70).toFixed(2)}deg)`;
            const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
            path.setAttribute("d", heartPath);
            path.setAttribute("fill", "none");
            path.setAttribute("stroke", "white");
            path.setAttribute("stroke-width", "1.6");
            path.setAttribute("stroke-linejoin", "round");
            svg.appendChild(path);
            sway.appendChild(svg);
            fall.appendChild(sway);
            layer.appendChild(fall);
        }
        return layer;
    };

    const syncHeartLayers = () => {
        document.querySelectorAll(selector).forEach(card => {
            const enabled = window.getComputedStyle(card).getPropertyValue("--pz-lkc-hearts-enabled").trim() === "1";
            const existing = card.querySelector(":scope > .pz-lkc-hearts-layer");
            if (!enabled) {
                existing?.remove();
                return;
            }
            const width = Math.max(1, card.clientWidth);
            const height = Math.max(1, card.clientHeight);
            if (existing && Number(existing.dataset.width) === width && Number(existing.dataset.height) === height) return;
            existing?.remove();
            card.appendChild(createHeartLayer(width, height));
        });
    };

    const setProperty = (style, name, value) => {
        if (style.getPropertyValue(name) !== value) style.setProperty(name, value);
    };
    const clearCard = card => {
        card.classList.remove(readyClass);
        card.style.removeProperty("--pz-lkc-stars-left");
        card.style.removeProperty("--pz-lkc-stars-top");
    };
    const requestFrame = () => {
        if (!frame && !document.hidden) frame = window.requestAnimationFrame(render);
    };
    const refresh = () => {
        needsScan = true;
        syncHeartLayers();
        requestFrame();
    };

    function render(timestamp) {
        frame = 0;
        if (document.hidden) return;

        if (needsScan) {
            const nextCards = Array.from(document.querySelectorAll(selector)).filter(card => {
                const style = window.getComputedStyle(card);
                return style.getPropertyValue("--pz-lkc-stars-enabled").trim() === "1";
            });
            const activeCards = new Set(nextCards);
            cards.filter(card => !activeCards.has(card)).forEach(clearCard);
            cards = nextCards;
            needsScan = false;
        }
        if (!cards.length) {
            root.style.removeProperty("--pz-lkc-star-field-x");
            root.style.removeProperty("--pz-lkc-star-field-y");
            return;
        }

        // Each card is a window onto the same field in document coordinates.
        // Read all positions before writing styles so moving previews and page
        // layout changes do not cause repeated layout within this frame.
        const positions = cards.filter(card => card.isConnected).map(card => {
            const rect = card.getBoundingClientRect();
            return {
                card,
                visible: rect.width > 0 && rect.height > 0,
                left: rect.left + window.scrollX + card.clientLeft,
                top: rect.top + window.scrollY + card.clientTop,
            };
        });
        const phase = reducedMotion.matches ? 0 : (timestamp % 27000) / 27000;
        setProperty(root.style, "--pz-lkc-star-field-x", `${(-480 * phase).toFixed(3)}px`);
        setProperty(root.style, "--pz-lkc-star-field-y", `${(600 * phase).toFixed(3)}px`);
        positions.forEach(({ card, visible, left, top }) => {
            if (!visible) return;
            setProperty(card.style, "--pz-lkc-stars-left", `${left.toFixed(3)}px`);
            setProperty(card.style, "--pz-lkc-stars-top", `${top.toFixed(3)}px`);
            card.classList.add(readyClass);
        });
        if (!reducedMotion.matches) requestFrame();
    }

    const init = () => {
        // Includes newly loaded cards and the settings preview's generated CSS.
        new MutationObserver(refresh).observe(document.body, { childList: true, subtree: true });
        document.addEventListener("input", refresh, true);
        document.addEventListener("change", refresh, true);
        document.addEventListener("scroll", requestFrame, true);
        window.addEventListener("resize", refresh);
        window.addEventListener("load", refresh);
        reducedMotion.addEventListener("change", refresh);
        document.addEventListener("visibilitychange", () => {
            if (document.hidden) {
                window.cancelAnimationFrame(frame);
                frame = 0;
            } else {
                refresh();
            }
        });
        refresh();
    };
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init, { once: true });
    } else {
        init();
    }
})();
