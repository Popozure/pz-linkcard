(function () {
    "use strict";

    const selector = ".lkc-external-wrap, .lkc-internal-wrap";
    const layerSelector = ":scope > .pz-lkc-sakura-layer";
    const petalPath = "M12 28C8 24 2.5 17 2.5 11 2.5 6.5 5.5 3.5 9.3 4.1c1 .2 2 1.8 2.7 4.4.7-2.6 1.7-4.2 2.7-4.4 3.8-.6 6.8 2.4 6.8 6.9 0 6-5.5 13-9.5 17Z";
    const colors = ["#f7aebe", "#ffc0cb", "#f9b8c5", "#ffd0d8"];
    let syncFrame = 0;
    const observedCards = new Set();
    const resizeObserver = typeof ResizeObserver === "function" ? new ResizeObserver(() => scheduleSync()) : null;

    const randomBetween = (min, max) => min + Math.random() * (max - min);

    const createLayer = (width, height) => {
        const layer = document.createElement("span");
        layer.className = "pz-lkc-sakura-layer";
        layer.setAttribute("aria-hidden", "true");
        layer.dataset.width = String(width);
        layer.dataset.height = String(height);

        const count = Math.max(7, Math.min(28, Math.round(width * height * 0.00016)));
        for (let index = 0; index < count; index++) {
            const size = randomBetween(8, 17);
            const petalHeight = size * 4 / 3;
            const duration = randomBetween(8, 16);
            const startY = randomBetween(-petalHeight, height);
            const travelY = height + petalHeight - startY;
            const driftX = randomBetween(-width * 0.22, width * 0.22);
            const sway = randomBetween(8, Math.max(14, Math.min(44, width * 0.1)));
            const direction = Math.random() < 0.5 ? -1 : 1;
            const petal = document.createElement("span");
            petal.className = "pz-lkc-sakura-petal";
            petal.style.left = `${randomBetween(0, Math.max(0, width - size)).toFixed(2)}px`;
            petal.style.top = `${startY.toFixed(2)}px`;
            petal.style.setProperty("--pz-lkc-sakura-size", `${size.toFixed(2)}px`);
            petal.style.setProperty("--pz-lkc-sakura-height", `${petalHeight.toFixed(2)}px`);
            petal.style.setProperty("--pz-lkc-sakura-duration", `${duration.toFixed(2)}s`);
            petal.style.setProperty("--pz-lkc-sakura-delay", `${(-Math.random() * duration).toFixed(2)}s`);
            petal.style.setProperty("--pz-lkc-sakura-fall-y", `${travelY.toFixed(2)}px`);
            petal.style.setProperty("--pz-lkc-sakura-drift-x", `${driftX.toFixed(2)}px`);
            petal.style.setProperty("--pz-lkc-sakura-sway-1", `${(direction * sway).toFixed(2)}px`);
            petal.style.setProperty("--pz-lkc-sakura-sway-2", `${(-direction * sway * 0.65).toFixed(2)}px`);
            petal.style.setProperty("--pz-lkc-sakura-sway-3", `${(direction * sway * 0.35).toFixed(2)}px`);
            petal.style.setProperty("--pz-lkc-sakura-y-1", `${(travelY * 0.25).toFixed(2)}px`);
            petal.style.setProperty("--pz-lkc-sakura-y-2", `${(travelY * 0.5).toFixed(2)}px`);
            petal.style.setProperty("--pz-lkc-sakura-y-3", `${(travelY * 0.75).toFixed(2)}px`);
            petal.style.setProperty("--pz-lkc-sakura-color", colors[Math.floor(Math.random() * colors.length)]);
            petal.style.setProperty("--pz-lkc-sakura-flutter-duration", `${randomBetween(1.2, 3.8).toFixed(2)}s`);
            petal.style.setProperty("--pz-lkc-sakura-flutter-delay", `${(-Math.random() * 4).toFixed(2)}s`);
            const rotation = randomBetween(-150, 150);
            petal.style.setProperty("--pz-lkc-sakura-rotation-start", `${rotation.toFixed(2)}deg`);
            petal.style.setProperty("--pz-lkc-sakura-rotation-end", `${(rotation + randomBetween(35, 180) * direction).toFixed(2)}deg`);

            const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            svg.setAttribute("viewBox", "0 0 24 32");
            svg.setAttribute("focusable", "false");
            const shape = document.createElementNS("http://www.w3.org/2000/svg", "path");
            shape.setAttribute("d", petalPath);
            shape.setAttribute("fill", "currentColor");
            shape.setAttribute("stroke", "rgba(255,255,255,.75)");
            shape.setAttribute("stroke-width", ".7");
            shape.setAttribute("stroke-linejoin", "round");
            svg.appendChild(shape);
            petal.appendChild(svg);
            layer.appendChild(petal);
        }
        return layer;
    };

    const syncLayers = () => {
        syncFrame = 0;
        const cards = Array.from(document.querySelectorAll(selector));
        const activeCards = new Set(cards);
        observedCards.forEach(card => {
            if (activeCards.has(card)) return;
            resizeObserver?.unobserve(card);
            observedCards.delete(card);
        });
        cards.forEach(card => {
            const enabled = window.getComputedStyle(card).getPropertyValue("--pz-lkc-sakura-enabled").trim() === "1";
            const existing = card.querySelector(layerSelector);
            if (!enabled) {
                existing?.remove();
                resizeObserver?.unobserve(card);
                observedCards.delete(card);
                return;
            }
            if (resizeObserver && !observedCards.has(card)) {
                resizeObserver.observe(card);
                observedCards.add(card);
            }
            const width = Math.max(1, card.clientWidth);
            const height = Math.max(1, card.clientHeight);
            if (existing && Number(existing.dataset.width) === width && Number(existing.dataset.height) === height) return;
            existing?.remove();
            card.appendChild(createLayer(width, height));
        });
    };

    const scheduleSync = () => {
        if (!syncFrame) syncFrame = window.requestAnimationFrame(syncLayers);
    };

    const init = () => {
        new MutationObserver(scheduleSync).observe(document.body, { childList: true, subtree: true });
        document.addEventListener("input", scheduleSync, true);
        document.addEventListener("change", scheduleSync, true);
        window.addEventListener("resize", scheduleSync);
        window.addEventListener("load", scheduleSync);
        scheduleSync();
    };

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init, { once: true });
    } else {
        init();
    }
})();
