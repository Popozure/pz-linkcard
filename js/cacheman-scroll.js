(function () {
    "use strict";
    const key = "pz-card-list-scroll:" + location.pathname + ":pz-linkcard-cacheman";
    let position;
    try { position = JSON.parse(sessionStorage.getItem(key)); } catch (_) { return; }
    if (!position || !Number.isFinite(position.x) || !Number.isFinite(position.y)) return;
    if ("scrollRestoration" in history) history.scrollRestoration = "manual";
    let stopped = false;
    let frame = null;
    const restore = () => {
        if (stopped) return;
        window.scrollTo({ left: position.x, top: position.y, behavior: "instant" });
        const list = document.querySelector(".pz-card-list-scroll");
        if (list) {
            list.scrollLeft = position.listX || 0;
            list.scrollTop = position.listY || 0;
        }
    };
    const tick = () => {
        restore();
        if (!stopped && document.readyState !== "complete") frame = requestAnimationFrame(tick);
    };
    const stop = () => {
        stopped = true;
        if (frame !== null) cancelAnimationFrame(frame);
    };
    // Restore as the parser adds content, before the main UI initializes.
    tick();
    document.addEventListener("DOMContentLoaded", restore, { once: true });
    window.addEventListener("load", () => { restore(); stop(); }, { once: true });
    window.addEventListener("pageshow", event => { if (event.persisted) { stopped = false; restore(); stop(); } });
    ["wheel", "touchstart", "pointerdown", "keydown"].forEach(type => {
        window.addEventListener(type, stop, { capture: true, passive: true, once: true });
    });
})();
