document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("a.lkc-link").forEach(function (el) {
        el.addEventListener("click", function (e) {
            let card = el.closest(".linkcard[data-lkc-id]");
            let lkc_id = card?.getAttribute("data-lkc-id") || "";
            let post_id = card.getAttribute("data-post-id") || "";
            let click_token = card.getAttribute("data-click-token") || "";
			if (!click_token) return;

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
            });
        });
    });
});
