(function ($) {
    "use strict";
    $(function () {
        const root = document.querySelector(".pz-card-list");
        if (!root) return;
        const updateUrlTooltip = link => {
            if (link.scrollWidth > link.clientWidth) link.title = link.textContent;
            else link.removeAttribute("title");
        };
        const updateUrlTooltips = () => root.querySelectorAll(".pz-card-list-url").forEach(updateUrlTooltip);
        ["pointerover", "focusin"].forEach(type => root.addEventListener(type, event => {
            const link = event.target instanceof Element ? event.target.closest(".pz-card-list-url") : null;
            if (link) updateUrlTooltip(link);
        }));
        window.addEventListener("resize", updateUrlTooltips);
        updateUrlTooltips();
        document.fonts?.ready.then(updateUrlTooltips);
        const pendingRows = new Map([...root.querySelectorAll("tr[data-pz-pending-id]")].map(row => [Number(row.dataset.pzPendingId), row]));
        const setRowActionsPending = (row, pending) => {
            row.querySelectorAll(".row-actions button").forEach(button => { button.disabled = pending; });
            row.querySelectorAll(".row-actions a").forEach(link => {
                if (pending) {
                    if (link.hasAttribute("href")) link.dataset.pzDisabledHref = link.getAttribute("href");
                    link.removeAttribute("href");
                    link.setAttribute("aria-disabled", "true");
                    link.setAttribute("tabindex", "-1");
                } else {
                    if (link.dataset.pzDisabledHref) link.setAttribute("href", link.dataset.pzDisabledHref);
                    delete link.dataset.pzDisabledHref;
                    link.removeAttribute("aria-disabled");
                    link.removeAttribute("tabindex");
                }
            });
        };
        pendingRows.forEach(row => setRowActionsPending(row, true));
        let pendingTimer = null;
        const pendingRequest = async params => {
            const response = await fetch(pzCardList.ajaxUrl, {
                method: "POST", credentials: "same-origin",
                headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
                body: new URLSearchParams({ action: "pz_lkc_cacheman_pending", nonce: pzCardList.pendingNonce, ...params })
            });
            const result = await response.json();
            if (!response.ok || !result?.success) throw new Error(pzCardList.queueFailed);
            return result.data;
        };
        const schedulePending = () => {
            if (!pendingRows.size || pendingTimer !== null) return;
            pendingTimer = setTimeout(async () => {
                try {
                    const body = new URLSearchParams({ action: "pz_lkc_cacheman_pending", nonce: pzCardList.pendingNonce });
                    pendingRows.forEach((row, id) => body.append("card_ids[]", String(id)));
                    const response = await fetch(pzCardList.ajaxUrl, { method: "POST", credentials: "same-origin", body });
                    const result = await response.json();
                    if (response.ok && result?.success) {
                        const ready = result.data.ready_cards || [];
                        let documentCopy = null;
                        if (ready.length) {
                            const url = new URL(window.location.href);
                            url.searchParams.set("pz_refresh_ids", ready.map(card => Number(card.id)).join(","));
                            const page = await fetch(url, { credentials: "same-origin", cache: "no-store" });
                            if (!page.ok) throw new Error("Unable to refresh rows");
                            documentCopy = new DOMParser().parseFromString(await page.text(), "text/html");
                        }
                        ready.forEach(card => {
                            const row = pendingRows.get(Number(card.id));
                            if (!row) return;
                            const replacement = documentCopy.querySelector(`tr[data-pz-card-id="${Number(card.id)}"]`);
                            if (!replacement || replacement.hasAttribute("data-pz-pending-id")) return;
                            const checked = row.querySelector('input[type="checkbox"]')?.checked;
                            const focused = row.contains(document.activeElement);
                            const next = document.importNode(replacement, true);
                            const checkbox = next.querySelector('input[type="checkbox"]');
                            if (checkbox) checkbox.checked = !!checked;
                            row.replaceWith(next);
                            if (focused) checkbox?.focus({ preventScroll: true });
                            pendingRows.delete(Number(card.id));
                        });
                        updateUrlTooltips();
                        updateSelectedCount();
                    }
                } catch (error) { /* Keep the reservation and retry status checks. */ }
                pendingTimer = null;
                schedulePending();
            }, 2000);
        };
        const queueRefresh = async button => {
            const id = Number(button.value);
            const row = button.closest("tr[data-pz-card-id]");
            if (!row || !Number.isInteger(id) || id <= 0 || pendingRows.has(id)) return;
            const title = row.querySelector(".pz-card-list-title-text");
            const previousTitle = title?.textContent;
            setRowActionsPending(row, true);
            row.dataset.pzPendingId = String(id);
            if (title) {
                title.textContent = pzCardList.retrieving;
                const spinner = document.createElement("span");
                spinner.className = "pz-man-pending-loader";
                spinner.setAttribute("aria-hidden", "true");
                title.appendChild(spinner);
            }
            try {
                const result = await pendingRequest({ queue_card_id: String(id) });
                if (Number(result.queued_id) !== id) throw new Error(pzCardList.queueFailed);
                pendingRows.set(id, row);
                schedulePending();
            } catch (error) {
                setRowActionsPending(row, false);
                delete row.dataset.pzPendingId;
                if (title) title.textContent = previousTitle;
                window.alert(pzCardList.queueFailed);
            }
        };
        schedulePending();
        const actionForm = document.getElementById("pz-linkcard-actions");
        const rowChecks = () => [...root.querySelectorAll('tbody input[name="card_ids[]"]')];
        const counters = [...root.querySelectorAll(".displaying-num")].map(total => {
            const counter = document.createElement("span");
            counter.style.marginInlineStart = "1em";
            counter.setAttribute("aria-live", "polite");
            total.after(counter);
            return counter;
        });
        const updateSelectedCount = () => {
            const count = rowChecks().filter(input => input.checked).length;
            const label = count === 1 ? pzCardList.selectedItem : pzCardList.selectedItems;
            counters.forEach(counter => { counter.textContent = count ? label.replace("%s", count.toLocaleString()) : ""; });
        };
        root.addEventListener("change", () => requestAnimationFrame(updateSelectedCount));
        root.addEventListener("click", () => requestAnimationFrame(updateSelectedCount));
        window.addEventListener("pageshow", updateSelectedCount);
        updateSelectedCount();
        const scrollKey = `pz-card-list-scroll:${location.pathname}${location.search}`;
        const saveScroll = () => {
            try { sessionStorage.setItem(scrollKey, JSON.stringify({ x: scrollX, y: scrollY })); } catch (error) { /* Storage may be unavailable. */ }
        };
        window.addEventListener("pagehide", saveScroll);
        const restoreScroll = () => {
            try {
                const position = JSON.parse(sessionStorage.getItem(scrollKey));
                if (position) requestAnimationFrame(() => window.scrollTo({ left: position.x, top: position.y, behavior: "instant" }));
            } catch (error) { /* Ignore unavailable or invalid storage. */ }
        };
        restoreScroll();
        window.addEventListener("pageshow", restoreScroll);
        actionForm?.addEventListener("submit", event => {
            const name = event.submitter?.name;
            if (name === "single_renew") {
                event.preventDefault();
                queueRefresh(event.submitter);
                return;
            }
            if (name !== "single_delete") {
                const action = actionForm.elements.namedItem("bulk_action");
                const count = rowChecks().filter(input => input.checked).length;
                if (!action?.value || !count) {
                    event.preventDefault();
                    window.alert(pzCardList.emptyMessage);
                    if (!action?.value) action?.focus();
                    else rowChecks()[0]?.focus();
                    return;
                }
                if (count >= 11 && !window.confirm(pzCardList.manyMessage)) { event.preventDefault(); return; }
            }
            const deleting = name === "single_delete" || (name !== "single_renew" && actionForm.elements.namedItem("bulk_action")?.value === "delete");
            if (deleting && !window.confirm(actionForm.dataset.deleteConfirm)) event.preventDefault();
        });
        document.addEventListener("submit", event => { if (!event.defaultPrevented) saveScroll(); });
        const loader = document.getElementById("pz-overlay-proc");
        let loaderTimer = null;
        const showLoader = () => {
            if (loaderTimer !== null) clearTimeout(loaderTimer);
            root.setAttribute("aria-busy", "true");
            loaderTimer = setTimeout(() => {
                loaderTimer = null;
                if (loader) {
                    const menu = document.getElementById("adminmenuwrap");
                    const bar = document.getElementById("pz-infobar") || document.getElementById("wpadminbar");
                    loader.style.setProperty("--pz-overlay-top", `${Math.max(0, bar?.getBoundingClientRect().bottom || 0)}px`);
                    loader.style.setProperty("--pz-overlay-left", `${menu && getComputedStyle(menu).display !== "none" ? Math.max(0, menu.getBoundingClientRect().right) : 0}px`);
                    loader.classList.remove("hidden");
                    loader.style.display = "flex";
                    loader.classList.add("pz-overlay-proc-active");
                }
                }, 500);
        };
        document.addEventListener("submit", event => {
            if (event.defaultPrevented || !event.target.matches("#pz-linkcard-actions, .pz-card-list-form, #adv-settings")) return;
            showLoader();
        });
        root.addEventListener("click", event => {
            if (event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
            const link = event.target instanceof Element ? event.target.closest(".subsubsub a") : null;
            if (link && !link.hasAttribute("download") && (!link.target || link.target === "_self")) showLoader();
        });
        window.addEventListener("pageshow", () => {
            if (loaderTimer !== null) {
                clearTimeout(loaderTimer);
                loaderTimer = null;
            }
            if (loader) {
                loader.classList.remove("pz-overlay-proc-active");
                loader.style.display = "none";
            }
            root.removeAttribute("aria-busy");
        });
        const alignOptionsPanel = () => {
            const panel = document.getElementById("screen-meta");
            const button = document.getElementById("show-settings-link");
            if (!panel || !button) return;
            const parent = panel.offsetParent || panel.parentElement;
            const parentRect = parent.getBoundingClientRect();
            const right = parentRect.left + parent.clientLeft + parent.clientWidth - button.getBoundingClientRect().right;
            panel.style.setProperty("--pz-options-panel-right", `${right}px`);
        };
        $(document).on("screen:options:open", alignOptionsPanel);
        window.addEventListener("resize", alignOptionsPanel);
        // Core assumes the other tab is hidden. Close its content before switching.
        document.addEventListener("click", event => {
            const button = event.target instanceof Element ? event.target.closest("#screen-meta-links .show-settings") : null;
            if (!button) return;
            const selectedId = button.getAttribute("aria-controls");
            document.querySelectorAll("#screen-meta-links .show-settings").forEach(other => {
                if (other === button) return;
                const panel = document.getElementById(other.getAttribute("aria-controls"));
                if (!panel || panel.id === selectedId) return;
                $(panel).stop(true, true).hide().addClass("hidden");
                other.classList.remove("screen-meta-active");
                other.setAttribute("aria-expanded", "false");
            });
            const container = document.getElementById("screen-meta");
            if (container) {
                // Fix geometry before core measures the content for slideDown().
                container.dataset.pzPanel = selectedId === "screen-options-wrap" ? "options" : "help";
                if (selectedId === "screen-options-wrap") alignOptionsPanel();
            }
        }, true);
        // Header IDs are prefixed to avoid WordPress editor CSS such as #excerpt.
        if (window.columns?.useCheckboxesForHidden) window.columns.useCheckboxesForHidden();
        const pageInput = document.getElementById("pz_linkcard_cards_per_page");
        if (pageInput) {
            const select = document.createElement("select");
            select.id = pageInput.id;
            select.name = pageInput.name;
            (pzCardList.pageChoices || [10, 20, 50, 100]).forEach(value => {
                const option = new Option(String(value), String(value));
                option.selected = String(value) === pageInput.value;
                select.add(option);
            });
            pageInput.replaceWith(select);
            const apply = select.form?.querySelector(".submit");
            if (apply) select.after(apply);
        }
        const options = document.getElementById("adv-settings");
        if (options) {
            const labels = [...options.querySelectorAll("label")].filter(label => label.querySelector(".hide-column-tog"));
            const grid = document.createElement("div");
            grid.className = "pz-card-option-grid";
            const defaults = new Set(["id", "excerpt", "domain", "sns", "regist_time", "update_time", "use_post_id1", "click_count", "update_result"]);
            labels.forEach(label => {
                const column = label.querySelector("input").value;
                label.classList.add(defaults.has(column) ? "pz-card-option-default" : "pz-card-option-optional");
                if (["sns_nexttime", "alive_nexttime"].includes(column)) label.classList.add("pz-card-option-next");
            });
            if (labels.length) {
                labels[0].before(grid);
                labels.forEach(label => grid.appendChild(label));
            }
        }
        const closePanel = () => {
            document.querySelectorAll("#screen-meta-links .show-settings[aria-expanded='true']").forEach(button => button.click());
        };
        document.addEventListener("click", event => {
            if (event.target instanceof Element && !event.target.closest("#screen-meta, #screen-meta-links")) closePanel();
        });
        const search = document.getElementById("pz-linkcard-search-search-input");
        const focusSearch = () => { search?.focus(); search?.select(); };
        document.addEventListener("keydown", event => {
            const target = event.target;
            const editing = target instanceof Element && (target.matches("input, textarea, select") || target.isContentEditable);
            const modifier = event.ctrlKey || event.metaKey;
            if (event.key === "Escape") {
                closePanel();
            } else if (event.key === "F1" || event.key === "F2") {
                event.preventDefault();
                const button = document.getElementById(event.key === "F1" ? "contextual-help-link" : "show-settings-link");
                if (button) {
                    const rect = button.getBoundingClientRect();
                    const bar = document.getElementById("pz-infobar") || document.getElementById("wpadminbar");
                    const visibleTop = Math.max(0, bar?.getBoundingClientRect().bottom || 0);
                    if (rect.top < visibleTop || rect.bottom > window.innerHeight) {
                        window.scrollBy({ top: rect.top - visibleTop - 8, behavior: "instant" });
                    }
                    button.click();
                }
            } else if (event.key === "F3" || (modifier && event.key.toLowerCase() === "f") || (event.altKey && event.key.toLowerCase() === "q")) {
                event.preventDefault();
                if (event.key === "F3" && search?.value.trim()) search.form.requestSubmit();
                else focusSearch();
            } else if (event.key === "F4") {
                event.preventDefault();
                window.location.href = pzCardList.settingsUrl;
            } else if (modifier && event.key === ";" && target === search) {
                event.preventDefault();
                search.setRangeText(pzCardList.today, search.selectionStart, search.selectionEnd, "end");
                search.dispatchEvent(new Event("input", { bubbles: true }));
            } else if (modifier && event.key.toLowerCase() === "a" && !editing) {
                event.preventDefault();
                const checkbox = root.querySelector("thead .check-column input");
                if (checkbox && !checkbox.checked) checkbox.click();
            } else if (modifier && !editing && ["ArrowLeft", "ArrowRight"].includes(event.key)) {
                const link = root.querySelector(`.tablenav.top a.${event.key === "ArrowLeft" ? "prev-page" : "next-page"}`);
                if (link) { event.preventDefault(); window.location.href = link.href; }
            }
        });
        document.addEventListener("keydown", event => {
            if (event.defaultPrevented || event.isComposing || event.ctrlKey || event.metaKey || event.altKey || event.shiftKey) return;
            const active = document.activeElement;
            const onCheckbox = active?.matches('.pz-card-list input[type="checkbox"]');
            if (onCheckbox && event.key === "ContextMenu") {
                const bulk = document.getElementById("pz-linkcard-bulk");
                if (bulk) { event.preventDefault(); bulk.focus(); try { bulk.showPicker?.(); } catch (error) { /* Focus remains on the selector. */ } }
                return;
            }
            const keys = onCheckbox ? ["ArrowUp", "ArrowDown", "Home", "End", "PageUp", "PageDown"] : ["Home", "End", "PageUp", "PageDown"];
            if (!keys.includes(event.key) || (!onCheckbox && active !== document.body && active !== document.documentElement)) return;
            const checks = [...root.querySelectorAll('.wp-list-table input[type="checkbox"]')].filter(input => !input.disabled && input.getClientRects().length);
            const index = Math.max(0, checks.indexOf(active));
            const next = event.key === "Home" ? checks[0] : event.key === "End" ? checks.at(-1)
                : checks[Math.max(0, Math.min(checks.length - 1, index + ({ ArrowUp: -1, ArrowDown: 1, PageUp: -10, PageDown: onCheckbox ? 10 : 11 })[event.key]))];
            if (next) { event.preventDefault(); next.focus(); }
        });
    });
})(jQuery);
