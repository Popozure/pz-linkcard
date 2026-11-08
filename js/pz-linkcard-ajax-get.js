(() => {
    "use strict";

    const config = window.pzLinkCardAjaxGet;
    if (!config?.ajaxUrl || !config?.nonce) return;

    const interval = Math.max(1000, Number(config.interval) || 3000);
    const maxTries = Math.max(1, Number(config.maxTries) || 40);

    document.querySelectorAll(".pz-lkc-pending[data-pz-lkc-pending='1'][data-lkc-id]").forEach(card => {
        if (!card.dataset.pzLkcToken || !card.dataset.pzLkcExpires) return;
        let tries = 0;
        let stopped = false;

        const poll = async () => {
            if (stopped || !card.isConnected || tries >= maxTries) return;
            tries++;

            const body = new URLSearchParams({
                action: "pz_lkc_ajax_get",
                nonce: config.nonce,
                card_id: card.dataset.lkcId || "",
                source_post_id: card.dataset.postId || "0",
                expires: card.dataset.pzLkcExpires,
                card_token: card.dataset.pzLkcToken,
            });

            try {
                const response = await fetch(config.ajaxUrl, {
                    method: "POST",
                    credentials: "same-origin",
                    headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
                    body: body.toString(),
                });
                const result = await response.json();
                if (response.status === 403 || response.status === 404) {
                    stopped = true;
                    return;
                }
                if (result?.success && result.data?.ready && result.data.html) {
                    const template = document.createElement("template");
                    template.innerHTML = result.data.html.trim();
                    const replacement = template.content.firstElementChild;
                    if (replacement) {
                        const existing = replacement.id ? document.getElementById(replacement.id) : null;
                        if (existing && existing !== card ) {
                            replacement.removeAttribute("id");
                        }
                        card.replaceWith(replacement);
                        stopped = true;
                        document.dispatchEvent(new CustomEvent("pz-linkcard:loaded", { detail: { card: replacement } }));
                        return;
                    }
                }
            } catch (error) {
                // A temporary network failure is retried until maxTries is reached.
            }

            if (!stopped && tries < maxTries) window.setTimeout(poll, interval);
        };

        window.setTimeout(poll, interval);
    });
})();
