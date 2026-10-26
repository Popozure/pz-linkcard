(function () {
    "use strict";

    const selector = ".lkc-external-wrap, .lkc-internal-wrap";
    const readyClass = "pz-lkc-stars-field-ready";
    const root = document.documentElement;
    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
    const heartHorizontalPeriod = 27000 * 640 * Math.sqrt(3) / 600;
    let cards = [];
    let frame = 0;
    let needsScan = true;

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
        requestFrame();
    };

    function render(timestamp) {
        frame = 0;
        if (document.hidden) return;

        if (needsScan) {
            const nextCards = Array.from(document.querySelectorAll(selector)).filter(card => {
                const style = window.getComputedStyle(card);
                return style.getPropertyValue("--pz-lkc-stars-enabled").trim() === "1" ||
                    style.getPropertyValue("--pz-lkc-hearts-enabled").trim() === "1";
            });
            const activeCards = new Set(nextCards);
            cards.filter(card => !activeCards.has(card)).forEach(clearCard);
            cards = nextCards;
            needsScan = false;
        }
        if (!cards.length) {
            root.style.removeProperty("--pz-lkc-star-field-x");
            root.style.removeProperty("--pz-lkc-star-field-y");
            root.style.removeProperty("--pz-lkc-heart-field-y");
            root.style.removeProperty("--pz-lkc-heart-field-x");
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
        setProperty(root.style, "--pz-lkc-heart-field-y", `${(600 * phase).toFixed(3)}px`);
        const heartPhaseX = reducedMotion.matches ? 0 : (timestamp % heartHorizontalPeriod) / heartHorizontalPeriod;
        setProperty(root.style, "--pz-lkc-heart-field-x", `${(-640 * heartPhaseX).toFixed(3)}px`);
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
