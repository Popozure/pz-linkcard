(function () {
    "use strict";

    const cards = new Map();
    const dirty = new Set();
    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
    let frame = 0;
    let scanFrame = 0;

    const reset = state => {
        state.point = null;
        state.rect = null;
        dirty.delete(state);
        state.card.style.removeProperty("--pz-lkc-tracking-x");
        state.card.style.removeProperty("--pz-lkc-tracking-y");
        state.card.classList.remove("pz-lkc-tracking-active");
    };
    const requestUpdate = state => {
        dirty.add(state);
        if (!frame) frame = window.requestAnimationFrame(update);
    };
    function update() {
        frame = 0;
        const updates = Array.from(dirty).map(state => {
            if (!state.point || !state.card.isConnected || reducedMotion.matches) return { state };
            // Freeze the neutral rectangle during pointer movement to avoid tilt feedback.
            if (!state.rect) state.rect = state.card.getBoundingClientRect();
            const rect = state.rect;
            if (rect.width <= 0 || rect.height <= 0) return { state };
            const clamp = value => Math.max(-1, Math.min(1, value));
            const x = clamp((state.point.x - rect.left - rect.width / 2) / (rect.width / 2));
            const y = clamp((state.point.y - rect.top - rect.height / 2) / (rect.height / 2));
            return { state, rotateX: -y * 12, rotateY: x * 12 };
        });
        dirty.clear();
        updates.forEach(({ state, rotateX, rotateY }) => {
            if (rotateX === undefined) {
                reset(state);
                return;
            }
            state.card.style.setProperty("--pz-lkc-tracking-x", `${rotateX.toFixed(3)}deg`);
            state.card.style.setProperty("--pz-lkc-tracking-y", `${rotateY.toFixed(3)}deg`);
            state.card.classList.add("pz-lkc-tracking-active");
        });
    }
    const bind = card => {
        const state = { card, rect: null, point: null };
        const move = event => {
            if (event.pointerType === "touch" || reducedMotion.matches) return;
            state.point = { x: event.clientX, y: event.clientY };
            requestUpdate(state);
        };
        const leave = () => reset(state);
        state.handlers = { pointerenter: move, pointermove: move, pointerleave: leave, pointercancel: leave };
        Object.entries(state.handlers).forEach(([name, handler]) => card.addEventListener(name, handler));
        cards.set(card, state);
    };
    const scan = () => {
        scanFrame = 0;
        const activeCards = new Set(Array.from(document.querySelectorAll(".lkc-external-wrap, .lkc-internal-wrap")).filter(card =>
            window.getComputedStyle(card).getPropertyValue("--pz-lkc-tracking-enabled").trim() === "1"
        ));
        cards.forEach((state, card) => {
            if (activeCards.has(card)) return;
            reset(state);
            Object.entries(state.handlers).forEach(([name, handler]) => card.removeEventListener(name, handler));
            cards.delete(card);
        });
        activeCards.forEach(card => {
            if (!cards.has(card)) {
                bind(card);
            } else {
                const state = cards.get(card);
                if (state.point) {
                    state.rect = null;
                    requestUpdate(state);
                }
            }
        });
    };
    const scheduleScan = () => {
        if (!scanFrame) scanFrame = window.requestAnimationFrame(scan);
    };
    const refreshBounds = () => {
        cards.forEach(state => {
            if (!state.point) return;
            state.rect = null;
            requestUpdate(state);
        });
    };
    const resetAll = () => cards.forEach(reset);
    const init = () => {
        new MutationObserver(scheduleScan).observe(document.body, { childList: true, subtree: true });
        document.addEventListener("input", scheduleScan, true);
        document.addEventListener("change", scheduleScan, true);
        document.addEventListener("scroll", refreshBounds, true);
        window.addEventListener("resize", refreshBounds);
        window.addEventListener("load", scheduleScan);
        window.addEventListener("blur", resetAll);
        reducedMotion.addEventListener("change", resetAll);
        document.addEventListener("visibilitychange", () => { if (document.hidden) resetAll(); });
        scheduleScan();
    };
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init, { once: true });
    } else {
        init();
    }
})();
