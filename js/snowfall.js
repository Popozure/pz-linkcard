(function () {
    "use strict";

    const patterns = new WeakMap();
    let activeCards = new Set();
    let cardIndex = 0;
    let frame = 0;

    const createPattern = () => {
        let seed = 1069 + ++cardIndex * 997;
        let motionSeed = seed + 4729;
        const random = () => {
            seed = (seed * 251 + 19) % 65521;
            return seed / 65521;
        };
        const motionRandom = () => {
            motionSeed = (motionSeed * 251 + 19) % 65521;
            return motionSeed / 65521;
        };
        const radii = [2, 1.5, 2.5, 2, 1.5, 1.5, 2, 2, 1.5, 2.5, 2, 1.5, 1.5, 2];
        const circles = radii.map((radius, index) => {
            const x = (14 + random() * 132).toFixed(2);
            const y = (8 + (index + random()) * 184 / radii.length).toFixed(2);
            const duration = (2.8 + random() * 2.5).toFixed(2);
            const delay = (-random() * 5.3).toFixed(2);
            const direction = random() < .5 ? "alternate" : "alternate-reverse";
            const fallDuration = (10.5 / (radius - .5) * (.94 + motionRandom() * .12)).toFixed(2);
            const fallDelay = (-motionRandom() * Number(fallDuration)).toFixed(2);
            const copies = [-200, 0, 200].map(offset => `<circle cx="${x}" cy="${(Number(y) + offset).toFixed(2)}" r="${radius}"/>`).join("");
            return `<g class="fall" style="animation-duration:${fallDuration}s;animation-delay:${fallDelay}s"><g class="sway" style="animation-duration:${duration}s;animation-delay:${delay}s;animation-direction:${direction}">${copies}</g></g>`;
        }).join("");
        const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="160" height="200" viewBox="0 0 160 200"><style>@keyframes sway{from{transform:translateX(-9px)}to{transform:translateX(9px)}}@keyframes fall{from{transform:translateY(0)}to{transform:translateY(200px)}}.fall{animation:fall 9s linear infinite}.sway{animation:sway 4s ease-in-out infinite alternate}@media(prefers-reduced-motion:reduce){.fall,.sway{animation:none!important}}</style><g fill="white">${circles}</g></svg>`;
        const bank = Array.from({ length: 5 }, (_, index) => {
            const position = (index * 25 + (random() - .5) * 8).toFixed(2);
            const width = (22 + random() * 10).toFixed(2);
            const height = (55 + random() * 45).toFixed(2);
            return `radial-gradient(ellipse ${width}% ${height}% at ${position}% 100%, #fff 96%, transparent 100%)`;
        }).join(", ");
        return {
            image: `url("data:image/svg+xml,${encodeURIComponent(svg)}")`,
            bank,
            bankHeight: `${(12 + random() * 4).toFixed(2)}px`,
            bankDuration: `${(34 + random() * 12).toFixed(2)}s`,
            bankDelay: `${(random() * 4).toFixed(2)}s`,
        };
    };
    const setProperty = (style, name, value) => {
        if (style.getPropertyValue(name) !== value) style.setProperty(name, value);
    };
    const refresh = () => {
        frame = 0;
        const nextCards = new Set();
        document.querySelectorAll(".lkc-external-wrap, .lkc-internal-wrap").forEach(card => {
            if (window.getComputedStyle(card).getPropertyValue("--pz-lkc-snow-enabled").trim() !== "1") return;
            if (!patterns.has(card)) patterns.set(card, createPattern());
            const pattern = patterns.get(card);
            setProperty(card.style, "--pz-lkc-snow-image", pattern.image);
            setProperty(card.style, "--pz-lkc-snow-bank", pattern.bank);
            setProperty(card.style, "--pz-lkc-snow-bank-height", pattern.bankHeight);
            setProperty(card.style, "--pz-lkc-snow-bank-duration", pattern.bankDuration);
            setProperty(card.style, "--pz-lkc-snow-bank-delay", pattern.bankDelay);
            nextCards.add(card);
        });
        activeCards.forEach(card => {
            if (nextCards.has(card)) return;
            card.style.removeProperty("--pz-lkc-snow-image");
            card.style.removeProperty("--pz-lkc-snow-bank");
            card.style.removeProperty("--pz-lkc-snow-bank-height");
            card.style.removeProperty("--pz-lkc-snow-bank-duration");
            card.style.removeProperty("--pz-lkc-snow-bank-delay");
        });
        activeCards = nextCards;
    };
    const scheduleRefresh = () => {
        if (!frame) frame = window.requestAnimationFrame(refresh);
    };
    const init = () => {
        // Handles new cards and regenerated settings-preview CSS without a frame loop.
        new MutationObserver(scheduleRefresh).observe(document.body, { childList: true, subtree: true });
        document.addEventListener("input", scheduleRefresh, true);
        document.addEventListener("change", scheduleRefresh, true);
        window.addEventListener("load", scheduleRefresh);
        scheduleRefresh();
    };
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init, { once: true });
    } else {
        init();
    }
})();
