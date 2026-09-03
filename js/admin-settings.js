document.addEventListener("DOMContentLoaded", () => {

	const dashboard = document.querySelector(".pz-dashboard");
    if (!dashboard) return;

	// 処理中オーバーレイを非表示
    document.querySelector("#pz-overlay-proc")?.classList.remove("pz-overlay-proc-active");
    document.querySelector("#pz-overlay-proc")?.style.setProperty("display", "none");

	// WordPress 標準のカラーピッカー (wpColorPicker) は jQuery 依存なので注意！
    document.querySelectorAll(".pz-wp-color-picker").forEach(el => {
        if (typeof jQuery !== "undefined" && typeof jQuery(el).wpColorPicker === "function") {
            jQuery(el).wpColorPicker();
        }
    });

	// スクロール位置の調整
    const scrollNow = document.querySelector("input[name='scroll-now']");
    const cacheEditor = document.querySelector(".pz-man-cache-editor");
    if (scrollNow && !cacheEditor) window.scrollTo(0, scrollNow.value);

    window.addEventListener("load", () => {
        document.querySelector("#pz-overlay-proc")?.classList.remove("pz-overlay-proc-active");
        document.querySelector("#pz-overlay-proc")?.classList.add("hidden");

        switchEnabled();

        // 一番上に行くボタン
        document.querySelectorAll(".pz-button-top").forEach(btn =>
            btn.addEventListener("click", buttonTopClick)
        );
        window.addEventListener("scroll", topButtonScroll);

        // ショートコードをコピー
        document.querySelectorAll(".pz-shortcode-1").forEach(el =>
            el.addEventListener("keyup", copyShortcode)
        );

        // ショートコードの入力チェック
        ["code1","code2","code3","code4"].forEach(code => {
            const el = document.querySelector(`input[name="properties[${code}]"]`);
            if (el) el.addEventListener("keydown", checkShortcodeKey);
        });

        // すべてのWP-Cronスケジュールを表示
        document.querySelectorAll(".pz-cron-all").forEach(el =>
            el.addEventListener("change", showAllCron)
        );

        // submit時にスクロール位置保存
        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", () => {
                if (scrollNow && !cacheEditor) scrollNow.value = window.scrollY;
                const inhibit = document.querySelector("input[type=checkbox][name='properties[flg-inhibit]']");
                const inhibitValue = document.querySelector("input[name='flg-inhibit']")?.value;
                if (inhibit?.checked || inhibitValue === "1") {
                    const overlay = document.querySelector("#pz-overlay-proc");
                    if (overlay) {
                        overlay.classList.remove("hidden");
                        overlay.classList.remove("pz-overlay-proc-active");
                        overlay.style.display = "block";
                        setTimeout(() => {
                            overlay.classList.add("pz-overlay-proc-active");
                        }, 500);
                    }
                }
            });
        });

        // クリックで全選択
        document.querySelectorAll(".pz-click-all-select").forEach(el =>
            el.addEventListener("click", allSelect)
        );

        document.addEventListener("click", errorModeNoticeDismiss);
        document.addEventListener("click", selectImageFromMedia);
        initCharacterCounts();
        initCachemanSearch();
        initScreenOptions();

        // readonly チェックボックス無効化
        document.querySelectorAll("input[type=checkbox]").forEach(el =>
            el.addEventListener("click", checkboxReadonly)
        );

        // 自動変換チェック
        document.querySelectorAll(".pz-sync-check,.pz-show").forEach(el =>
            el.addEventListener("change", switchEnabled)
        );

        document.querySelector("#pz-overlay-proc")?.classList.remove("pz-overlay-proc-active");
        document.querySelector("#pz-overlay-proc")?.classList.add("hidden");
    });

    // ----------- 関数群 -----------

    // 一番上へ行く
    function buttonTopClick(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: "smooth" });
    }

    // TOPボタンの表示切替
    function topButtonScroll() {
        const btn = document.querySelector(".pz-button-top");
        if (!btn) return;
        if (window.scrollY > 80) {
            btn.style.display = "block";
        } else {
            btn.style.display = "none";
        }
    }

    // 項目の有効化／無効化
    function switchEnabled() {
        const setDisabled = (selector, disabled, readonly=false, color=null) => {
            document.querySelectorAll(selector).forEach(el => {
                el.disabled = disabled;
                el.readOnly = readonly;
                if (color !== null) {
                    el.parentElement.style.color = color;
                    el.style.color = color;
                }
            });
        };

        // カスタムフィールド
        const inGet = document.querySelector("select[name='properties[in-get]']")?.value;
        setDisabled("input[name='properties[in-field-title]']", inGet != "3");
        setDisabled("input[name='properties[in-field-excerpt]']", inGet != "3");

        // サムネイル（外部）
        const exThumb = document.querySelector("select[name='properties[ex-thumbnail]']")?.value;
        setDisabled("select[name='properties[ex-thumbnail-size]']", !(exThumb == "1" || exThumb == "13"));

        // サムネイル（内部）
        const inThumb = document.querySelector("select[name='properties[in-thumbnail]']")?.value;
        setDisabled("select[name='properties[in-thumbnail-size]']", !(inThumb == "1" || inThumb == "13"));

        // ユーザーエージェント
        const flgAgentEl = document.querySelector("input[name='properties[flg-agent]'][type=checkbox]");
		const flgAgent = flgAgentEl ? flgAgentEl.checked : false;
		setDisabled("input[name='properties[user-agent]']", !flgAgent, !flgAgent );

		// 自動変換関連
		const autoAtagEl = document.querySelector("input[name='properties[auto-atag]'][type=checkbox]");
		const autoUrlEl  = document.querySelector("input[name='properties[auto-url]'][type=checkbox]");
		const autoAtag = autoAtagEl ? autoAtagEl.checked : false;
		const autoUrl = autoUrlEl ? autoUrlEl.checked : false;
		const enabled = autoAtag || autoUrl;
		setDisabled("input[name='properties[auto-external]'][type=checkbox]", false, !enabled, enabled ? "#444" : "#ddd");
		setDisabled("input[name='properties[flg-do-shortcode]'][type=checkbox]", false, !enabled, enabled ? "#444" : "#ddd");
		setDisabled("textarea[name='properties[exclude-url]']", false, !enabled, enabled ? "#444" : "#888");
	}

    // ショートコードをコピー
    function copyShortcode(e) {
        const val = e.target.value;
        document.querySelectorAll(".pz-shortcode-copy").forEach(el => {
            el.textContent = val;
        });
        document.querySelectorAll(".pz-shortcode-enabled").forEach(el => {
            el.disabled = val.length === 0;
        });
    }

    // ショートコード入力チェック
    function checkShortcodeKey(e) {
        if (e.key === " ") {
            e.preventDefault();
        }
    }

    // WP-Cron 一覧の表示切替
    function showAllCron(e) {
        document.querySelectorAll(".pz-cron-list-other").forEach(el => {
            if (e.target.checked) {
                el.style.display = "table-row";
                el.classList.add("pz-show");
                el.classList.remove("pz-hide");
            } else {
                el.style.display = "none";
                el.classList.remove("pz-show");
                el.classList.add("pz-hide");
            }
        });
    }

    // カラーピッカーとテキスト同期
    function syncColor(e) {
        const name = e.target.getAttribute("name");
        const value = e.target.value;
        document.querySelectorAll(`input[name="${name}"]`).forEach(el => {
            el.value = value;
        });
    }

    // readonly チェックボックス無効化
    function checkboxReadonly(e) {
        if (e.target.readOnly) {
            e.preventDefault();
        }
    }

    function errorModeNoticeDismiss(e) {
        const notice = e.target.closest(".pz-lkc-error-mode-notice");
        if (!notice || !e.target.closest(".notice-dismiss")) return;

        const checkbox = document.querySelector('input[type=checkbox][name="properties[error-mode]"]');
        if (checkbox) {
            checkbox.checked = false;
            checkbox.dispatchEvent(new Event("change", { bubbles: true }));
        }

        if (!window.pzLinkCardAdmin?.ajaxUrl || !window.pzLinkCardAdmin?.noticeNonce) return;

        const data = new FormData();
        data.append("action", "pz_lkc_clear_error_mode");
        data.append("nonce", window.pzLinkCardAdmin.noticeNonce);

        fetch(window.pzLinkCardAdmin.ajaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).catch(() => {});
    }

    function selectImageFromMedia(e) {
        const button = e.target.closest(".pz-media-select-image");
        if (!button) return;
        e.preventDefault();

        const target = button.dataset.target;
        const input = target ? document.querySelector(`input[name="${target}"]`) : null;
        if (!input || !window.wp?.media) return;

        const frame = wp.media({
            title: window.pzLinkCardAdmin?.mediaTitle || "Select Image",
            button: {
                text: window.pzLinkCardAdmin?.mediaButton || "Use this image"
            },
            library: {
                type: "image"
            },
            multiple: false
        });

        frame.on("select", () => {
            const attachment = frame.state().get("selection").first()?.toJSON();
            if (!attachment?.url) return;
            input.value = attachment.url;
            input.dispatchEvent(new Event("input", { bubbles: true }));
            input.dispatchEvent(new Event("change", { bubbles: true }));
            updateImagePreview(input, attachment.url);
        });

        frame.open();
    }

    function initCharacterCounts() {
        document.querySelectorAll("[data-pz-character-count-for]").forEach(counter => {
            const target = document.getElementById(counter.dataset.pzCharacterCountFor || "");
            if (!target) return;

            const template = counter.dataset.pzCharacterCountTemplate || "%s characters";
            const formatter = new Intl.NumberFormat(document.documentElement.lang || undefined);
            const update = () => {
                counter.textContent = template.replace("%s", formatter.format(Array.from(target.value || "").length));
            };

            update();
            target.addEventListener("input", update);
        });
    }

    function updateImagePreview(input, url) {
        const imageBox = input
            ?.closest(".pz-man-cache-image-box")
            ?.querySelector(".pz-man-cache-image-preview");
        if (!imageBox || !url) return;

        imageBox.classList.remove("pz-man-cache-image-empty");
        imageBox.innerHTML = "";

        const link = document.createElement("a");
        link.href = url;
        link.target = "_blank";
        link.rel = "noopener noreferrer";
        link.className = "pz-man-image-box-trigger";

        const frame = document.createElement("div");
        const img = document.createElement("img");
        img.src = url;
        img.alt = "";
        img.loading = "lazy";

        frame.appendChild(img);
        link.appendChild(frame);
        imageBox.appendChild(link);
    }

    function initScreenOptions() {
        const root = document.querySelector(".pz-man-screen-options");
        const toggle = document.querySelector("#pz-man-screen-options-toggle");
        const panel = document.querySelector("#pz-man-screen-options-panel");
        if (!root || !toggle || !panel) return;

        const columns = {
            id: [".pz-man-head-id", ".pz-man-body-id"],
            excerpt: [".pz-man-head-excerpt", ".pz-man-body-excerpt-cell"],
            charset: [".pz-man-head-charset", ".pz-man-body-charset"],
            domain: [".pz-man-head-domain", ".pz-man-body-domain-cell"],
            sns: [".pz-man-head-sns_twitter", ".pz-man-body-sns"],
            regist_time: [".pz-man-head-regist_time", ".pz-man-body-resist-time"],
            update_time: [".pz-man-head-update_time", ".pz-man-body-update-time"],
            sns_time: [".pz-man-head-sns_time", ".pz-man-body-sns-time"],
            alive_time: [".pz-man-head-alive_time", ".pz-man-body-alive-time"],
            post_id: [".pz-man-head-use_post_id1", ".pz-man-body-post-id"],
            click_count: [".pz-man-head-click_count", ".pz-man-body-click-count"],
            result: [".pz-man-head-update_result", ".pz-man-body-result"],
        };

        const state = {};

        const setPanelOpen = open => {
            panel.hidden = !open;
            toggle.setAttribute("aria-expanded", open ? "true" : "false");
            const icon = toggle.querySelector(".dashicons");
            if (icon) {
                icon.classList.toggle("dashicons-arrow-down-alt2", !open);
                icon.classList.toggle("dashicons-arrow-up-alt2", open);
            }
        };

        const applyColumn = (column, visible) => {
            (columns[column] || []).forEach(selector => {
                document.querySelectorAll(selector).forEach(el => {
                    el.classList.toggle("pz-man-column-hidden", !visible);
                });
            });
        };

        const saveState = (perPage = null) => {
            if (!window.pzLinkCardAdmin?.ajaxUrl || !window.pzLinkCardAdmin?.cachemanColumnsNonce) {
                return Promise.resolve();
            }

            const body = new URLSearchParams();
            body.set("action", "pz_lkc_save_cacheman_columns");
            body.set("nonce", window.pzLinkCardAdmin.cachemanColumnsNonce);
            Object.entries(state).forEach(([column, visible]) => {
                body.set(`columns[${column}]`, visible ? "1" : "0");
            });
            if (perPage !== null) body.set("per_page", perPage);

            return fetch(window.pzLinkCardAdmin.ajaxUrl, {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                },
                body: body.toString(),
            }).catch(() => {});
        };

        panel.querySelectorAll(".pz-man-screen-column-toggle").forEach(checkbox => {
            const column = checkbox.dataset.pzManColumn;
            state[column] = checkbox.checked;
            applyColumn(column, checkbox.checked);
            checkbox.addEventListener("change", () => {
                state[column] = checkbox.checked;
                applyColumn(column, checkbox.checked);
                saveState();
            });
        });

        const perPageSelect = document.querySelector("#pz-man-screen-option-per-page");
        if (perPageSelect) {
            perPageSelect.addEventListener("change", () => {
                saveState(perPageSelect.value).finally(() => {
                    const form = perPageSelect.closest("form");
                    const pageNow = form?.querySelector('input[name="page_now"]');
                    if (pageNow) pageNow.value = "1";
                    if (form?.requestSubmit) {
                        form.requestSubmit();
                    } else {
                        form?.submit();
                    }
                });
            });
        }

        toggle.addEventListener("click", e => {
            e.preventDefault();
            setPanelOpen(panel.hidden);
        });

        document.addEventListener("click", e => {
            if (panel.hidden || root.contains(e.target)) return;
            setPanelOpen(false);
        });

        document.addEventListener("keydown", e => {
            if (e.key !== "Escape" || panel.hidden) return;
            setPanelOpen(false);
            toggle.focus();
        });
    }

    function initCachemanSearch() {
        const input = document.querySelector("#post-search-input");
        const searchSubmit = document.querySelector("#search-submit");
        if (!input || !searchSubmit) return;

        const runIdSearch = id => {
            if (!id) return;

            input.value = `ID:${id}`;
            input.dispatchEvent(new Event("input", { bubbles: true }));
            input.dispatchEvent(new Event("change", { bubbles: true }));

            const pageNow = input.form?.querySelector('input[name="page_now"]');
            if (pageNow) pageNow.value = "1";

            searchSubmit.click();
        };

        document.addEventListener("click", e => {
            const button = e.target?.closest?.(".pz-man-id-search");
            if (!button) return;

            e.preventDefault();
            runIdSearch(button.dataset.pzManSearchId);
        });

        document.addEventListener("click", e => {
            const button = e.target?.closest?.(".pz-filter-item");
            if (!button) return;

            input.value = "";
            input.dispatchEvent(new Event("input", { bubbles: true }));
            input.dispatchEvent(new Event("change", { bubbles: true }));
        });
    }

    // 全選択
    function allSelect(e) {
        const el = e.target;
        if (el.tagName === "INPUT") {
            el.select();
        } else if (el.tagName === "DIV") {
            const range = document.createRange();
            range.selectNodeContents(el);
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        }
    }
});
