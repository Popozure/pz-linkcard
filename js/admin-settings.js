document.addEventListener("DOMContentLoaded", () => {

	const dashboard = document.querySelector(".pz-dashboard");
    if (!dashboard) return;
    let processingOverlayTimer = null;
	initInfobarPosition();

	// 処理中オーバーレイを非表示
    document.querySelector("#pz-overlay-proc")?.classList.remove("pz-overlay-proc-active");
    document.querySelector("#pz-overlay-proc")?.style.setProperty("display", "none");
    initSettingsToasts();

    window.addEventListener("load", () => {
        document.querySelector("#pz-overlay-proc")?.classList.remove("pz-overlay-proc-active");
        document.querySelector("#pz-overlay-proc")?.classList.add("hidden");

        switchEnabled();

        // ページ上部へ戻るボタン
        document.querySelectorAll(".pz-button-top").forEach(btn =>
            btn.addEventListener("click", buttonTopClick)
        );
        window.addEventListener("scroll", topButtonScroll);
        topButtonScroll();

        // ショートコード名をプレビューへ反映
        document.querySelectorAll(".pz-shortcode-1").forEach(el =>
            el.addEventListener("keyup", copyShortcode)
        );

        // ショートコード名の入力チェック
        ["code1","code2","code3","code4"].forEach(code => {
            const el = document.querySelector(`input[name="properties[${code}]"]`);
            if (el) el.addEventListener("keydown", checkShortcodeKey);
        });

        const widthInput = document.querySelector('input[name="properties[width]"]');
        if (widthInput) widthInput.addEventListener("keydown", changeWidthUnitKey);

        // すべてのWP-Cronスケジュールを表示
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

                if (submitter?.dataset?.noOverlay === "1") return;
                showProcessingOverlay();
            });
        });

        // Admin setting handler
        document.querySelectorAll(".pz-click-all-select").forEach(el =>
            el.addEventListener("click", allSelect)
        );

        document.addEventListener("click", errorModeNoticeDismiss);
        document.addEventListener("click", selectImageFromMedia);
        document.addEventListener("click", clearCachemanImage);
        initCharacterCounts();
        initUnsavedFormWarnings();
        initSettingsSaveShortcut();
        initSettingsScrollMemory();
        initCachemanEditorShortcuts();
        initSettingsTabs();
        initCachemanSearch();
        initCachemanPaginationKeys();
        initImageBox();
        initScreenOptions();
        initFileImport();
        initSettingsSectionJump();
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
        initCardRangeDragCancel();
        document.querySelectorAll(".pz-card-prop-number input[type=number]").forEach(el =>
            el.addEventListener("input", syncCardNumber)
        );
        document.querySelectorAll(".pz-copy-card-to-hover").forEach(el =>
            el.addEventListener("click", copyCardSettingsToHover)
        );
        initCardEnabledSwitches();
        updateCardRangeFills();

        // Auto switch checks
        document.querySelectorAll(".pz-sync-check,.pz-show,input[name='properties[centering]'],select[name='properties[thumbnail-position]'],select[name='properties[info-position]']").forEach(el =>
            el.addEventListener("change", switchEnabled)
        );

        document.querySelector("#pz-overlay-proc")?.classList.remove("pz-overlay-proc-active");
        document.querySelector("#pz-overlay-proc")?.classList.add("hidden");
    });

    // ----------- 関数群 -----------

	function initInfobarPosition() {
		const infobar = document.querySelector("#pz-infobar");
		if (!infobar) return;

		const adminBar = document.querySelector("#wpadminbar");
		let frame = null;
		const updatePosition = () => {
			frame = null;
			const adminBarBottom = adminBar ? adminBar.getBoundingClientRect().bottom : 0;
			infobar.style.top = `${Math.max(0, Math.round(adminBarBottom))}px`;
		};
		const scheduleUpdate = () => {
			if (frame !== null) return;
			frame = window.requestAnimationFrame(updatePosition);
		};

		updatePosition();
		window.addEventListener("resize", scheduleUpdate);
		window.addEventListener("scroll", scheduleUpdate, { passive: true });
		if (adminBar && "ResizeObserver" in window) {
			new ResizeObserver(scheduleUpdate).observe(adminBar);
		}
	}

    function initFileImport() {
        const fileInput = document.querySelector("#import_file");
        const importButton = document.querySelector("#import_button");
        if (!fileInput || !importButton) return;

        const updateImportButton = () => {
            importButton.disabled = !fileInput.files?.length;
        };
        fileInput.addEventListener("change", updateImportButton);
        updateImportButton();
    }

    function initSettingsSectionJump() {
        const pages = document.querySelectorAll(".pz-page");
        const jumpButtonPages = document.querySelectorAll("#pz-external, #pz-internal");
        if (!pages.length) return;

        const fixedTop = () => {
            const edges = ["#wpadminbar", "#pz-infobar", "#pz-tabbar-wrapper"]
                .map(selector => document.querySelector(selector))
                .filter(Boolean)
                .map(el => Math.max(0, el.getBoundingClientRect().bottom));
            return Math.max(0, ...edges) + 4;
        };

        const scrollToHeading = heading => {
            window.scrollTo({
                top: Math.max(0, window.scrollY + heading.getBoundingClientRect().top - fixedTop()),
                behavior: "smooth",
            });
        };

        const activeHeadings = () => {
            const page = Array.from(pages).find(item => item.classList.contains("pz-page-active"));
            return page ? Array.from(page.querySelectorAll("h2, h3")) : [];
        };

        const keyboardJump = direction => {
            const headings = activeHeadings();
            if (!headings.length) return;

            const marker = fixedTop() + 4;
            const currentIndex = headings.reduce((closest, heading, index) => {
                const distance = Math.abs(heading.getBoundingClientRect().top - marker);
                return distance < closest.distance ? { index, distance } : closest;
            }, { index: 0, distance: Infinity }).index;
            const target = headings[currentIndex + direction];
            if (target) scrollToHeading(target);
        };

        jumpButtonPages.forEach(page => {
            const headings = Array.from(page.querySelectorAll("h3"));
            headings.forEach((heading, index) => {
                if (heading.querySelector(".pz-section-jump")) return;

                const jump = document.createElement("span");
                jump.className = "pz-section-jump";

                [
                    { label: "🔺", title: "前の見出しへ", target: headings[index - 1], disabled: index === 0 },
                    { label: "🔻", title: "次の見出しへ", target: headings[index + 1], disabled: index === headings.length - 1 },
                ].forEach(item => {
                    const button = document.createElement("button");
                    button.type = "button";
                    button.className = "pz-section-jump-button";
                    button.textContent = item.label;
                    button.title = item.title;
                    button.setAttribute("aria-label", item.title);
                    button.disabled = item.disabled;
                    button.addEventListener("click", event => {
                        event.preventDefault();
                        event.stopPropagation();
                        if (item.target) scrollToHeading(item.target);
                    });
                    jump.appendChild(button);
                });

                heading.appendChild(jump);
            });
        });

        document.addEventListener("keydown", event => {
            if (!event.ctrlKey || event.altKey || event.metaKey || event.shiftKey || event.isComposing) return;
            if (event.key !== "ArrowDown" && event.key !== "ArrowUp") return;

            event.preventDefault();
            keyboardJump(event.key === "ArrowDown" ? 1 : -1);
        });
    }

    // ページ上部へ戻る
    function buttonTopClick(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: "smooth" });
    }

    // TOPボタンの表示切り替え
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
        const inGet = document.querySelector("select[name='properties[in-get-from]']")?.value;
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

		// 自動変換関連
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
		const siteNameDisabled = infoPositionEl ? infoPositionEl.value === "" : false;
		setDisabled("input[name='properties[flg-use-sitename]'][type=checkbox]", siteNameDisabled, false, siteNameDisabled ? "#ddd" : "#444");
	}

    // ショートコード名をコピー
    function copyShortcode(e) {
        const val = e.target.value;
        document.querySelectorAll(".pz-shortcode-copy").forEach(el => {
            el.textContent = val;
        });
        document.querySelectorAll(".pz-shortcode-enabled").forEach(el => {
            el.disabled = val.length === 0;
        });
    }

    // ショートコード名の入力チェック
    function checkShortcodeKey(e) {
        if (e.key === " ") {
            e.preventDefault();
        }
    }

    function showProcessingOverlay(delay = 500) {
        const overlay = document.querySelector("#pz-overlay-proc");
        if (!overlay) return;

        if (processingOverlayTimer) {
            clearTimeout(processingOverlayTimer);
            processingOverlayTimer = null;
        }
        processingOverlayTimer = setTimeout(() => {
            processingOverlayTimer = null;
            overlay.classList.remove("hidden");
            overlay.classList.remove("pz-overlay-proc-active");
            overlay.style.display = "flex";
            overlay.classList.add("pz-overlay-proc-active");
        }, delay);
    }

    function changeWidthUnitKey(e) {
        let unit = null;
        if (e.key === "p" || e.key === "P") {
            unit = "px";
        } else if (e.key === "%") {
            unit = "%";
        }
        if (unit === null) return;

        const unitSelect = document.querySelector('select[name="properties[width-unit]"]');
        if (!unitSelect) return;

        e.preventDefault();
        unitSelect.value = unit;
        unitSelect.dispatchEvent(new Event("input", { bubbles: true }));
        unitSelect.dispatchEvent(new Event("change", { bubbles: true }));
    }

    // WP-Cron一覧の表示切り替え
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

    function initCardRangeDragCancel() {
        document.querySelectorAll(".pz-card-range").forEach(range => {
            let dragStartValue = null;
            let activePointerId = null;

            const stopNativeDrag = () => {
                const wasDisabled = range.disabled;
                range.disabled = true;
                range.blur();
                window.requestAnimationFrame(() => {
                    range.disabled = wasDisabled;
                });
            };

            const cancelDrag = e => {
                if (dragStartValue === null) return;
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                range.value = dragStartValue;
                range.dispatchEvent(new Event("input", { bubbles: true }));
                range.dispatchEvent(new Event("change", { bubbles: true }));
                if (activePointerId !== null) {
                    try {
                        range.releasePointerCapture?.(activePointerId);
                    } catch (err) {
                        // Ignore if the browser already released pointer capture.
                    }
                }
                stopNativeDrag();
                dragStartValue = null;
                activePointerId = null;
            };

            range.addEventListener("pointerdown", e => {
                if (e.button === 0) {
                    dragStartValue = range.value;
                    activePointerId = e.pointerId;
                    range.setPointerCapture?.(e.pointerId);
                    return;
                }
                if (e.button === 2) {
                    cancelDrag(e);
                }
            });
            range.addEventListener("mousedown", e => {
                if (e.button === 2 && dragStartValue !== null) {
                    cancelDrag(e);
                }
            });
            range.addEventListener("pointerup", e => {
                if (activePointerId !== null && e.pointerId === activePointerId) {
                    dragStartValue = null;
                    activePointerId = null;
                }
            });
            range.addEventListener("pointercancel", () => {
                dragStartValue = null;
                activePointerId = null;
            });
            range.addEventListener("contextmenu", e => {
                if (dragStartValue !== null) {
                    cancelDrag(e);
                }
            });
        });
    }

    function updateCardRangeFills() {
        document.querySelectorAll(".pz-card-range").forEach(updateCardRangeFill);
    }

    function initCardEnabledSwitches() {
        document.querySelectorAll(".pz-card-prop-switch input[type=checkbox]").forEach(el => {
            if (!/-enabled\]$/.test(el.name || "")) return;
            el.addEventListener("change", updateCardEnabledSwitch);
            updateCardEnabledSwitch({ target: el });
        });
    }

    function updateCardEnabledSwitch(e) {
        const switchInput = e.target;
        const row = switchInput.closest(".pz-card-prop-row");
        if (!row) return;

        const disabled = !switchInput.checked;
        row.classList.toggle("pz-card-prop-disabled", disabled);
        row.setAttribute("aria-disabled", disabled ? "true" : "false");

        row.querySelectorAll("input, select, textarea, button").forEach(el => {
            if (el === switchInput || el.type === "hidden") return;

            if (el.type === "checkbox" && el.closest(".pz-card-prop-switch")) {
                el.disabled = disabled;
                el.setAttribute("aria-disabled", disabled ? "true" : "false");
                const hiddenInput = el.previousElementSibling;
                if (hiddenInput?.type === "hidden" && hiddenInput.name === el.name) {
                    hiddenInput.disabled = disabled;
                }
                return;
            }

            el.disabled = false;
            el.setAttribute("aria-disabled", disabled ? "true" : "false");
            if (disabled) {
                if (el.dataset.pzCardTabindex === undefined) {
                    el.dataset.pzCardTabindex = el.hasAttribute("tabindex") ? el.getAttribute("tabindex") : "";
                }
                el.setAttribute("tabindex", "-1");
                if (el.matches("input, textarea")) {
                    el.readOnly = true;
                }
            } else {
                if (el.dataset.pzCardTabindex !== undefined) {
                    if (el.dataset.pzCardTabindex === "") {
                        el.removeAttribute("tabindex");
                    } else {
                        el.setAttribute("tabindex", el.dataset.pzCardTabindex);
                    }
                    delete el.dataset.pzCardTabindex;
                }
                if (el.matches("input, textarea")) {
                    el.readOnly = false;
                }
            }
        });
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

    function copyCardSettingsToHover(e) {
        const prefix = e.currentTarget?.dataset?.pzCardPrefix;
        if (!prefix) return;

        const findNamedControl = name => {
            const controls = Array.from(document.querySelectorAll(`[name="${name}"]`));
            return controls.find(control => control.type !== "hidden") || controls[0] || null;
        };

        [
            "transform-enabled",
            "transform-x",
            "transform-y",
            "transform-rotate",
            "transform-scale",
            "opacity",
            "bg-enabled",
            "bg-color",
            "bg-image",
            "border-enabled",
            "border-color",
            "border-style",
            "border-width",
            "border-radius",
            "shadow-enabled",
            "shadow-color",
            "shadow-x",
            "shadow-y",
            "shadow-blur",
            "shadow-spread",
            "shadow-inset",
            "transition"
        ].forEach(suffix => {
            const fromName = `properties[${prefix}-${suffix}]`;
            const toName = `properties[${prefix}-hover-${suffix}]`;
            const from = findNamedControl(fromName);
            const to = findNamedControl(toName);
            if (!from || !to) return;

            if (to.type === "checkbox") {
                to.checked = from.checked;
                if (/-enabled\]$/.test(to.name || "")) {
                    updateCardEnabledSwitch({ target: to });
                }
            } else {
                to.value = from.value;
            }
            to.dispatchEvent(new Event("input", { bubbles: true }));
            to.dispatchEvent(new Event("change", { bubbles: true }));

            const range = document.querySelector(`.pz-card-range[data-target="${toName}"]`);
            if (range) {
                range.value = to.value;
                updateCardRangeFill(range);
            }
        });
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
        if (!notice || !e.target.closest(".notice-dismiss, .pz-toast-dismiss")) return;

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

    function initSettingsToasts() {
        const dashboard = document.querySelector(".pz-dashboard.pz-settings");
        if (!dashboard) return;

        let container = document.body.querySelector(":scope > .pz-toast-container") || dashboard.querySelector(".pz-toast-container");
        if (!container) {
            container = document.createElement("div");
            container.className = "pz-toast-container";
            container.setAttribute("role", "status");
            container.setAttribute("aria-live", "polite");
        }
        if (container.parentElement !== document.body) {
            document.body.appendChild(container);
        }

        Object.assign(container.style, {
            position: "fixed",
            top: "84px",
            right: "24px",
            zIndex: "100000",
            display: "flex",
            flexDirection: "column",
            gap: "10px",
            width: "min(520px, calc(100vw - 48px))",
            pointerEvents: "none"
        });

        const notices = Array.from(dashboard.querySelectorAll(".notice")).filter(notice => !notice.closest(".pz-toast-container"));
        notices.forEach(notice => container.appendChild(notice));

        const pendingToasts = [];
        container.querySelectorAll(".notice, .pz-toast").forEach(toast => {
            if (toast.dataset.pzToastReady === "1") return;
            toast.dataset.pzToastReady = "1";
            toast.classList.add("pz-toast");
            toast.classList.remove("notice", "is-dismissible");
            Object.assign(toast.style, {
                position: "relative",
                margin: "0",
                padding: "12px 44px 12px 16px",
                boxSizing: "border-box",
                borderTop: "1px solid #c3c4c7",
                borderRight: "1px solid #c3c4c7",
                borderBottom: "1px solid #c3c4c7",
                borderRadius: "6px",
                background: "#fff",
                boxShadow: "0 8px 24px rgba(0, 0, 0, 0.22)",
                pointerEvents: "auto",
                transition: "opacity 0.8s ease, transform 0.8s ease"
            });
            let closeButton = toast.querySelector(".notice-dismiss, .pz-toast-dismiss");
            if (!closeButton) {
                closeButton = document.createElement("button");
                closeButton.type = "button";
                closeButton.className = "pz-toast-dismiss";
                closeButton.setAttribute("aria-label", "Dismiss this notice.");
                closeButton.innerHTML = '<span class="dashicons dashicons-no-alt" aria-hidden="true"></span>';
                toast.appendChild(closeButton);
            } else {
                closeButton.classList.remove("notice-dismiss");
                closeButton.classList.add("pz-toast-dismiss");
            }
            const dismiss = () => {
                if (toast.dataset.pzToastClosing === "1") return;
                toast.dataset.pzToastClosing = "1";
                toast.classList.add("pz-toast-hide");
                window.setTimeout(() => {
                    toast.style.visibility = "hidden";
                    toast.style.pointerEvents = "none";
                    toast.removeAttribute("data-pz-toast-visible");
                    cleanupSettingsToasts();
                }, 800);
            };

            toast.dataset.pzToastQueued = "1";
            toast.style.display = "none";
            pendingToasts.push(toast);
            if (closeButton) {
                closeButton.addEventListener("click", () => window.setTimeout(dismiss, 0));
            }
        });

        container._pzToastQueue = (container._pzToastQueue || []).concat(pendingToasts);
        scheduleSettingsToasts();

        function scheduleSettingsToasts() {
            if (container.dataset.pzToastScheduling === "1") return;
            container.dataset.pzToastScheduling = "1";
            showNextSettingsToast();
        }

        function showNextSettingsToast() {
            const nextToast = container._pzToastQueue?.shift();
            if (!nextToast) {
                container.dataset.pzToastScheduling = "0";
                return;
            }
            nextToast.dataset.pzToastVisible = "1";
            nextToast.classList.remove("pz-toast-hide");
            nextToast.style.display = "";
            nextToast.addEventListener("animationend", () => {
                nextToast.style.animation = "none";
            }, { once: true });
            window.setTimeout(() => {
                const closeButton = nextToast.querySelector(".pz-toast-dismiss");
                closeButton?.dispatchEvent(new MouseEvent("click", { bubbles: true, cancelable: true }));
            }, 6000);
            window.setTimeout(showNextSettingsToast, 500);
        }

        function cleanupSettingsToasts() {
            const hasQueuedToast = (container._pzToastQueue || []).length > 0;
            const hasOpenToast = Array.from(container.querySelectorAll(".pz-toast")).some(toast => toast.dataset.pzToastClosing !== "1");
            if (!hasQueuedToast && !hasOpenToast) {
                container.innerHTML = "";
            }
        }
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
            input.value = button.dataset.format === "css-url" ? `url(${attachment.url})` : attachment.url;
            input.dispatchEvent(new Event("input", { bubbles: true }));
            input.dispatchEvent(new Event("change", { bubbles: true }));
            updateImagePreview(input, attachment.url);
        });

        frame.open();
    }

    function clearCachemanImage(e) {
        const button = e.target.closest(".pz-man-cache-clear-image");
        if (!button) return;

        e.preventDefault();

        const target = button.dataset.target;
        const input = target ? document.querySelector(`input[name="${target}"]`) : null;
        if (!input) return;

        input.value = "";
        input.dispatchEvent(new Event("input", { bubbles: true }));
        input.dispatchEvent(new Event("change", { bubbles: true }));
        updateImagePreview(input, "");
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
            if (["scroll-now", "scroll_now", "tab-now"].includes(el.name)) return;
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

    function initCachemanEditorShortcuts() {
        const editor = document.querySelector(".pz-man-cache-dirty-check");
        const form = editor?.closest("form");
        if (!editor || !form) return;

        const clickActionButton = action => {
            const button = form.querySelector(`button[name="action"][value="${action}"]`);
            if (button && !button.disabled) button.click();
        };

        document.addEventListener("keydown", e => {
            if (e.isComposing || e.repeat) return;

            if (e.key === "Escape" || e.key === "Esc") {
                e.preventDefault();
                clickActionButton("cancel");
                return;
            }

            if (e.altKey && !e.ctrlKey && !e.metaKey && !e.shiftKey && e.key.toLowerCase() === "s") {
                e.preventDefault();
                clickActionButton("update");
            }
        });
    }

    function initSettingsSaveShortcut() {
        const settings = document.querySelector(".pz-settings");
        const form = settings?.querySelector("form");
        if (!settings || !form) return;

        document.addEventListener("keydown", e => {
            if (e.isComposing || e.repeat) return;
            if (!e.altKey || e.ctrlKey || e.metaKey || e.shiftKey || e.key.toLowerCase() !== "s") return;

            const submitButton = form.querySelector(".pz-page-active .pz-submit-float input[type='submit']:not(:disabled)")
                || form.querySelector("input[type='submit']:not(:disabled)");
            if (!submitButton) return;

            e.preventDefault();
            if (form.requestSubmit) {
                form.requestSubmit(submitButton);
            } else {
                submitButton.click();
            }
        });
    }

    function initSettingsScrollMemory() {
        const form = document.querySelector(".pz-settings form");
        const scrollNow = form?.querySelector('input[name="scroll-now"]');
        if (!form || !scrollNow) return;

        form.addEventListener("submit", () => {
            scrollNow.value = String(Math.max(0, Math.round(window.scrollY || window.pageYOffset || 0)));
        }, true);
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
        let rightButtonDown = false;
        let rightWheelUsed = false;
        let submitGap = null;
        let invalidNavigationActive = false;

        tabbarSpacer.className = "pz-tabbar-spacer";
        tabbarSpacer.style.height = "0";
        wrapper.parentNode.insertBefore(tabbarSpacer, wrapper);

        const getFixedTop = () => {
            const adminBar = document.querySelector("#wpadminbar");
            const adminBarBottom = adminBar ? Math.max(0, adminBar.getBoundingClientRect().bottom) : 0;
            const infobar = document.querySelector("#pz-infobar");
            const infobarBottom = infobar ? Math.max(0, infobar.getBoundingClientRect().bottom) : 0;
            const viewportTop = window.visualViewport ? Math.max(0, window.visualViewport.offsetTop) : 0;
            return Math.max(adminBarBottom, infobarBottom, viewportTop);
        };

        const measureSubmitGap = () => {
            const tabRect = wrapper.getBoundingClientRect();
            const submitRect = submitFloat?.getBoundingClientRect();
            if (submitRect && !wrapper.classList.contains("pz-tabbar-fixed")) {
                const minGap = window.matchMedia("(max-width: 782px)").matches ? 32 : 12;
                submitGap = Math.max(minGap, Math.round(submitRect.top - tabRect.bottom));
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
                    const minGap = window.matchMedia("(max-width: 782px)").matches ? 32 : 12;
                    if (submitGap === null) submitGap = minGap;
                    submitGap = Math.max(minGap, submitGap);
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

        const focusElement = control => {
            if (typeof control?.focus !== "function") return;
            try {
                control.focus({ preventScroll: true });
            } catch (e) {
                control.focus();
            }
        };

        const getFocusItem = element => {
            if (!element) return "";

            const target = element.dataset?.target || "";
            let match = target.match(/^properties\[([^\]]+)\]$/);
            if (match) return match[1];

            const name = element.getAttribute?.("name") || "";
            match = name.match(/^properties\[([^\]]+)\]$/);
            return match ? match[1] : "";
        };

        const getTabByPageId = pageId => tabbar.querySelector(`.pz-tab[name="${pageId}"], .pz-tab[href="#${pageId}"]`);

        const getFocusableControlByItem = (page, item) => {
            if (!page || !item) return null;

            return Array.from(page.querySelectorAll("input, select, textarea, button, a[href], [tabindex]")).find(control => {
                if (control.type === "hidden" || control.disabled) return false;
                const style = window.getComputedStyle(control);
                if (style.display === "none" || style.visibility === "hidden" || !control.getClientRects().length) return false;
                return getFocusItem(control) === item;
            }) || null;
        };

        const mapFocusItemForTab = (item, tabName) => {
            if (!item) return "";
            if (tabName === "pz-external" && item.startsWith("in-")) return `ex-${item.slice(3)}`;
            if (tabName === "pz-internal" && item.startsWith("ex-")) return `in-${item.slice(3)}`;
            return item;
        };

        const saveActiveFocusItem = () => {
            const activeTab = tabbar.querySelector(".pz-tab-active");
            const activePage = document.getElementById(getTabName(activeTab));
            const item = getFocusItem(document.activeElement);
            if (activeTab && activePage?.contains(document.activeElement) && item) {
                activeTab.dataset.focusItem = item;
                return item;
            }
            return activeTab?.dataset.focusItem || "";
        };

        const getRestoreFocusItem = (tab, sourceFocusItem = "") => {
            const tabName = getTabName(tab);
            const page = document.getElementById(tabName);
            const mappedItem = mapFocusItemForTab(sourceFocusItem, tabName);
            if (getFocusableControlByItem(page, mappedItem)) return mappedItem;
            if (getFocusableControlByItem(page, tab.dataset.focusItem || "")) return tab.dataset.focusItem;
            return "";
        };

        const openTab = (tab, focusTab = false, options = {}) => {
            const tabName = getTabName(tab);
            if (!tabName) return;
            const restoreFocus = options.restoreFocus !== false;
            const sourceFocusItem = options.sourceFocusItem ?? saveActiveFocusItem();

            getTabs().forEach(item => item.classList.remove("pz-tab-active"));
            document.querySelectorAll(".pz-page").forEach(page => page.classList.remove("pz-page-active"));

            tab.classList.add("pz-tab-active");
            document.getElementById(tabName)?.classList.add("pz-page-active");
            if (tabNameEl) tabNameEl.textContent = tab.textContent;
            if (tabNow) tabNow.value = tabName;

            adjustTabVisibility(tab);
            updateButtons();
            if (restoreFocus) {
                const focusItem = getRestoreFocusItem(tab, sourceFocusItem);
                const focusControl = getFocusableControlByItem(document.getElementById(tabName), focusItem);
                if (focusControl) {
                    tab.dataset.focusItem = focusItem;
                    window.setTimeout(() => focusElement(focusControl), 0);
                    return;
                }
            }
            if (focusTab) tab.focus();
        };

        const focusControl = control => {
            focusElement(control);
        };

        const isControlInView = control => {
            if (!control) return;

            const rect = control.getBoundingClientRect();
            const fixedTop = getFixedTop() + wrapper.offsetHeight + 16;
            const fixedBottom = 16;
            return rect.top >= fixedTop && rect.bottom <= window.innerHeight - fixedBottom;
        };

        const scrollToControl = control => {
            if (!control) return;

            if (isControlInView(control)) {
                focusControl(control);
                return;
            }

            const rect = control.getBoundingClientRect();
            const fixedTop = getFixedTop() + wrapper.offsetHeight + 16;
            const visibleHeight = Math.max(1, window.innerHeight - fixedTop);
            const targetTop = Math.max(0, window.scrollY + rect.top - fixedTop - ((visibleHeight - rect.height) / 2));
            window.scrollTo({ top: targetTop, behavior: "smooth" });
            focusControl(control);
        };

        const showInvalidControl = control => {
            if (invalidNavigationActive) return;
            invalidNavigationActive = true;
            window.setTimeout(() => {
                invalidNavigationActive = false;
            }, 500);

            const page = control?.closest(".pz-page");
            if (!page?.id) return;

            const tab = tabbar.querySelector(`.pz-tab[name="${page.id}"], .pz-tab[href="#${page.id}"]`);
            if (tab) openTab(tab, false, { restoreFocus: false });

            window.setTimeout(() => scrollToControl(control), 60);
        };

        document.querySelector(".pz-settings form")?.addEventListener("invalid", e => {
            showInvalidControl(e.target);
        }, true);

        dashboard.addEventListener("focusin", e => {
            const page = e.target.closest(".pz-page");
            const item = getFocusItem(e.target);
            if (!page?.id || !item) return;

            const tab = getTabByPageId(page.id);
            if (tab) tab.dataset.focusItem = item;
        });

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

        const changeWheelControl = (control, delta) => {
            if (!control || !dashboard.contains(control) || control.disabled || control.readOnly) return false;

            if (control.matches("input[type='checkbox']")) {
                const nextChecked = delta < 0;
                if (control.checked === nextChecked) return false;
                control.checked = nextChecked;
            } else if (control.matches("select")) {
                const options = Array.from(control.options);
                const currentIndex = control.selectedIndex;
                let nextIndex = currentIndex;
                const direction = delta > 0 ? 1 : -1;
                do {
                    nextIndex += direction;
                } while (nextIndex >= 0 && nextIndex < options.length && options[nextIndex].disabled);

                if (nextIndex < 0 || nextIndex >= options.length || nextIndex === currentIndex) return false;
                control.selectedIndex = nextIndex;
            } else {
                const currentValue = Number(control.value);
                const stepValue = control.step && control.step !== "any" ? Number(control.step) : 1;
                if (!Number.isFinite(currentValue) || !Number.isFinite(stepValue) || stepValue <= 0) return false;

                const min = control.min === "" ? -Infinity : Number(control.min);
                const max = control.max === "" ? Infinity : Number(control.max);
                const nextValue = Math.min(max, Math.max(min, currentValue + (delta > 0 ? -stepValue : stepValue)));
                if (nextValue === currentValue) return false;
                control.value = String(nextValue);
            }

            control.dispatchEvent(new Event("input", { bubbles: true }));
            control.dispatchEvent(new Event("change", { bubbles: true }));
            return true;
        };

        const getWheelControl = (target, includeCheckbox = false) => {
            const checkboxSelector = includeCheckbox ? ", input[type='checkbox']" : "";
            return target?.closest?.(`select, input:not([type='hidden']):not([type='checkbox']):not([type='radio']):not([type='button']):not([type='submit']):not([type='reset']):not([type='file'])${checkboxSelector}`);
        };

        dashboard.addEventListener("wheel", e => {
            if (!e.shiftKey) return;

            const delta = Math.abs(e.deltaX) > Math.abs(e.deltaY) ? e.deltaX : e.deltaY;
            if (delta === 0) return;

            if (e.shiftKey && tabbar.contains(e.target)) {
                e.preventDefault();
                e.stopPropagation();
                const now = Date.now();
                if (now - lastWheelAt < 120) return;
                lastWheelAt = now;
                moveTab(delta > 0 ? 1 : -1, false);
                return;
            }

            const switchUi = e.target.closest(".pz-card-switch-ui");
            if (switchUi && dashboard.contains(switchUi)) {
                const checkbox = switchUi.closest(".pz-card-prop-switch")?.querySelector("input[type='checkbox']");
                if (!checkbox || checkbox.disabled || checkbox.readOnly) return;

                const nextChecked = delta < 0;
                if (checkbox.checked === nextChecked) return;
                checkbox.checked = nextChecked;
                e.preventDefault();
                e.stopPropagation();
                checkbox.dispatchEvent(new Event("input", { bubbles: true }));
                checkbox.dispatchEvent(new Event("change", { bubbles: true }));
                return;
            }

            const control = getWheelControl(e.target);
            if (!changeWheelControl(control, delta)) return;

            e.preventDefault();
            e.stopPropagation();
        }, { passive: false });

        tabbar.addEventListener("click", e => {
            const tab = e.target.closest(".pz-tab");
            if (!tab || !tabbar.contains(tab)) return;
            if (e.button !== 0) return;

            e.preventDefault();
            openTab(tab, true);
        });

        tabbar.addEventListener("keydown", e => {
            const tab = e.target.closest(".pz-tab");
            if (!tab || !tabbar.contains(tab)) return;
            if (e.key !== "ArrowLeft" && e.key !== "ArrowRight") return;

            if (e.ctrlKey && !e.altKey && !e.metaKey && !e.shiftKey) {
                e.preventDefault();
                e.stopPropagation();
                moveTab(e.key === "ArrowRight" ? 1 : -1, false, tab);
                return;
            }
            if (e.altKey || e.metaKey || e.shiftKey) return;

            e.preventDefault();
            e.stopPropagation();
            moveTab(e.key === "ArrowRight" ? 1 : -1, true, tab);
        });

        document.addEventListener("keydown", e => {
            if (!e.ctrlKey || e.altKey || e.metaKey || e.shiftKey || e.isComposing) return;
            if (e.key !== "ArrowLeft" && e.key !== "ArrowRight") return;
            if (tabbar.contains(e.target)) return;

            e.preventDefault();
            e.stopPropagation();
            moveTab(e.key === "ArrowRight" ? 1 : -1, false);
        });

        dashboard.addEventListener("wheel", e => {
            if (!rightButtonDown || e.shiftKey) return;

            const delta = Math.abs(e.deltaX) > Math.abs(e.deltaY) ? e.deltaX : e.deltaY;
            if (delta === 0) return;

            rightWheelUsed = true;
            e.preventDefault();
            e.stopPropagation();

            const switchUi = e.target.closest(".pz-card-switch-ui");
            if (switchUi && dashboard.contains(switchUi)) {
                const checkbox = switchUi.closest(".pz-card-prop-switch")?.querySelector("input[type='checkbox']");
                if (changeWheelControl(checkbox, delta)) {
                    return;
                }
            }

            const control = getWheelControl(e.target, true);
            if (changeWheelControl(control, delta)) {
                return;
            }

            if (!tabbar.contains(e.target)) return;

            const now = Date.now();
            if (now - lastWheelAt < 120) return;
            lastWheelAt = now;
            moveTab(delta > 0 ? 1 : -1, false);
        }, { passive: false });

        dashboard.addEventListener("pointerdown", e => {
            if (e.button === 2) {
                rightButtonDown = true;
                rightWheelUsed = false;
            }
        });
        document.addEventListener("pointerup", e => {
            if (e.button === 2) rightButtonDown = false;
        });
        document.addEventListener("pointercancel", e => {
            if (e.button === 2) rightButtonDown = false;
        });
        window.addEventListener("blur", () => {
            rightButtonDown = false;
        });
        dashboard.addEventListener("contextmenu", e => {
            if (rightWheelUsed) e.preventDefault();
        });

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
        if (!imageBox) return;

        const clearButton = input
            ?.closest(".pz-man-cache-image-box")
            ?.querySelector(".pz-man-cache-clear-image");

        imageBox.innerHTML = "";

        if (!url) {
            imageBox.classList.add("pz-man-cache-image-empty");
            imageBox.textContent = "-";
            if (clearButton) clearButton.hidden = true;
            return;
        }

        imageBox.classList.remove("pz-man-cache-image-empty");
        if (clearButton) clearButton.hidden = false;

        const link = document.createElement("a");
        link.href = url;
        link.target = "_blank";
        link.rel = "noopener";
        link.referrerPolicy = "no-referrer";
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

    function initImageBox() {
        const cacheman = document.querySelector(".pz-cacheman");
        if (!cacheman) return;

        const CLICK_ZOOM = 5;
        const MAX_ZOOM = 10;
        const MIN_ZOOM = 1;
        const WHEEL_ZOOM_STEP = 0.5;

        const style = document.createElement("style");
        style.textContent = `
            .pz-image-box {
                position: fixed;
                display: flex;
                align-items: center;
                justify-content: center;
                box-sizing: border-box;
                padding: 56px 28px 28px;
                overflow: auto;
                background: rgba(0, 0, 0, 0.72);
                z-index: 1000;
                cursor: default;
            }
            .pz-image-box img {
                display: block;
                max-width: min(92vw, 100%);
                max-height: calc(100vh - 120px);
                object-fit: contain;
                background: #fff;
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.5);
                cursor: zoom-in;
                transform-origin: center center;
                transition: transform 120ms ease;
                user-select: none;
            }
            .pz-image-box img.pz-image-box-zoomed {
                cursor: zoom-out;
            }
            .pz-image-box-close {
                position: absolute;
                top: 12px;
                right: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 36px;
                height: 36px;
                padding: 0 0 2px;
                border: 0;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.92);
                color: #111;
                font-size: 28px;
                line-height: 1;
                cursor: pointer;
            }
            .pz-image-box-close:hover,
            .pz-image-box-close:focus {
                background: #fff;
                outline: 2px solid #72aee6;
                outline-offset: 2px;
            }
        `;
        document.head.appendChild(style);

        let box = null;
        let observer = null;
        let zoom = 1;
        let activeImage = null;

        const clampZoom = value => Math.min(MAX_ZOOM, Math.max(MIN_ZOOM, value));

        const applyZoom = () => {
            if (!activeImage) return;
            activeImage.style.transform = `scale(${zoom})`;
            activeImage.classList.toggle("pz-image-box-zoomed", zoom > 1);
        };

        const setZoom = value => {
            zoom = clampZoom(value);
            applyZoom();
        };

        const getContentLeft = () => {
            const wpContent = document.querySelector("#wpcontent");
            if (wpContent) return wpContent.getBoundingClientRect().left;

            const menuWrap = document.querySelector("#adminmenuwrap");
            return menuWrap ? menuWrap.getBoundingClientRect().right : 0;
        };

        const positionBox = () => {
            if (!box) return;

            const adminBar = document.querySelector("#wpadminbar");
            const infobar = document.querySelector("#pz-infobar");
            const top = infobar
                ? infobar.getBoundingClientRect().bottom
                : adminBar
                    ? adminBar.getBoundingClientRect().bottom
                    : 0;
            const left = getContentLeft();

            box.style.top = `${Math.max(0, top)}px`;
            box.style.left = `${Math.max(0, left)}px`;
            box.style.right = "0";
            box.style.bottom = "0";
            box.style.width = "auto";
            box.style.height = "auto";
        };

        const closeBox = () => {
            if (!box) return;

            box.remove();
            box = null;
            activeImage = null;
            zoom = 1;
            window.removeEventListener("resize", positionBox);
            window.removeEventListener("scroll", positionBox, true);
            window.removeEventListener("keydown", handleKeydown);

            if (observer) {
                observer.disconnect();
                observer = null;
            }
        };

        function handleKeydown(e) {
            if (e.key !== "Escape" && e.key !== "Esc") return;
            closeBox();
        }

        const openBox = (src, alt) => {
            closeBox();

            box = document.createElement("div");
            box.className = "pz-image-box";

            const close = document.createElement("button");
            close.type = "button";
            close.className = "pz-image-box-close";
            close.setAttribute("aria-label", "Close");
            close.textContent = "\u00d7";

            const img = document.createElement("img");
            img.src = src;
            img.alt = alt || "";
            activeImage = img;
            zoom = 1;

            box.append(close, img);
            document.body.appendChild(box);
            positionBox();

            close.addEventListener("click", closeBox);
            box.addEventListener("click", closeBox);
            img.addEventListener("click", e => {
                e.stopPropagation();
                setZoom(zoom > MIN_ZOOM ? MIN_ZOOM : CLICK_ZOOM);
            });
            box.addEventListener("wheel", e => {
                if (!e.ctrlKey) return;

                e.preventDefault();
                e.stopPropagation();
                const delta = e.deltaY > 0 ? -WHEEL_ZOOM_STEP : WHEEL_ZOOM_STEP;
                setZoom(zoom + delta);
            }, { passive: false });

            window.addEventListener("resize", positionBox);
            window.addEventListener("scroll", positionBox, true);
            window.addEventListener("keydown", handleKeydown);

            observer = new MutationObserver(positionBox);
            observer.observe(document.body, { attributes: true, attributeFilter: ["class"] });

            const menuWrap = document.querySelector("#adminmenuwrap");
            if (menuWrap) {
                observer.observe(menuWrap, { attributes: true, attributeFilter: ["class", "style"] });
            }
        };

        cacheman.addEventListener("click", e => {
            const link = e.target?.closest?.(".pz-man-thumbnail, .pz-man-image-box-trigger");
            if (!link || !cacheman.contains(link)) return;

            const img = link.querySelector("img");
            const src = link.getAttribute("href") || img?.src;
            if (!src) return;

            e.preventDefault();
            e.stopPropagation();
            openBox(src, img?.alt || "");
        });
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

        const resetToFirstPage = () => {
            const pageNow = input.form?.querySelector('input[name="page_now"]');
            const pageTrans = input.form?.querySelector('input[name="page_trans"]');
            if (pageNow) pageNow.value = "1";
            if (pageTrans) pageTrans.value = "1";
        };

        const submitSearch = () => {
            resetToFirstPage();
            if (input.form?.requestSubmit) {
                input.form.requestSubmit(searchSubmit);
            } else {
                searchSubmit.click();
            }
        };

        const runIdSearch = id => {
            if (!id) return;

            input.value = `ID:${id}`;
            input.dispatchEvent(new Event("input", { bubbles: true }));
            input.dispatchEvent(new Event("change", { bubbles: true }));

            submitSearch();
        };

        input.addEventListener("keydown", e => {
            if (e.key !== "Enter" || e.isComposing) return;

            e.preventDefault();
            submitSearch();
        });

        searchSubmit.addEventListener("click", () => {
            resetToFirstPage();
        });

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

    function initCachemanPaginationKeys() {
        const cacheman = document.querySelector(".pz-cacheman");
        const pageInput = document.querySelector('input[name="page_trans"]');
        const form = pageInput?.closest("form");
        if (!cacheman || !pageInput || !form) return;

        const isEditableTarget = target => {
            if (!target) return false;
            if (target.isContentEditable) return true;
            return !!target.closest?.("input, textarea, select, [contenteditable='true']");
        };

        const getPageMax = () => {
            const total = document.querySelector(".pz-man-pages .total-pages")?.textContent || "";
            const max = parseInt(total.replace(/[^\d]/g, ""), 10);
            return Number.isFinite(max) && max > 0 ? max : 1;
        };

        const movePage = delta => {
            const pageNow = parseInt(pageInput.value, 10) || 1;
            const pageMax = getPageMax();
            const nextPage = Math.min(pageMax, Math.max(1, pageNow + delta));
            if (nextPage === pageNow) return;

            pageInput.value = String(nextPage);
            pageInput.dispatchEvent(new Event("input", { bubbles: true }));
            pageInput.dispatchEvent(new Event("change", { bubbles: true }));

            if (form.requestSubmit) {
                form.requestSubmit();
            } else {
                form.submit();
            }
        };

        document.addEventListener("keydown", e => {
            if (!e.ctrlKey || e.altKey || e.metaKey || e.shiftKey) return;
            if (e.key !== "ArrowLeft" && e.key !== "ArrowRight") return;
            if (isEditableTarget(e.target)) return;

            e.preventDefault();
            movePage(e.key === "ArrowRight" ? 1 : -1);
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
