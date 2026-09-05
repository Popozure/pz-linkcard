document.addEventListener("DOMContentLoaded", () => {

	const dashboard = document.querySelector(".pz-dashboard");
    if (!dashboard) return;

	// 蜃ｦ逅・ｸｭ繧ｪ繝ｼ繝舌・繝ｬ繧､繧帝撼陦ｨ遉ｺ
    document.querySelector("#pz-overlay-proc")?.classList.remove("pz-overlay-proc-active");
    document.querySelector("#pz-overlay-proc")?.style.setProperty("display", "none");

	// 繧ｹ繧ｯ繝ｭ繝ｼ繝ｫ菴咲ｽｮ縺ｮ隱ｿ謨ｴ
    const scrollNow = document.querySelector("input[name='scroll-now']");
    const cacheEditor = document.querySelector(".pz-man-cache-editor");
    if (scrollNow && !cacheEditor) window.scrollTo(0, scrollNow.value);

    window.addEventListener("load", () => {
        document.querySelector("#pz-overlay-proc")?.classList.remove("pz-overlay-proc-active");
        document.querySelector("#pz-overlay-proc")?.classList.add("hidden");

        switchEnabled();

        // 荳逡ｪ荳翫↓陦後￥繝懊ち繝ｳ
        document.querySelectorAll(".pz-button-top").forEach(btn =>
            btn.addEventListener("click", buttonTopClick)
        );
        window.addEventListener("scroll", topButtonScroll);
        topButtonScroll();

        // 繧ｷ繝ｧ繝ｼ繝医さ繝ｼ繝峨ｒ繧ｳ繝斐・
        document.querySelectorAll(".pz-shortcode-1").forEach(el =>
            el.addEventListener("keyup", copyShortcode)
        );

        // 繧ｷ繝ｧ繝ｼ繝医さ繝ｼ繝峨・蜈･蜉帙メ繧ｧ繝・け
        ["code1","code2","code3","code4"].forEach(code => {
            const el = document.querySelector(`input[name="properties[${code}]"]`);
            if (el) el.addEventListener("keydown", checkShortcodeKey);
        });

        // 縺吶∋縺ｦ縺ｮWP-Cron繧ｹ繧ｱ繧ｸ繝･繝ｼ繝ｫ繧定｡ｨ遉ｺ
        document.querySelectorAll(".pz-cron-all").forEach(el =>
            el.addEventListener("change", showAllCron)
        );

        // Admin setting handler
        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", e => {
                const submitter = e.submitter;
                if (submitter?.classList.contains("pz-man-cache-reload-button")) {
                    submitter.classList.add("is-spinning");
                }

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

        // Admin setting handler
        document.querySelectorAll(".pz-click-all-select").forEach(el =>
            el.addEventListener("click", allSelect)
        );

        document.addEventListener("click", errorModeNoticeDismiss);
        document.addEventListener("click", selectImageFromMedia);
        initCharacterCounts();
        initUnsavedFormWarnings();
        initSettingsTabs();
        initCachemanSearch();
        initScreenOptions();
        // readonly checkbox guard
        document.querySelectorAll("input[type=checkbox]").forEach(el =>
            el.addEventListener("click", checkboxReadonly)
        );
        document.querySelectorAll("input[type=checkbox][data-pz-locked-checked='1']").forEach(el => {
            el.checked = true;
            el.addEventListener("change", keepCheckboxChecked);
        });
        document.querySelectorAll("input[type=checkbox][data-pz-locked-checkbox='1']").forEach(el => {
            el.dataset.pzLockedState = el.checked ? "1" : "0";
            el.addEventListener("change", restoreLockedCheckbox);
        });

        document.querySelectorAll(".pz-card-range").forEach(el =>
            el.addEventListener("input", syncCardRange)
        );
        document.querySelectorAll(".pz-card-range").forEach(el =>
            el.addEventListener("keydown", resetCardRange)
        );
        document.querySelectorAll(".pz-card-prop-number input[type=number]").forEach(el =>
            el.addEventListener("input", syncCardNumber)
        );
        updateCardRangeFills();

        // Auto switch checks
        document.querySelectorAll(".pz-sync-check,.pz-show,input[name='properties[centering]'],select[name='properties[thumbnail-position]'],select[name='properties[info-position]']").forEach(el =>
            el.addEventListener("change", switchEnabled)
        );

        document.querySelector("#pz-overlay-proc")?.classList.remove("pz-overlay-proc-active");
        document.querySelector("#pz-overlay-proc")?.classList.add("hidden");
    });

    // ----------- 髢｢謨ｰ鄒､ -----------

    // 荳逡ｪ荳翫∈陦後￥
    function buttonTopClick(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: "smooth" });
    }

    // TOP繝懊ち繝ｳ縺ｮ陦ｨ遉ｺ蛻・崛
    function topButtonScroll() {
        const indicator = document.querySelector(".pz-indicator");
        if (!indicator) return;
        indicator.classList.toggle("pz-indicator-active", window.scrollY > 80);
    }

    // Admin setting helper
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

        // Admin setting value
        const inGet = document.querySelector("select[name='properties[in-get]']")?.value;
        setDisabled("input[name='properties[in-field-title]']", inGet != "3");
        setDisabled("input[name='properties[in-field-excerpt]']", inGet != "3");

        // Admin setting value
        const exThumb = document.querySelector("select[name='properties[ex-thumbnail]']")?.value;
        setDisabled("select[name='properties[ex-thumbnail-size]']", !(exThumb == "1" || exThumb == "13"));

        // Admin setting value
        const inThumb = document.querySelector("select[name='properties[in-thumbnail]']")?.value;
        setDisabled("select[name='properties[in-thumbnail-size]']", !(inThumb == "1" || inThumb == "13"));

        // Admin setting value
        const flgAgentEl = document.querySelector("input[name='properties[flg-agent]'][type=checkbox]");
		const flgAgent = flgAgentEl ? flgAgentEl.checked : false;
		setDisabled("input[name='properties[user-agent]']", !flgAgent, !flgAgent );

		// 閾ｪ蜍募､画鋤髢｢騾｣
		const autoAtagEl = document.querySelector("input[name='properties[auto-atag]'][type=checkbox]");
		const autoUrlEl  = document.querySelector("input[name='properties[auto-url]'][type=checkbox]");
		const autoAtag = autoAtagEl ? autoAtagEl.checked : false;
		const autoUrl = autoUrlEl ? autoUrlEl.checked : false;
		const enabled = autoAtag || autoUrl;
		setDisabled("input[name='properties[auto-external]'][type=checkbox]", false, !enabled, enabled ? "#444" : "#ddd");
		setDisabled("input[name='properties[flg-do-shortcode]'][type=checkbox]", false, !enabled, enabled ? "#444" : "#ddd");
		setDisabled("textarea[name='properties[exclude-url]']", false, !enabled, enabled ? "#444" : "#888");

		// Admin setting value
		const centeringEl = document.querySelector("input[name='properties[centering]'][type=checkbox]");
		const centering = centeringEl ? centeringEl.checked : false;
		setDisabled("select[name='properties[margin-left]']", centering);
		setDisabled("select[name='properties[margin-right]']", centering);

		// Admin setting value
		const thumbnailPositionEl = document.querySelector("select[name='properties[thumbnail-position]']");
		const thumbnailDisabled = thumbnailPositionEl ? thumbnailPositionEl.value === "0" : false;
		setDisabled("input[name='properties[thumbnail-width]']", thumbnailDisabled);
		setDisabled("input[name='properties[thumbnail-height]']", thumbnailDisabled);

		const infoPositionEl = document.querySelector("select[name='properties[info-position]']");
		const siteNameReadonly = infoPositionEl ? infoPositionEl.value === "" : false;
		setDisabled("input[name='properties[use-sitename]'][type=checkbox]", false, siteNameReadonly, siteNameReadonly ? "#ddd" : "#444");
	}

    // 繧ｷ繝ｧ繝ｼ繝医さ繝ｼ繝峨ｒ繧ｳ繝斐・
    function copyShortcode(e) {
        const val = e.target.value;
        document.querySelectorAll(".pz-shortcode-copy").forEach(el => {
            el.textContent = val;
        });
        document.querySelectorAll(".pz-shortcode-enabled").forEach(el => {
            el.disabled = val.length === 0;
        });
    }

    // 繧ｷ繝ｧ繝ｼ繝医さ繝ｼ繝牙・蜉帙メ繧ｧ繝・け
    function checkShortcodeKey(e) {
        if (e.key === " ") {
            e.preventDefault();
        }
    }

    // WP-Cron 荳隕ｧ縺ｮ陦ｨ遉ｺ蛻・崛
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

    // Admin setting helper
    function syncColor(e) {
        const name = e.target.getAttribute("name");
        const value = e.target.value;
        document.querySelectorAll(`input[name="${name}"]`).forEach(el => {
            el.value = value;
        });
    }

    function syncCardRange(e) {
        const targetName = e.target.dataset.target;
        const target = targetName ? document.querySelector(`input[name="${targetName}"]`) : null;
        if (target) target.value = e.target.value;
        updateCardRangeFill(e.target);
    }

    function syncCardNumber(e) {
        const name = e.target.getAttribute("name");
        const range = name ? document.querySelector(`.pz-card-range[data-target="${name}"]`) : null;
        if (range) {
            range.value = e.target.value;
            updateCardRangeFill(range);
        }
    }

    function resetCardRange(e) {
        if (e.key !== "Escape") return;

        const range = e.target;
        const min = Number(range.min || 0);
        const resetValue = range.hasAttribute("data-center") ? Number(range.dataset.center) : (min < 0 ? 0 : min);
        e.preventDefault();
        range.value = resetValue;
        range.dispatchEvent(new Event("input", { bubbles: true }));
    }

    function updateCardRangeFills() {
        document.querySelectorAll(".pz-card-range").forEach(updateCardRangeFill);
    }

    function updateCardRangeFill(range) {
        const min = Number(range.min || 0);
        const max = Number(range.max || 100);
        const value = Number(range.value || 0);
        if (max <= min) return;

        const pct = Math.min(100, Math.max(0, ((value - min) / (max - min)) * 100));
        if (range.hasAttribute("data-center") || min < 0) {
            const centerValue = range.hasAttribute("data-center") ? Number(range.dataset.center) : 0;
            const center = Math.min(100, Math.max(0, ((centerValue - min) / (max - min)) * 100));
            const start = Math.min(center, pct);
            const end = Math.max(center, pct);
            const fill = value < centerValue ? "#d64b4b" : "#0073aa";
            range.style.setProperty("--pz-range-bg", `linear-gradient(to right, #d7d7d7 0%, #d7d7d7 ${start}%, ${fill} ${start}%, ${fill} ${end}%, #d7d7d7 ${end}%, #d7d7d7 100%)`);
            return;
        }
        range.style.setProperty("--pz-range-bg", `linear-gradient(to right, #0073aa 0%, #0073aa ${pct}%, #d7d7d7 ${pct}%, #d7d7d7 100%)`);
    }

    // Admin setting helper
    function checkboxReadonly(e) {
        if (e.target.dataset.pzLockedChecked === "1") {
            e.preventDefault();
            e.target.checked = true;
            return;
        }
        if (e.target.dataset.pzLockedCheckbox === "1") {
            e.preventDefault();
            restoreLockedCheckbox(e);
            return;
        }
        if (e.target.readOnly) {
            e.preventDefault();
        }
    }

    function keepCheckboxChecked(e) {
        e.target.checked = true;
    }

    function restoreLockedCheckbox(e) {
        e.target.checked = e.target.dataset.pzLockedState === "1";
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

    function serializeFormValues(form) {
        const params = new URLSearchParams();
        Array.from(form.elements).forEach(el => {
            if (!el.name || el.disabled) return;
            if (["button", "submit", "reset"].includes(el.type)) return;
            if (["scroll-now", "tab-now"].includes(el.name)) return;
            if ((el.type === "checkbox" || el.type === "radio") && !el.checked) return;
            if (el.type === "file") return;

            if (el.tagName === "SELECT" && el.multiple) {
                Array.from(el.selectedOptions).forEach(option => params.append(el.name, option.value));
                return;
            }

            params.append(el.name, el.value);
        });
        return params.toString();
    }

    function initUnsavedFormWarning(form, options = {}) {
        if (!form) return;
        if (form.dataset.pzUnsavedWarningInitialized) return;
        form.dataset.pzUnsavedWarningInitialized = "1";

        const confirmMessage = window.pzLinkCardAdmin?.discardChanges || "Discard changes?";
        const confirmNonUpdateSubmit = options.confirmNonUpdateSubmit || false;
        let isSubmitting = false;
        let initialState = serializeFormValues(form);
        let hasUnsavedChanges = false;

        const updateUnsavedChanges = () => {
            hasUnsavedChanges = serializeFormValues(form) !== initialState;
        };

        form.addEventListener("input", updateUnsavedChanges);
        form.addEventListener("change", updateUnsavedChanges);
        form.addEventListener("reset", () => {
            window.setTimeout(updateUnsavedChanges, 0);
        });
        form.addEventListener("submit", e => {
            const submitter = e.submitter;
            const isUpdate = submitter?.name === "action" && submitter?.value === "update";
            if (confirmNonUpdateSubmit && !isUpdate && hasUnsavedChanges && !window.confirm(confirmMessage)) {
                e.preventDefault();
                e.stopPropagation();
                return;
            }

            isSubmitting = true;
            initialState = serializeFormValues(form);
            hasUnsavedChanges = false;
        }, true);

        window.addEventListener("pageshow", () => {
            isSubmitting = false;
        });

        window.addEventListener("beforeunload", e => {
            if (isSubmitting || !hasUnsavedChanges) return;

            e.preventDefault();
            e.returnValue = confirmMessage;
            return confirmMessage;
        });
    }

    function initUnsavedFormWarnings() {
        initUnsavedFormWarning(document.querySelector(".pz-man-cache-dirty-check")?.closest("form"), {
            confirmNonUpdateSubmit: true,
        });
        initUnsavedFormWarning(document.querySelector(".pz-settings form"));
    }

    function initSettingsTabs() {
        const wrapper = document.querySelector("#pz-tabbar-wrapper");
        const tabbar = document.querySelector("#pz-tabbar");
        if (!wrapper || !tabbar) return;

        const leftBtn = wrapper.querySelector(".pz-tab-left");
        const rightBtn = wrapper.querySelector(".pz-tab-right");
        const tabNameEl = document.querySelector(".pz-tab-name");
        const tabNow = document.querySelector('input[name="tab-now"]');
        const dashboard = wrapper.closest(".pz-dashboard");
        const submitFloat = dashboard?.querySelector(".pz-submit-float");
        const tabbarSpacer = document.createElement("div");
        let lastWheelAt = 0;
        let submitGap = null;

        tabbarSpacer.className = "pz-tabbar-spacer";
        tabbarSpacer.style.height = "0";
        wrapper.parentNode.insertBefore(tabbarSpacer, wrapper);

        const getFixedTop = () => {
            const adminBar = document.querySelector("#wpadminbar");
            const adminBarBottom = adminBar ? Math.max(0, adminBar.getBoundingClientRect().bottom) : 0;
            const viewportTop = window.visualViewport ? Math.max(0, window.visualViewport.offsetTop) : 0;
            return Math.max(adminBarBottom, viewportTop);
        };

        const measureSubmitGap = () => {
            const tabRect = wrapper.getBoundingClientRect();
            const submitRect = submitFloat?.getBoundingClientRect();
            if (submitRect && !wrapper.classList.contains("pz-tabbar-fixed")) {
                submitGap = Math.max(0, Math.round(submitRect.top - tabRect.bottom));
            }
        };

        const syncFixedTabbar = () => {
            const fixedTop = getFixedTop();
            const shouldFix = tabbarSpacer.getBoundingClientRect().top <= fixedTop;

            if (shouldFix) {
                const spacerRect = tabbarSpacer.getBoundingClientRect();
                const fixedLeft = Math.max(0, spacerRect.left);
                const fixedWidth = Math.min(spacerRect.width, document.documentElement.clientWidth - fixedLeft);
                tabbarSpacer.style.height = `${wrapper.offsetHeight}px`;
                wrapper.classList.add("pz-tabbar-fixed");
                wrapper.style.top = `${fixedTop}px`;
                wrapper.style.setProperty("--pz-tabbar-fixed-top", `${fixedTop}px`);
                wrapper.style.left = `${fixedLeft}px`;
                wrapper.style.width = `${fixedWidth}px`;
                if (submitFloat) {
                    if (submitGap === null) submitGap = 12;
                    submitFloat.style.setProperty("--pz-submit-sticky-top", `${fixedTop + wrapper.offsetHeight + submitGap}px`);
                }
            } else {
                measureSubmitGap();
                wrapper.classList.remove("pz-tabbar-fixed");
                wrapper.style.top = "";
                wrapper.style.removeProperty("--pz-tabbar-fixed-top");
                wrapper.style.left = "";
                wrapper.style.width = "";
                tabbarSpacer.style.height = "0";
                submitFloat?.style.removeProperty("--pz-submit-sticky-top");
            }

            updateButtons();
        };

        const getTabName = tab => tab?.getAttribute("name") || tab?.hash?.replace("#", "") || "";
        const isVisibleTab = tab => {
            const style = window.getComputedStyle(tab);
            return style.display !== "none" && style.visibility !== "hidden" && tab.getClientRects().length > 0;
        };
        const getTabs = () => Array.from(tabbar.querySelectorAll(".pz-tab")).filter(isVisibleTab);

        const updateButtons = () => {
            const overflow = tabbar.scrollWidth > tabbar.clientWidth + 1;
            wrapper.classList.toggle("pz-tabbar-overflow", overflow);

            if (!leftBtn || !rightBtn) return;
            leftBtn.style.display = overflow && tabbar.scrollLeft > 0 ? "flex" : "none";
            rightBtn.style.display = overflow && tabbar.scrollLeft + tabbar.clientWidth < tabbar.scrollWidth - 1 ? "flex" : "none";
        };

        const adjustTabVisibility = tab => {
            if (!tab) return;

            const tabRect = tab.getBoundingClientRect();
            const barRect = tabbar.getBoundingClientRect();
            const margin = 24;

            if (tabRect.left < barRect.left) {
                tabbar.scrollBy({ left: tabRect.left - barRect.left - margin, behavior: "smooth" });
            } else if (tabRect.right > barRect.right) {
                tabbar.scrollBy({ left: tabRect.right - barRect.right + margin, behavior: "smooth" });
            }
        };

        const openTab = (tab, focusTab = false) => {
            const tabName = getTabName(tab);
            if (!tabName) return;

            getTabs().forEach(item => item.classList.remove("pz-tab-active"));
            document.querySelectorAll(".pz-page").forEach(page => page.classList.remove("pz-page-active"));

            tab.classList.add("pz-tab-active");
            document.getElementById(tabName)?.classList.add("pz-page-active");
            if (tabNameEl) tabNameEl.textContent = tab.textContent;
            if (tabNow) tabNow.value = tabName;

            adjustTabVisibility(tab);
            updateButtons();
            if (focusTab) tab.focus();
        };

        const getCurrentIndex = (tabs, currentTab = null) => {
            const currentName = getTabName(currentTab) || tabNow?.value || getTabName(tabbar.querySelector(".pz-tab-active"));
            const currentIndex = tabs.findIndex(tab => getTabName(tab) === currentName);
            return currentIndex >= 0 ? currentIndex : tabs.findIndex(tab => tab.classList.contains("pz-tab-active"));
        };

        const moveTab = (direction, focusTab = false, currentTab = null) => {
            const tabs = getTabs();
            if (!tabs.length) return;

            const currentIndex = getCurrentIndex(tabs, currentTab);
            const baseIndex = currentIndex >= 0 ? currentIndex : 0;
            const nextIndex = (baseIndex + direction + tabs.length) % tabs.length;
            openTab(tabs[nextIndex], focusTab);
        };

        tabbar.addEventListener("click", e => {
            const tab = e.target.closest(".pz-tab");
            if (!tab || !tabbar.contains(tab)) return;

            e.preventDefault();
            openTab(tab);
        });

        tabbar.addEventListener("keydown", e => {
            const tab = e.target.closest(".pz-tab");
            if (!tab || !tabbar.contains(tab)) return;
            if (e.key !== "ArrowLeft" && e.key !== "ArrowRight") return;

            e.preventDefault();
            e.stopPropagation();
            moveTab(e.key === "ArrowRight" ? 1 : -1, true, tab);
        });

        tabbar.addEventListener("wheel", e => {
            if (!e.shiftKey) return;

            const delta = Math.abs(e.deltaX) > Math.abs(e.deltaY) ? e.deltaX : e.deltaY;
            if (delta === 0) return;

            e.preventDefault();
            const now = Date.now();
            if (now - lastWheelAt < 120) return;
            lastWheelAt = now;
            moveTab(delta > 0 ? 1 : -1, true);
        }, { passive: false });

        leftBtn?.addEventListener("click", () => {
            tabbar.scrollBy({ left: -Math.round(tabbar.clientWidth * 0.75), behavior: "smooth" });
        });
        rightBtn?.addEventListener("click", () => {
            tabbar.scrollBy({ left: Math.round(tabbar.clientWidth * 0.75), behavior: "smooth" });
        });

        tabbar.addEventListener("scroll", updateButtons);
        window.addEventListener("scroll", syncFixedTabbar);
        window.addEventListener("resize", syncFixedTabbar);
        window.visualViewport?.addEventListener("scroll", syncFixedTabbar);
        window.visualViewport?.addEventListener("resize", syncFixedTabbar);
        if (window.ResizeObserver) {
            new ResizeObserver(syncFixedTabbar).observe(tabbar);
            new ResizeObserver(syncFixedTabbar).observe(wrapper);
        }

        const activeTab = tabbar.querySelector(".pz-tab-active") || getTabs()[0];
        if (tabNameEl && activeTab) tabNameEl.textContent = activeTab.textContent;
        adjustTabVisibility(activeTab);
        measureSubmitGap();
        syncFixedTabbar();
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

    // Admin setting helper
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
