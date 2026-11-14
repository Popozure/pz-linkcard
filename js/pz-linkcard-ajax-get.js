(() => {
    "use strict";

    const config = window.pzLinkCardAjaxGet;
    if (!config?.ajaxUrl || !config?.nonce) return;

    const interval = Math.max(1000, Number(config.interval) || 3000);
    const maxTries = Math.max(1, Number(config.maxTries) || 40);
    const pendingSelector = ".pz-lkc-pending[data-pz-lkc-pending='1'][data-lkc-id]";
    const polledCards = new WeakSet();
    const observedDocuments = new WeakSet();
    const observedFrames = new WeakSet();

    const pollCard = card => {
        if (polledCards.has(card) || !card.dataset.pzLkcToken || !card.dataset.pzLkcExpires) return;
        polledCards.add(card);
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
                    const cardDocument = card.ownerDocument;
                    const template = cardDocument.createElement("template");
                    template.innerHTML = result.data.html.trim();
                    const replacement = template.content.firstElementChild;
                    if (replacement && card.isConnected) {
                        const existing = replacement.id ? cardDocument.getElementById(replacement.id) : null;
                        if (existing && existing !== card) replacement.removeAttribute("id");
                        card.replaceWith(replacement);
                        stopped = true;
                        const EventConstructor = cardDocument.defaultView?.CustomEvent || CustomEvent;
                        cardDocument.dispatchEvent(new EventConstructor("pz-linkcard:loaded", { detail: { card: replacement } }));
                        return;
                    }
                    if (!card.isConnected) {
                        stopped = true;
                        return;
                    }
                }
            } catch (error) {
                // A temporary network failure is retried until maxTries is reached.
            }

            if (!stopped && tries < maxTries) window.setTimeout(poll, interval);
        };

        window.setTimeout(poll, interval);
    };

    const scanNode = node => {
        if (node.nodeType !== 1) return;
        if (node.matches(pendingSelector)) pollCard(node);
        node.querySelectorAll(pendingSelector).forEach(pollCard);
        if (node.tagName === "IFRAME") observeFrame(node);
        node.querySelectorAll("iframe").forEach(observeFrame);
    };

    const observeDocument = targetDocument => {
        if (!targetDocument?.documentElement || observedDocuments.has(targetDocument)) return;
        observedDocuments.add(targetDocument);
        scanNode(targetDocument.documentElement);
        const Observer = targetDocument.defaultView?.MutationObserver || MutationObserver;
        new Observer(records => {
            records.forEach(record => record.addedNodes.forEach(scanNode));
        }).observe(targetDocument.documentElement, { childList: true, subtree: true });
    };

    function observeFrame(frame) {
        if (observedFrames.has(frame)) return;
        observedFrames.add(frame);
        const attach = () => {
            try {
                if (frame.contentDocument) observeDocument(frame.contentDocument);
            } catch (error) {
                // Cross-origin frames cannot contain this site's link-card preview.
            }
        };
        frame.addEventListener("load", attach);
        attach();
    }

    observeDocument(document);
})();
