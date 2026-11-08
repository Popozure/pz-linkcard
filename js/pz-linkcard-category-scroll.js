(() => {
    "use strict";

    let suppressedList = null;
    let suppressedUntil = 0;
    document.addEventListener("dragstart", event => {
        if (event.target.closest?.(".lkc-cat-list")) event.preventDefault();
    });
    document.addEventListener("click", event => {
        if (suppressedList?.contains(event.target) && performance.now() < suppressedUntil) {
            event.preventDefault();
            event.stopImmediatePropagation();
        }
    }, true);
    document.addEventListener("pointerdown", event => {
        const list = event.target.closest?.(".lkc-cat-list");
        if (!list || event.button !== 0 || event.pointerType === "touch"
            || list.scrollWidth <= list.clientWidth) return;

        const pointerId = event.pointerId;
        const startX = event.clientX;
        const startScroll = list.scrollLeft;
        let dragging = false;
        const move = moveEvent => {
            if (moveEvent.pointerId !== pointerId) return;
            const distance = moveEvent.clientX - startX;
            if (!dragging && Math.abs(distance) < 5) return;
            if (!dragging) {
                dragging = true;
                list.setPointerCapture(pointerId);
                list.classList.add("pz-category-dragging");
            }
            moveEvent.preventDefault();
            list.scrollLeft = startScroll - distance;
        };
        const finish = endEvent => {
            if (endEvent.pointerId !== pointerId) return;
            document.removeEventListener("pointermove", move);
            document.removeEventListener("pointerup", finish);
            document.removeEventListener("pointercancel", finish);
            list.removeEventListener("lostpointercapture", finish);
            if (dragging) {
                suppressedList = list;
                suppressedUntil = performance.now() + 400;
                list.classList.remove("pz-category-dragging");
                if (list.hasPointerCapture(pointerId)) list.releasePointerCapture(pointerId);
            }
        };
        document.addEventListener("pointermove", move, { passive: false });
        document.addEventListener("pointerup", finish);
        document.addEventListener("pointercancel", finish);
        list.addEventListener("lostpointercapture", finish);
    });
})();
