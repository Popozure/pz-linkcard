(() => {
    "use strict";

    const style = document.createElement("style");
    style.textContent = '.lkc-browser-tab:has(.lkc-browser-close)::after{display:none!important}.lkc-browser-close{position:absolute;top:50%;right:9px;display:block;transform:translateY(-50%);color:#5f6368;font-size:14px;line-height:1;cursor:pointer}.lkc-browser-close:focus-visible{outline:1px solid #5f6368;outline-offset:2px}';
    document.head.append(style);
    const running = new WeakSet();

    async function closeAndReopen(card) {
        if (running.has(card)) return;
        running.add(card);
        const reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
        const duration = reduced ? 0 : 220;
        let closing;
        let opening;
        try {
            closing = card.animate([
                { opacity: 1, transform: "scale(1)" },
                { opacity: 0, transform: "scale(0.05) translateY(30px)" }
            ], { duration, easing: "ease-in", fill: "forwards" });
            await closing.finished;
            await new Promise(resolve => window.setTimeout(resolve, 3000));
            opening = card.animate([
                { opacity: 0, transform: "scale(0.05) translateY(30px)" },
                { opacity: 1, transform: "scale(1)" }
            ], { duration, easing: "ease-out", fill: "forwards" });
            closing.cancel();
            await opening.finished;
        } finally {
            closing?.cancel();
            opening?.cancel();
            running.delete(card);
        }
    }

    function activate(event) {
        const close = event.target.closest?.(".lkc-browser-close");
        if (!close) return;
        if (event.type === "keydown" && event.key !== "Enter" && event.key !== " ") return;
        const card = close.closest(".linkcard");
        if (!card) return;
        event.preventDefault();
        event.stopImmediatePropagation();
        if (!event.repeat) closeAndReopen(card);
    }
    document.addEventListener("click", activate, true);
    document.addEventListener("keydown", activate, true);
})();
