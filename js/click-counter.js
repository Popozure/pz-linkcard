document.addEventListener("DOMContentLoaded", function () {
    const countedCards = new WeakSet();
    document.addEventListener("click", function (e) {
        const link = e.target.closest?.("a.lkc-link");
        const card = link?.closest(".linkcard[data-lkc-id]");
        if (!card || countedCards.has(card)) return;

        const lkc_id = card.getAttribute("data-lkc-id") || "";
        const post_id = card.getAttribute("data-post-id") || "";
        const click_token = card.getAttribute("data-click-token") || "";
        if (!lkc_id || !click_token) return;

        // Track each rendered card independently for this page load.
        countedCards.add(card);
        fetch(pz_lkc_ajax.ajax_url, {
            method: "POST",
            keepalive: true,
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({
                action: "pz_lkc_click_count",
                nonce: pz_lkc_ajax.nonce,
                lkc_id: lkc_id,
                post_id: post_id,
                click_token: click_token
            })
        }).catch(() => {});
    });
});
