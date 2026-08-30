document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("a.lkc-link").forEach(function (el) {
        el.addEventListener("click", function (e) {
            let lkc_id = el.getAttribute("data-lkc-id");
            if (!lkc_id) return;

            fetch(pz_lkc_ajax.ajax_url, {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({
                    action: "pz_lkc_click_count",
                    nonce: pz_lkc_ajax.nonce,
                    lkc_id: lkc_id
                })
            });
        });
    });
});
