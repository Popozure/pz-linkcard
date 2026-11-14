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
        const snowmanVisible = random() < .07;
        const snowmanLeft = 12 + random() * 68;
        const bankLayers = Array.from({ length: 5 }, (_, index) => {
            const position = (index * 25 + (random() - .5) * 8).toFixed(2);
            const width = (22 + random() * 10).toFixed(2);
            const height = (55 + random() * 45).toFixed(2);
            return `radial-gradient(ellipse ${width}% ${height}% at ${position}% 100%, #fff 96%, transparent 100%)`;
        });
        const bankHeight = 18 + random() * 3;
        if (snowmanVisible) {
            bankLayers.push(`radial-gradient(ellipse 20% 65% at ${snowmanLeft.toFixed(2)}% 100%, #fff 96%, transparent 100%)`);
        }
        const bank = bankLayers.join(", ");
        const bankDuration = 34 + random() * 12;
        const bankDelay = random() * 4;
        const snowmanBodyDelay = bankDuration * (.55 + random() * .25) + bankDelay;
        const snowmanHeadDelay = snowmanBodyDelay + 3.5 + random() * 4;
        const snowmanBottom = bankHeight * (.2 + random() * .08);
        return {
            image: `url("data:image/svg+xml,${encodeURIComponent(svg)}")`,
            bank,
            bankHeight: `${bankHeight.toFixed(2)}px`,
            bankDuration: `${bankDuration.toFixed(2)}s`,
            bankDelay: `${bankDelay.toFixed(2)}s`,
            snowmanBodyDelay: `${snowmanBodyDelay.toFixed(2)}s`,
            snowmanHeadDelay: `${snowmanHeadDelay.toFixed(2)}s`,
            snowmanLeft: `${snowmanLeft.toFixed(2)}%`,
            snowmanBottom: `${snowmanBottom.toFixed(2)}px`,
            snowmanVisible,
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
            if (pattern.snowmanVisible) {
                setProperty(card.style, "--pz-lkc-snowman-body-delay", pattern.snowmanBodyDelay);
                setProperty(card.style, "--pz-lkc-snowman-head-delay", pattern.snowmanHeadDelay);
                setProperty(card.style, "--pz-lkc-snowman-left", pattern.snowmanLeft);
                setProperty(card.style, "--pz-lkc-snowman-bottom", pattern.snowmanBottom);
            }
            if (pattern.snowmanVisible && !card.querySelector(":scope > .pz-lkc-snowman")) {
                const snowman = document.createElement("span");
                snowman.className = "pz-lkc-snowman";
                snowman.setAttribute("aria-hidden", "true");
                card.appendChild(snowman);
            } else if (!pattern.snowmanVisible) {
                card.querySelector(":scope > .pz-lkc-snowman")?.remove();
            }
            nextCards.add(card);
        });
        activeCards.forEach(card => {
            if (nextCards.has(card)) return;
            card.style.removeProperty("--pz-lkc-snow-image");
            card.style.removeProperty("--pz-lkc-snow-bank");
            card.style.removeProperty("--pz-lkc-snow-bank-height");
            card.style.removeProperty("--pz-lkc-snow-bank-duration");
            card.style.removeProperty("--pz-lkc-snow-bank-delay");
            card.style.removeProperty("--pz-lkc-snowman-body-delay");
            card.style.removeProperty("--pz-lkc-snowman-head-delay");
            card.style.removeProperty("--pz-lkc-snowman-left");
            card.style.removeProperty("--pz-lkc-snowman-bottom");
            card.querySelector(":scope > .pz-lkc-snowman")?.remove();
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
