document.addEventListener("DOMContentLoaded", () => {
    const dashboard = document.querySelector(".pz-dashboard");
    if (!dashboard) return;

    function initSettingsPreviewWindow() {
        const win = document.querySelector(".pz-settings-preview-window");
        const handle = win?.querySelector("[data-pz-preview-handle]");
        const modeButton = win?.querySelector("[data-pz-preview-mode]");
        const closeButton = win?.querySelector("[data-pz-preview-close]");
        const backgroundButtons = win?.querySelectorAll("[data-pz-preview-background]");
        const twoCardsCheckbox = win?.querySelector("[data-pz-preview-two-cards]");
        if (!win || !handle) return;
        const form = document.querySelector(".pz-settings form");
        const storageKey = "pz-linkcard-preview-state";
        const labels = (typeof pzLinkCardPreview !== "undefined" && pzLinkCardPreview.labels) || {};
        const restoreButton = document.createElement("button");
        restoreButton.type = "button";
        restoreButton.className = "pz-settings-preview-restore";
        restoreButton.textContent = labels.restorePreview || "□Preview";
        restoreButton.setAttribute("aria-label", labels.restorePreviewAria || "Show preview");
        restoreButton.hidden = true;
        document.body.appendChild(restoreButton);
        let previewFadeTimer = null;
        let previewClosed = false;
        const showPreviewWindow = () => {
            previewClosed = false;
            window.clearTimeout(previewFadeTimer);
            win.style.display = "";
            window.requestAnimationFrame(() => {
                win.classList.add("pz-settings-preview-ready");
            });
        };
        const showRestoreButton = () => {
            restoreButton.hidden = false;
            window.requestAnimationFrame(() => {
                restoreButton.classList.add("pz-settings-preview-restore-ready");
            });
        };
        const hideRestoreButton = () => {
            restoreButton.classList.remove("pz-settings-preview-restore-ready");
            window.setTimeout(() => {
                if (!restoreButton.classList.contains("pz-settings-preview-restore-ready")) {
                    restoreButton.hidden = true;
                }
            }, 160);
        };
        const hidePreviewWindow = () => {
            window.clearTimeout(previewFadeTimer);
            win.classList.remove("pz-settings-preview-ready");

            const finish = () => {
                win.removeEventListener("transitionend", onTransitionEnd);
                if (!win.classList.contains("pz-settings-preview-ready")) {
                    win.style.display = "none";
                }
            };
            const onTransitionEnd = e => {
                if (e.target === win && e.propertyName === "opacity") finish();
            };

            win.addEventListener("transitionend", onTransitionEnd);
            previewFadeTimer = window.setTimeout(finish, 220);
        };
        const showIconPreview = () => {
            previewClosed = true;
            setDockedScrollSpace(0);
            setRightDockedScrollSpace(0);
            win.classList.remove("pz-settings-preview-ready", "pz-settings-preview-docked", "pz-settings-preview-docked-right");
            win.style.display = "none";
            showRestoreButton();
        };

        const previewBackgrounds = {
            white: { background: "#fff", image: "none" },
            gray: { background: "#888", image: "none" },
            black: { background: "#000", image: "none" },
            red: { background: "#f8d7da", image: "none" },
            green: { background: "#d8f0df", image: "none" },
            blue: { background: "#d9eafa", image: "none" },
            rectangles: {
                background: "#fffdf4",
                image: "repeating-linear-gradient(45deg, #f9edbd 25%, transparent 25%, transparent 75%, #f9edbd 75%, #f9edbd), repeating-linear-gradient(45deg, #f9edbd 25%, #fffdf4 25%, #fffdf4 75%, #f9edbd 75%, #f9edbd)",
                position: "0 0, 20px 20px",
                size: "40px 40px",
            },
            diagonal: {
                background: "#fff",
                image: "repeating-linear-gradient(135deg, #d9eef7 0, #d9eef7 4px, transparent 4px, transparent 10px)",
            },
            crosshatch: {
                background: "#fff",
                image: "repeating-linear-gradient(45deg, rgba(224, 174, 194, 0.35), rgba(224, 174, 194, 0.35) 20px, transparent 0, transparent 40px), repeating-linear-gradient(315deg, rgba(224, 174, 194, 0.35), rgba(224, 174, 194, 0.35) 20px, transparent 0, transparent 40px)",
            },
        };
        const applyPreviewBackground = name => {
            const background = previewBackgrounds[name] || previewBackgrounds.white;
            win.style.backgroundColor = background.background;
            win.style.backgroundImage = background.image;
            win.style.backgroundPosition = background.position || "";
            win.style.backgroundSize = background.size || "";
            backgroundButtons?.forEach(button => {
                button.classList.toggle("is-selected", button.dataset.pzPreviewBackground === name);
            });
        };
        backgroundButtons?.forEach(button => {
            button.addEventListener("click", e => {
                e.preventDefault();
                e.stopPropagation();
                applyPreviewBackground(button.dataset.pzPreviewBackground);
                button.blur();
            });
        });
        applyPreviewBackground("white");

        const preventPreviewLink = e => {
            if (!e.target?.closest?.(".pz-settings-preview-window a")) return;
            e.preventDefault();
            e.stopPropagation();
        };
        win.addEventListener("click", preventPreviewLink, true);
        win.addEventListener("auxclick", preventPreviewLink, true);

        const minPreviewWidth = 320;
        const minPreviewHeight = 180;
        const dockedMinHeightFallback = 28;
        let previewDocked = false;
        let previewDockSide = null;
        let floatingPreviewRect = null;
        let suppressHandleDblClick = false;
        let lastHandleClick = { time: 0, x: 0, y: 0 };
        const stateInput = name => form?.querySelector(`[data-pz-preview-state="${name}"]`) || null;
        const setStateInput = (name, value) => {
            const input = stateInput(name);
            if (input) input.value = value ?? "";
        };
        const readStateInput = name => stateInput(name)?.value ?? "";
        const readStateNumber = (state, name) => {
            const raw = state && Object.prototype.hasOwnProperty.call(state, name) ? state[name] : readStateInput(name);
            const parsed = parseInt(raw, 10);
            return Number.isFinite(parsed) ? parsed : null;
        };
        const readStateFlag = (state, name) => {
            const raw = state && Object.prototype.hasOwnProperty.call(state, name) ? state[name] : readStateInput(name);
            return raw === true || raw === 1 || raw === "1" || raw === "true";
        };
        const readStoredState = () => {
            try {
                const stored = window.localStorage?.getItem(storageKey);
                if (!stored) return null;
                const parsed = JSON.parse(stored);
                return parsed && typeof parsed === "object" ? parsed : null;
            } catch (err) {
                return null;
            }
        };
        const applyTwoCards = enabled => {
            const active = !!enabled;
            win.classList.toggle("pz-settings-preview-two-cards", active);
            if (twoCardsCheckbox) twoCardsCheckbox.checked = active;
            setStateInput("preview-two-cards", active ? "1" : "0");
        };
        const updateModeButton = () => {
            if (!modeButton) return;
            modeButton.textContent = previewDocked ? "□" : "_";
            modeButton.setAttribute("aria-label", previewDocked ? "Window preview" : "Dock preview bottom");
            modeButton.title = previewDocked ? "ウィンドウ状態にする" : "下にドッキング";
        };

        const getAdminBarBottom = () => {
            const adminBar = document.querySelector("#wpadminbar");
            const infobar = document.querySelector("#pz-infobar");
            const adminBarBottom = adminBar ? Math.max(0, adminBar.getBoundingClientRect().bottom) : 0;
            const infobarBottom = infobar ? Math.max(0, infobar.getBoundingClientRect().bottom) : 0;
            return Math.max(adminBarBottom, infobarBottom);
        };

        const clampToViewport = (left, top) => {
            const margin = 8;
            const rect = win.getBoundingClientRect();
            const minLeft = Math.max(margin, getDockLeft() + margin);
            const minTop = Math.max(margin, getAdminBarBottom());
            const maxLeft = Math.max(minLeft, window.innerWidth - rect.width - margin);
            const maxTop = Math.max(minTop, window.innerHeight - rect.height - margin);
            return {
                left: Math.min(maxLeft, Math.max(minLeft, left)),
                top: Math.min(maxTop, Math.max(minTop, top)),
            };
        };

        const getViewportBounds = () => {
            const margin = 8;
            return {
                minLeft: Math.max(margin, getDockLeft() + margin),
                minTop: Math.max(margin, getAdminBarBottom()),
                maxRight: window.innerWidth - margin,
                maxBottom: window.innerHeight - margin,
            };
        };
        const getDockLeft = () => {
            const wpContent = document.getElementById("wpcontent");
            return wpContent ? Math.max(0, wpContent.getBoundingClientRect().left) : 0;
        };
        const getViewportClientRight = () => {
            return document.documentElement?.clientWidth || window.innerWidth;
        };
        const getDockedMinHeight = () => {
            const handleHeight = Math.ceil(handle.getBoundingClientRect().height);
            return Math.max(dockedMinHeightFallback, handleHeight);
        };
        const getWindowRect = () => {
            const rect = win.getBoundingClientRect();
            return {
                left: rect.left,
                top: rect.top,
                width: rect.width,
                height: rect.height,
            };
        };
        const syncPreviewState = () => {
            if (win.style.display === "none") return;
            const rect = getWindowRect();
            if (rect.width <= 0 || rect.height <= 0) return null;

            const state = {
                ...(readStoredState() || {}),
                "preview-mode": previewDockSide === "right" ? "right" : (previewDocked ? "docked" : "window"),
                "preview-left": Math.round(rect.left),
                "preview-top": Math.round(rect.top),
                "preview-width": Math.round(rect.width),
                "preview-height": Math.round(rect.height),
                "preview-docked-height": Math.round(previewDockSide === "bottom" ? rect.height : (readStateNumber(null, "preview-docked-height") || rect.height)),
                "preview-right-docked-width": Math.round(previewDockSide === "right" ? rect.width : (readStateNumber(null, "preview-right-docked-width") || rect.width)),
                "preview-two-cards": twoCardsCheckbox?.checked ? 1 : 0,
            };
            if (previewDockSide === "right") {
                state["right-docked-width"] = Math.round(rect.width);
            } else if (previewDocked) {
                Object.assign(state, {
                    "docked-left": Math.round(rect.left),
                    "docked-top": Math.round(rect.top),
                    "docked-width": Math.round(rect.width),
                    "docked-height": Math.round(rect.height),
                });
            } else {
                Object.assign(state, {
                    "window-left": Math.round(rect.left),
                    "window-top": Math.round(rect.top),
                    "window-width": Math.round(rect.width),
                    "window-height": Math.round(rect.height),
                });
            }
            Object.entries(state).forEach(([name, value]) => setStateInput(name, value));
            try {
                window.localStorage?.setItem(storageKey, JSON.stringify(state));
            } catch (err) {
                // Ignore storage failures such as private browsing quota errors.
            }
            return state;
        };
        const saveIconState = () => {
            const rect = getWindowRect();
            const storedState = readStoredState() || {};
            const state = {
                ...storedState,
                "preview-mode": "icon",
                "preview-restore-mode": previewDockSide === "right" ? "right" : (previewDocked ? "docked" : "window"),
                "preview-left": Math.round(rect.left),
                "preview-top": Math.round(rect.top),
                "preview-width": Math.round(rect.width),
                "preview-height": Math.round(rect.height),
                "preview-docked-height": Math.round(previewDockSide === "bottom" ? rect.height : (readStateNumber(null, "preview-docked-height") || rect.height)),
                "preview-right-docked-width": Math.round(previewDockSide === "right" ? rect.width : (readStateNumber(null, "preview-right-docked-width") || rect.width)),
                "preview-two-cards": twoCardsCheckbox?.checked ? 1 : 0,
                "window-left": readStateNumber(storedState, "window-left"),
                "window-top": readStateNumber(storedState, "window-top"),
                "window-width": readStateNumber(storedState, "window-width"),
                "window-height": readStateNumber(storedState, "window-height"),
                "docked-left": readStateNumber(storedState, "docked-left"),
                "docked-top": readStateNumber(storedState, "docked-top"),
                "docked-width": readStateNumber(storedState, "docked-width"),
                "docked-height": readStateNumber(storedState, "docked-height"),
                "right-docked-width": readStateNumber(storedState, "right-docked-width"),
            };
            setStateInput("preview-mode", "icon");
            try {
                window.localStorage?.setItem(storageKey, JSON.stringify(state));
            } catch (err) {
                // Ignore storage failures such as private browsing quota errors.
            }
        };
        const persistPreviewState = async () => {
            if (typeof pzLinkCardPreview === "undefined" || !pzLinkCardPreview.ajaxUrl) return;
            const state = syncPreviewState();
            if (!state) return;

            const fd = new FormData();
            fd.append("action", pzLinkCardPreview.stateAction || "pz_lkc_preview_state");
            fd.append("nonce", pzLinkCardPreview.stateNonce || "");
            Object.entries(state).forEach(([name, value]) => fd.append(name, value));
            try {
                await fetch(pzLinkCardPreview.ajaxUrl, { method: "POST", body: fd });
            } catch (err) {
                console.warn("Pz-LinkCard preview state failed:", err);
            }
        };
        const setDockedScrollSpace = (height = 0) => {
            const active = previewDockSide === "bottom" && height > 0;
            document.body.classList.toggle("pz-settings-preview-docked-active", active);
            if (active) {
                document.documentElement.style.setProperty("--pz-settings-preview-docked-height", `${Math.round(height)}px`);
            } else {
                document.documentElement.style.removeProperty("--pz-settings-preview-docked-height");
            }
        };
        const setRightDockedScrollSpace = (width = 0) => {
            const active = previewDockSide === "right" && width > 0;
            document.body.classList.toggle("pz-settings-preview-right-docked-active", active);
            if (active) {
                document.documentElement.style.setProperty("--pz-settings-preview-right-docked-width", `${Math.round(width)}px`);
            } else {
                document.documentElement.style.removeProperty("--pz-settings-preview-right-docked-width");
            }
        };
        const getResizeEdges = e => {
            const rect = win.getBoundingClientRect();
            const edgeSize = 8;
            if (previewDocked) {
                if (previewDockSide === "right") {
                    const draggablePalette = e.target?.closest?.(".pz-settings-preview-palette")
                        && !e.target?.closest?.(".pz-settings-preview-background");
                    return draggablePalette
                        ? { top: false, right: false, bottom: false, left: true }
                        : null;
                }
                return e.clientY - rect.top <= edgeSize ? { top: true, right: false, bottom: false, left: false } : null;
            }
            const edges = {
                top: e.clientY - rect.top <= edgeSize,
                right: rect.right - e.clientX <= edgeSize,
                bottom: rect.bottom - e.clientY <= edgeSize,
                left: e.clientX - rect.left <= edgeSize,
            };
            return Object.values(edges).some(Boolean) ? edges : null;
        };
        const getResizeCursor = edges => {
            if (!edges) return "";
            if ((edges.top && edges.left) || (edges.bottom && edges.right)) return "nwse-resize";
            if ((edges.top && edges.right) || (edges.bottom && edges.left)) return "nesw-resize";
            if (edges.top || edges.bottom) return "ns-resize";
            if (edges.left || edges.right) return "ew-resize";
            return "";
        };
        const setResizeCursor = e => {
            if (win.classList.contains("pz-settings-preview-resizing")) return;
            if (e.target?.closest?.(".pz-settings-preview-button, .pz-settings-preview-background, .pz-settings-preview-two-cards-control")) {
                win.style.cursor = "";
                handle.style.cursor = "";
                return;
            }
            if (e.target?.closest?.("[data-pz-preview-handle]")) {
                win.style.cursor = "";
                handle.style.cursor = "";
                return;
            }
            const cursor = getResizeCursor(getResizeEdges(e));
            win.style.cursor = cursor;
            handle.style.cursor = cursor || "";
        };
        const clearResizeCursor = () => {
            if (win.classList.contains("pz-settings-preview-resizing")) return;
            win.style.cursor = "";
            handle.style.cursor = "";
        };

        const setPosition = (left, top) => {
            if (previewDocked) return;
            const next = clampToViewport(left, top);
            win.style.left = `${next.left}px`;
            win.style.top = `${next.top}px`;
            win.style.right = "auto";
            win.style.bottom = "auto";
        };

        const keepInViewport = () => {
            if (previewClosed) return;
            if (previewDocked) {
                if (previewDockSide === "right") {
                    applyRightDockedRect(getWindowRect().width);
                } else {
                    applyDockedRect(getWindowRect().height);
                }
                return;
            }
            const bounds = getViewportBounds();
            const rect = win.getBoundingClientRect();
            const maxWidth = Math.max(1, bounds.maxRight - bounds.minLeft);
            const maxHeight = Math.max(1, bounds.maxBottom - bounds.minTop);
            if (rect.width > maxWidth) win.style.width = `${Math.round(maxWidth)}px`;
            if (rect.height > maxHeight) {
                win.style.height = `${Math.round(maxHeight)}px`;
                win.style.maxHeight = "none";
            }
            setPosition(rect.left, rect.top);
        };

        const applyFloatingRect = rect => {
            const next = rect || floatingPreviewRect;
            previewDocked = false;
            previewDockSide = null;
            win.classList.remove("pz-settings-preview-docked", "pz-settings-preview-docked-right");
            setDockedScrollSpace(0);
            setRightDockedScrollSpace(0);
            updateModeButton();
            if (!next) {
                keepInViewport();
                syncPreviewState();
                return;
            }

            const bounds = getViewportBounds();
            const width = Math.min(Math.max(minPreviewWidth, next.width), Math.max(minPreviewWidth, bounds.maxRight - bounds.minLeft));
            const height = Math.min(Math.max(minPreviewHeight, next.height), Math.max(minPreviewHeight, bounds.maxBottom - bounds.minTop));
            win.style.width = `${Math.round(width)}px`;
            win.style.height = `${Math.round(height)}px`;
            win.style.maxHeight = "none";
            setPosition(next.left, next.top);
            floatingPreviewRect = getWindowRect();
            syncPreviewState();
        };
        const applyDockedRect = height => {
            const bounds = getViewportBounds();
            const dockLeft = getDockLeft();
            const dockedMinHeight = getDockedMinHeight();
            const maxHeight = Math.max(dockedMinHeight, window.innerHeight - bounds.minTop);
            const nextHeight = Math.min(maxHeight, Math.max(dockedMinHeight, height || getWindowRect().height));

            previewDocked = true;
            previewDockSide = "bottom";
            win.classList.add("pz-settings-preview-docked");
            win.classList.remove("pz-settings-preview-docked-right");
            setRightDockedScrollSpace(0);
            updateModeButton();
            win.style.left = `${Math.round(dockLeft)}px`;
            win.style.top = `${Math.round(window.innerHeight - nextHeight)}px`;
            win.style.right = "auto";
            win.style.bottom = "auto";
            win.style.width = `${Math.round(Math.max(minPreviewWidth, getViewportClientRight() - dockLeft))}px`;
            win.style.height = `${Math.round(nextHeight)}px`;
            win.style.maxHeight = "none";
            setDockedScrollSpace(nextHeight);
            syncPreviewState();
        };
        const applyRightDockedRect = width => {
            const bounds = getViewportBounds();
            const maxWidth = Math.max(minPreviewWidth, getViewportClientRight() - bounds.minLeft);
            const nextWidth = Math.min(maxWidth, Math.max(minPreviewWidth, width || getWindowRect().width));

            previewDocked = true;
            previewDockSide = "right";
            win.classList.add("pz-settings-preview-docked", "pz-settings-preview-docked-right");
            updateModeButton();
            win.style.left = `${Math.round(getViewportClientRight() - nextWidth)}px`;
            win.style.top = `${Math.round(bounds.minTop)}px`;
            win.style.right = "auto";
            win.style.bottom = "auto";
            win.style.width = `${Math.round(nextWidth)}px`;
            win.style.height = `${Math.round(window.innerHeight - bounds.minTop)}px`;
            win.style.maxHeight = "none";
            setDockedScrollSpace(0);
            setRightDockedScrollSpace(nextWidth);
            syncPreviewState();
        };
        const animatePreviewWindow = () => {
            win.classList.add("pz-settings-preview-animating");
            window.setTimeout(() => {
                win.classList.remove("pz-settings-preview-animating");
            }, 150);
        };
        const toggleDockedPreview = () => {
            animatePreviewWindow();
            if (previewDocked) {
                syncPreviewState();
                const stored = readStoredState() || {};
                const windowRect = {
                    left: readStateNumber(stored, "window-left"),
                    top: readStateNumber(stored, "window-top"),
                    width: readStateNumber(stored, "window-width"),
                    height: readStateNumber(stored, "window-height"),
                };
                const hasWindowRect = Object.values(windowRect).every(value => value !== null);
                applyFloatingRect(hasWindowRect ? windowRect : floatingPreviewRect);
                persistPreviewState();
                return;
            }

            floatingPreviewRect = getWindowRect();
            syncPreviewState();
            const stored = readStoredState() || {};
            const dockedHeight = readStateNumber(stored, "docked-height")
                ?? readStateNumber(stored, "preview-docked-height")
                ?? floatingPreviewRect.height;
            applyDockedRect(dockedHeight);
            persistPreviewState();
        };
        const cyclePreviewMode = () => {
            animatePreviewWindow();
            syncPreviewState();
            const stored = readStoredState() || {};

            if (!previewDocked) {
                floatingPreviewRect = getWindowRect();
                const dockedHeight = readStateNumber(stored, "docked-height")
                    ?? readStateNumber(stored, "preview-docked-height")
                    ?? floatingPreviewRect.height;
                applyDockedRect(dockedHeight);
            } else if (previewDockSide === "bottom") {
                const dockedWidth = readStateNumber(stored, "right-docked-width")
                    ?? readStateNumber(stored, "preview-right-docked-width")
                    ?? floatingPreviewRect?.width
                    ?? getWindowRect().width;
                applyRightDockedRect(dockedWidth);
            } else {
                const bounds = getViewportBounds();
                const windowRect = {
                    left: readStateNumber(stored, "window-left"),
                    top: readStateNumber(stored, "window-top"),
                    width: Math.max(minPreviewWidth, (bounds.maxRight - bounds.minLeft) * 0.7),
                    height: Math.max(minPreviewHeight, (bounds.maxBottom - bounds.minTop) * 0.7),
                };
                const hasWindowPosition = windowRect.left !== null && windowRect.top !== null;
                applyFloatingRect(hasWindowPosition ? windowRect : {
                    left: floatingPreviewRect?.left ?? bounds.minLeft + (bounds.maxRight - bounds.minLeft) * 0.15,
                    top: floatingPreviewRect?.top ?? bounds.minTop + (bounds.maxBottom - bounds.minTop) * 0.15,
                    width: windowRect.width,
                    height: windowRect.height,
                });
            }
            persistPreviewState();
        };
        const restorePreviewState = () => {
            const stored = readStoredState() || {};
            const storedMode = stored["preview-mode"] || readStateInput("preview-mode");
            const mode = storedMode === "icon" ? (stored["preview-restore-mode"] || "window") : storedMode;
            const initialFloatingRect = !previewDocked ? getWindowRect() : null;
            const windowRect = {
                left: readStateNumber(stored, "window-left"),
                top: readStateNumber(stored, "window-top"),
                width: readStateNumber(stored, "window-width"),
                height: readStateNumber(stored, "window-height"),
            };
            const dockLeft = getDockLeft();
            const dockWidth = getViewportClientRight() - dockLeft;
            const isStoredDockRect = windowRect.left !== null
                && windowRect.width !== null
                && Math.abs(windowRect.left - dockLeft) <= 1
                && windowRect.width >= dockWidth - 1;
            const hasWindowRect = Object.values(windowRect).every(value => value !== null) && !isStoredDockRect;
            if (hasWindowRect) {
                floatingPreviewRect = windowRect;
            } else if (initialFloatingRect?.width > 0 && initialFloatingRect?.height > 0) {
                floatingPreviewRect = initialFloatingRect;
            }

            if (mode === "docked") {
                const dockedHeight = readStateNumber(stored, "docked-height")
                    ?? readStateNumber(stored, "preview-docked-height")
                    ?? readStateNumber(stored, "preview-height");
                applyDockedRect(dockedHeight);
                return;
            }
            if (mode === "right") {
                const dockedWidth = readStateNumber(stored, "right-docked-width")
                    ?? readStateNumber(stored, "preview-right-docked-width")
                    ?? readStateNumber(stored, "preview-width");
                applyRightDockedRect(dockedWidth);
                return;
            }
            if (hasWindowRect) {
                applyFloatingRect(windowRect);
                return;
            }
            const fallbackRect = {
                left: readStateNumber(stored, "preview-left"),
                top: readStateNumber(stored, "preview-top"),
                width: readStateNumber(stored, "preview-width"),
                height: readStateNumber(stored, "preview-height"),
            };
            if (Object.values(fallbackRect).every(value => value !== null)) {
                applyFloatingRect(fallbackRect);
                return;
            }
            keepInViewport();
            syncPreviewState();
        };

        const dockFloatingDragAtEdge = (upEvent, offsetX, offsetY) => {
            if (upEvent.type !== "pointerup") return false;
            const rect = getWindowRect();
            const dockThreshold = 64;
            const pushedRight = upEvent.clientX - offsetX + rect.width - window.innerWidth;
            const pushedBottom = upEvent.clientY - offsetY + rect.height - window.innerHeight;
            const dockRight = pushedRight >= dockThreshold && pushedRight >= pushedBottom;
            const dockBottom = pushedBottom >= dockThreshold && pushedBottom > pushedRight;
            if (!dockRight && !dockBottom) return false;

            const stored = readStoredState() || {};
            floatingPreviewRect = rect;
            animatePreviewWindow();
            if (dockRight) {
                const dockedWidth = readStateNumber(stored, "right-docked-width")
                    ?? readStateNumber(stored, "preview-right-docked-width")
                    ?? rect.width;
                applyRightDockedRect(dockedWidth);
            } else {
                const dockedHeight = readStateNumber(stored, "docked-height")
                    ?? readStateNumber(stored, "preview-docked-height")
                    ?? rect.height;
                applyDockedRect(dockedHeight);
            }
            persistPreviewState();
            return true;
        };

        win.addEventListener("pointermove", setResizeCursor);
        win.addEventListener("pointerleave", clearResizeCursor);
        win.addEventListener("pointerdown", e => {
            if (e.button !== undefined && e.button !== 0) return;
            if (e.target?.closest?.(".pz-settings-preview-button, .pz-settings-preview-background, .pz-settings-preview-two-cards-control")) return;
            if (e.target?.closest?.("[data-pz-preview-handle]")) return;
            const edges = getResizeEdges(e);
            if (!edges) return;

            const startRect = win.getBoundingClientRect();
            const start = {
                left: startRect.left,
                top: startRect.top,
                right: startRect.right,
                bottom: startRect.bottom,
                width: startRect.width,
                height: startRect.height,
            };
            const startedRightDocked = previewDockSide === "right";
            let undockedFromRight = false;
            let dragOffsetX = 0;
            let dragOffsetY = 0;

            win.setPointerCapture?.(e.pointerId);
            win.classList.add("pz-settings-preview-resizing");
            win.style.cursor = getResizeCursor(edges);
            handle.style.cursor = win.style.cursor;
            e.preventDefault();
            e.stopPropagation();

            const move = moveEvent => {
                if (startedRightDocked && !undockedFromRight && Math.abs(moveEvent.clientY - e.clientY) >= 64) {
                    applyFloatingRect(floatingPreviewRect);
                    const floatingRect = getWindowRect();
                    dragOffsetX = floatingRect.width / 2;
                    dragOffsetY = Math.min(floatingRect.height, handle.getBoundingClientRect().height / 2);
                    undockedFromRight = true;
                    win.classList.remove("pz-settings-preview-resizing");
                    win.classList.add("pz-settings-preview-dragging");
                    win.style.cursor = "move";
                    handle.style.cursor = "move";
                    animatePreviewWindow();
                    setPosition(moveEvent.clientX - dragOffsetX, moveEvent.clientY - dragOffsetY);
                    return;
                }
                if (undockedFromRight) {
                    setPosition(moveEvent.clientX - dragOffsetX, moveEvent.clientY - dragOffsetY);
                    return;
                }
                if (previewDocked) {
                    if (previewDockSide === "right") {
                        applyRightDockedRect(start.width + e.clientX - moveEvent.clientX);
                    } else {
                        applyDockedRect(start.height + start.top - moveEvent.clientY);
                    }
                    return;
                }

                const bounds = getViewportBounds();
                let left = start.left;
                let top = start.top;
                let width = start.width;
                let height = start.height;

                if (edges.left) {
                    const minWidth = Math.min(minPreviewWidth, start.right - bounds.minLeft);
                    left = Math.min(start.right - minWidth, Math.max(bounds.minLeft, moveEvent.clientX));
                    width = start.right - left;
                }
                if (edges.right) {
                    const minWidth = Math.min(minPreviewWidth, bounds.maxRight - left);
                    width = Math.min(bounds.maxRight - left, Math.max(minWidth, moveEvent.clientX - left));
                }
                if (edges.top) {
                    const minHeight = Math.min(minPreviewHeight, start.bottom - bounds.minTop);
                    top = Math.min(start.bottom - minHeight, Math.max(bounds.minTop, moveEvent.clientY));
                    height = start.bottom - top;
                }
                if (edges.bottom) {
                    const minHeight = Math.min(minPreviewHeight, bounds.maxBottom - top);
                    height = Math.min(bounds.maxBottom - top, Math.max(minHeight, moveEvent.clientY - top));
                }

                win.style.left = `${Math.round(left)}px`;
                win.style.top = `${Math.round(top)}px`;
                win.style.width = `${Math.round(width)}px`;
                win.style.height = `${Math.round(height)}px`;
                win.style.right = "auto";
                win.style.bottom = "auto";
                win.style.maxHeight = "none";
            };
            const up = upEvent => {
                win.classList.remove("pz-settings-preview-resizing", "pz-settings-preview-dragging");
                win.releasePointerCapture?.(upEvent.pointerId);
                clearResizeCursor();
                const snappedToEdge = undockedFromRight
                    && dockFloatingDragAtEdge(upEvent, dragOffsetX, dragOffsetY);
                if (!snappedToEdge) {
                    if (!previewDocked) floatingPreviewRect = getWindowRect();
                    syncPreviewState();
                    persistPreviewState();
                }
                window.removeEventListener("pointermove", move);
                window.removeEventListener("pointerup", up);
                window.removeEventListener("pointercancel", up);
            };

            window.addEventListener("pointermove", move);
            window.addEventListener("pointerup", up);
            window.addEventListener("pointercancel", up);
        }, true);

        handle.addEventListener("pointerdown", e => {
            if (e.button !== undefined && e.button !== 0) return;
            if (e.target?.closest?.(".pz-settings-preview-button, .pz-settings-preview-background, .pz-settings-preview-two-cards-control")) return;

            const now = Date.now();
            const distance = Math.hypot(e.clientX - lastHandleClick.x, e.clientY - lastHandleClick.y);
            const isDoubleClick = now - lastHandleClick.time < 400 && distance < 8;
            lastHandleClick = { time: now, x: e.clientX, y: e.clientY };

            if (e.detail >= 2 || isDoubleClick) {
                e.preventDefault();
                suppressHandleDblClick = true;
                cyclePreviewMode();
                return;
            }

            const rect = win.getBoundingClientRect();
            let offsetX = e.clientX - rect.left;
            let offsetY = e.clientY - rect.top;
            const startedBottomDocked = previewDockSide === "bottom";
            const startedRightDocked = previewDockSide === "right";
            let undockedFromBottom = false;
            let undockedFromRight = false;

            handle.setPointerCapture?.(e.pointerId);
            win.classList.add("pz-settings-preview-dragging");
            e.preventDefault();

            const move = moveEvent => {
                if (startedBottomDocked && !undockedFromBottom && Math.abs(moveEvent.clientX - e.clientX) >= 64) {
                    applyFloatingRect(floatingPreviewRect);
                    const floatingRect = getWindowRect();
                    offsetX = floatingRect.width / 2;
                    offsetY = Math.min(floatingRect.height, handle.getBoundingClientRect().height / 2);
                    undockedFromBottom = true;
                    animatePreviewWindow();
                    setPosition(moveEvent.clientX - offsetX, moveEvent.clientY - offsetY);
                    return;
                }
                if (startedRightDocked && !undockedFromRight && Math.hypot(moveEvent.clientX - e.clientX, moveEvent.clientY - e.clientY) >= 4) {
                    applyFloatingRect(floatingPreviewRect);
                    const floatingRect = getWindowRect();
                    offsetX = Math.min(floatingRect.width, Math.max(0, e.clientX - rect.left));
                    offsetY = Math.min(floatingRect.height, handle.getBoundingClientRect().height / 2);
                    undockedFromRight = true;
                    animatePreviewWindow();
                    setPosition(moveEvent.clientX - offsetX, moveEvent.clientY - offsetY);
                    return;
                }
                if (startedRightDocked && !undockedFromRight) return;
                if (previewDocked) {
                    if (previewDockSide === "right") {
                        applyRightDockedRect(rect.width + e.clientX - moveEvent.clientX);
                        return;
                    }
                    applyDockedRect(rect.height + e.clientY - moveEvent.clientY);
                    return;
                }
                setPosition(moveEvent.clientX - offsetX, moveEvent.clientY - offsetY);
            };
            const up = upEvent => {
                win.classList.remove("pz-settings-preview-dragging");
                handle.releasePointerCapture?.(upEvent.pointerId);
                const undockedFromDock = undockedFromBottom || undockedFromRight;
                let snappedToEdge = undockedFromDock
                    && dockFloatingDragAtEdge(upEvent, offsetX, offsetY);
                if (!previewDocked && !undockedFromDock && upEvent.type === "pointerup") {
                    const dockThreshold = 64;
                    const pushedRight = upEvent.clientX - offsetX + rect.width - window.innerWidth;
                    const pushedBottom = upEvent.clientY - offsetY + rect.height - window.innerHeight;
                    const dockRight = pushedRight >= dockThreshold && pushedRight >= pushedBottom;
                    const dockBottom = pushedBottom >= dockThreshold && pushedBottom > pushedRight;
                    if (dockRight || dockBottom) {
                        const stored = readStoredState() || {};
                        applyFloatingRect({ left: rect.left, top: rect.top, width: rect.width, height: rect.height });
                        animatePreviewWindow();
                        if (dockRight) {
                            const dockedWidth = readStateNumber(stored, "right-docked-width")
                                ?? readStateNumber(stored, "preview-right-docked-width")
                                ?? rect.width;
                            applyRightDockedRect(dockedWidth);
                        } else {
                            const dockedHeight = readStateNumber(stored, "docked-height")
                                ?? readStateNumber(stored, "preview-docked-height")
                                ?? rect.height;
                            applyDockedRect(dockedHeight);
                        }
                        persistPreviewState();
                        snappedToEdge = true;
                    }
                }
                if (!snappedToEdge) {
                    if (!previewDocked) floatingPreviewRect = getWindowRect();
                    syncPreviewState();
                    persistPreviewState();
                }
                window.removeEventListener("pointermove", move);
                window.removeEventListener("pointerup", up);
                window.removeEventListener("pointercancel", up);
            };

            window.addEventListener("pointermove", move);
            window.addEventListener("pointerup", up);
            window.addEventListener("pointercancel", up);
        });
        handle.addEventListener("dblclick", e => {
            if (e.target?.closest?.(".pz-settings-preview-button, .pz-settings-preview-background, .pz-settings-preview-two-cards-control")) return;
            e.preventDefault();
            if (suppressHandleDblClick) {
                suppressHandleDblClick = false;
                return;
            }
            cyclePreviewMode();
        });
        modeButton?.addEventListener("click", e => {
            e.preventDefault();
            e.stopPropagation();
            toggleDockedPreview();
            modeButton.blur();
        });
        closeButton?.addEventListener("click", e => {
            e.preventDefault();
            e.stopPropagation();
            if (!previewDocked) floatingPreviewRect = getWindowRect();
            syncPreviewState();
            persistPreviewState();
            saveIconState();
            showIconPreview();
            closeButton.blur();
        });
        twoCardsCheckbox?.addEventListener("change", () => {
            applyTwoCards(twoCardsCheckbox.checked);
            syncPreviewState();
            persistPreviewState();
        });
        restoreButton.addEventListener("click", e => {
            e.preventDefault();
            hideRestoreButton();
            previewClosed = false;
            win.style.display = "";
            restorePreviewState();
            showPreviewWindow();
            win.focus?.();
        });

        window.addEventListener("resize", keepInViewport);
        applyTwoCards(readStateFlag(readStoredState() || {}, "preview-two-cards"));
        updateModeButton();
        if ((readStoredState() || {})["preview-mode"] === "icon" || readStateInput("preview-mode") === "icon") {
            showIconPreview();
        } else if (readStoredState() || readStateInput("preview-mode")) {
            restorePreviewState();
            showPreviewWindow();
        } else {
            saveIconState();
            showIconPreview();
        }
        initSettingsPreviewLiveStyles(win);
    }

    function initSettingsPreviewLiveStyles(win) {
        const form = document.querySelector(".pz-settings form");
        if (!form) return;

        const labels = (typeof pzLinkCardPreview !== "undefined" && pzLinkCardPreview.labels) || {};
        let previewCssTimer = null;
        let previewCssRequestSeq = 0;
        const isControlDisabled = el => {
            if (!el) return false;
            if (el.disabled || el.classList.contains("pz-disabled")) return true;
            if (el.closest(".pz-card-prop-switch")) return false;
            return el.readOnly ||
                el.getAttribute("aria-disabled") === "true" ||
                el.closest(".pz-card-prop-disabled") !== null;
        };
        const getPreviewStyleElement = () => {
            let styleEl = document.getElementById("pz-linkcard-preview-css");
            if (!styleEl) {
                styleEl = document.createElement("style");
                styleEl.id = "pz-linkcard-preview-css";
                document.head.appendChild(styleEl);
            }
            return styleEl;
        };
        const getPreviewHoverStyleElement = () => {
            let styleEl = document.getElementById("pz-linkcard-preview-hover-css");
            if (!styleEl) {
                styleEl = document.createElement("style");
                styleEl.id = "pz-linkcard-preview-hover-css";
                document.head.appendChild(styleEl);
            }
            return styleEl;
        };
        const getPreviewHoverCss = () => {
            const items = [
                ["title", ".lkc-title"],
                ["excerpt", ".lkc-excerpt"],
                ["url", ".lkc-url, .lkc-url-info"],
                ["date", ".lkc-date"],
                ["heading", ".lkc-heading"],
                ["more", ".lkc-more"],
                ["info", ".lkc-info"],
                ["added", ".lkc-added"],
            ];
            return ["ex", "in"].flatMap(prefix => items.map(([name, selector]) => {
                const decoration = value(`${prefix}-${name}-hover`) !== "" ? "underline" : "none";
                return `.pz-settings-preview-window [data-pz-preview-card="${prefix}"] ${selector}:hover { text-decoration: ${decoration} !important; }`;
            })).join("\n");
        };
        const collectAllProperties = () => {
            const fd = new FormData();
            const values = new Map();
            form.querySelectorAll("input[name^='properties['], select[name^='properties['], textarea[name^='properties[']").forEach(el => {
                if (isControlDisabled(el)) {
                    values.set(el.name, "");
                    return;
                }
                if (el.type === "checkbox" || el.type === "radio") {
                    if (el.type === "checkbox") values.set(el.name, el.checked ? el.value : "");
                    if (el.type === "radio" && el.checked) values.set(el.name, el.value);
                    return;
                }
                values.set(el.name, el.value ?? "");
            });
            values.forEach((value, name) => {
                fd.append(name, value);
            });
            return fd;
        };
        const kickPreviewCssCallback = async () => {
            if (typeof pzLinkCardPreview === "undefined" || !pzLinkCardPreview.ajaxUrl) return;

            const seq = ++previewCssRequestSeq;
            const fd = collectAllProperties();
            fd.append("action", pzLinkCardPreview.action || "pz_lkc_preview_render");
            fd.append("nonce", pzLinkCardPreview.nonce || "");

            try {
                const response = await fetch(pzLinkCardPreview.ajaxUrl, { method: "POST", body: fd });
                const json = await response.json();
                if (seq !== previewCssRequestSeq || !json?.success) return;
                getPreviewStyleElement().textContent = json.data?.css || "";
            } catch (err) {
                console.warn("Pz-LinkCard preview CSS failed:", err);
            }
        };
        const schedulePreviewCssCallback = (delay = 120) => {
            if (previewCssTimer) clearTimeout(previewCssTimer);
            previewCssTimer = setTimeout(kickPreviewCssCallback, delay);
        };

        const findControl = name => {
            const controls = Array.from(form.querySelectorAll(`[name="properties[${name}]"]`));
            return controls.find(el => el.type !== "hidden" && !isControlDisabled(el)) ||
                controls.find(el => el.type !== "hidden") ||
                controls[0] ||
                null;
        };
        const value = name => {
            const control = findControl(name);
            if (!control || isControlDisabled(control)) return "";
            if (control.type === "checkbox" || control.type === "radio") return control.checked ? control.value : "";
            return control.value ?? "";
        };
        const checked = name => value(name) !== "";
        const intValue = (name, fallback = 0) => {
            const parsed = parseInt(value(name), 10);
            return Number.isFinite(parsed) ? parsed : fallback;
        };
        const cssSize = name => {
            const raw = value(name);
            if (raw === "" || raw === null || raw === undefined) return "";
            return /^-?\d+(\.\d+)?$/.test(String(raw)) ? `${raw}px` : String(raw);
        };
        const cssBackgroundImage = name => {
            const raw = String(value(name) || "").trim();
            if (!raw) return "";
            if (/^url\(/i.test(raw)) return raw;
            if (/^https?:\/\//i.test(raw)) return `url("${raw}")`;
            return raw;
        };
        const applyDisplay = (node, show) => {
            if (!node) return;
            if (show) {
                node.style.removeProperty("display");
            } else {
                node.style.setProperty("display", "none", "important");
            }
        };
        const resetStyle = (node, props) => {
            if (!node) return;
            props.forEach(prop => node.style[prop] = "");
        };
        const resetCardOrder = card => {
            const info = card.querySelector("[data-pz-preview-info]");
            const content = card.querySelector("[data-pz-preview-content]");
            const cardBody = card.querySelector(".lkc-card");
            const title = card.querySelector(".lkc-title");
            const thumbnail = card.querySelector(".lkc-thumbnail");
            if (info && content && cardBody && info.parentElement !== cardBody) {
                cardBody.insertBefore(info, content);
            }
            if (thumbnail && title && thumbnail.parentElement) {
                thumbnail.parentElement.insertBefore(thumbnail, title);
            }
        };
        const ensureShareNode = (share, selector, className, text) => {
            let node = share.querySelector(selector);
            if (!node) {
                node = document.createElement("span");
                share.appendChild(node);
            }
            node.className = className;
            node.textContent = text;
            return node;
        };
        const syncShare = (card, content, info, title, infoUrl) => {
            const position = value("sns-position");
            const enabled = position !== "" && (checked("sns-tw") || checked("sns-fb") || checked("sns-hb"));
            let share = card.querySelector(".lkc-share");
            if (!share && enabled) {
                share = document.createElement("div");
                share.className = "lkc-share";
            }
            if (!share) return;

            const twText = checked("sns-tw-x") ? "1234 tweets" : "1234 posts";
            const tw = ensureShareNode(share, ".lkc-sns-tw, .lkc-sns-x", checked("sns-tw-x") ? "lkc-sns-tw no_icon" : "lkc-sns-x no_icon", twText);
            const fb = ensureShareNode(share, ".lkc-sns-fb", "lkc-sns-fb no_icon", "1234 shares");
            const hb = ensureShareNode(share, ".lkc-sns-hb", "lkc-sns-hb no_icon", "1234 users");
            applyDisplay(tw, enabled && checked("sns-tw"));
            applyDisplay(fb, enabled && checked("sns-fb"));
            applyDisplay(hb, enabled && checked("sns-hb"));
            applyDisplay(share, enabled);
            if (!enabled) return;

            if (position === "1" && content && title) {
                const titleContainer = title.parentElement && title.parentElement.parentElement === content ? title.parentElement : title;
                content.insertBefore(share, titleContainer.parentElement === content ? titleContainer.nextSibling : null);
            } else if (info) {
                info.insertBefore(share, infoUrl && infoUrl.parentElement === info ? infoUrl : null);
            }
        };
        const previewUrlText = (card, fallbackPrefix) => {
            const current = card.querySelector(".lkc-url, .lkc-url-info")?.textContent?.trim();
            if (current) return current;
            const href = card.querySelector("a.lkc-link")?.getAttribute("href");
            if (href) return href;
            return fallbackPrefix === "in" ? "/" : "https://example.com/pz-linkcard-preview";
        };
        const titleInsertReference = (content, title) => {
            if (!content || !title) return null;
            const titleContainer = title.parentElement && title.parentElement.parentElement === content ? title.parentElement : title;
            return titleContainer.parentElement === content ? titleContainer.nextSibling : null;
        };
        const textStyle = (selector, prefix) => {
            win.querySelectorAll(selector).forEach(node => {
                resetStyle(node, ["color", "background", "backgroundColor", "padding", "fontSize", "lineHeight", "fontWeight", "fontStyle", "textDecoration", "maxHeight", "webkitLineClamp"]);
                const color = value(`${prefix}-color`);
                const bg = value(`${prefix}-bg-color`);
                if (color) node.style.color = color;
                if (bg) {
                    node.style.padding = "4px";
                    node.style.backgroundColor = bg;
                }
                const size = cssSize(`${prefix}-size`);
                const height = cssSize(`${prefix}-height`);
                if (size) node.style.fontSize = size;
                if (height) node.style.lineHeight = height;
                node.style.fontWeight = checked(`${prefix}-bold`) ? "bold" : "normal";
                node.style.fontStyle = checked(`${prefix}-italic`) ? "italic" : "normal";
                node.style.textDecoration = checked(`${prefix}-underline`) ? "underline" : "none";
                const maxLine = intValue(`${prefix}-maxline`, 0);
                if (maxLine > 0) node.style.webkitLineClamp = String(maxLine);
            });
        };
        const bindHoverTextStyle = (selector, prefix) => {
            win.querySelectorAll(selector).forEach(node => {
                if (!node.dataset.pzPreviewHoverBound) {
                    node.addEventListener("pointerenter", () => {
                        const decoration = checked(`${prefix}-hover`) ? "underline" : "none";
                        node.style.setProperty("text-decoration", decoration, "important");
                    });
                    node.addEventListener("pointerleave", () => {
                        const decoration = checked(`${prefix}-underline`) ? "underline" : "none";
                        node.style.setProperty("text-decoration", decoration, "important");
                    });
                    node.dataset.pzPreviewHoverBound = "1";
                }
                if (node.matches(":hover")) {
                    const decoration = checked(`${prefix}-hover`) ? "underline" : "none";
                    node.style.setProperty("text-decoration", decoration, "important");
                }
            });
        };
        const applyPartBox = (card, selector, prefix) => {
            const node = card.querySelector(selector);
            if (!node) return;
            resetStyle(node, ["transform", "background", "backgroundColor", "backgroundImage", "border", "borderColor", "borderStyle", "borderWidth", "borderRadius", "boxShadow", "opacity", "transition"]);
            if (checked(`${prefix}-transform-enabled`)) {
                const transformX = intValue(`${prefix}-transform-x`, 0);
                const transformY = intValue(`${prefix}-transform-y`, 0);
                const transformRotate = intValue(`${prefix}-transform-rotate`, 0);
                const transformScale = intValue(`${prefix}-transform-scale`, 100);
                if (transformX || transformY || transformRotate || transformScale !== 100) {
                    node.style.transform = `translate(${transformX}px, ${transformY}px) rotate(${transformRotate}deg) scale(${transformScale / 100})`;
                }
            }
            const ignoreLinkBackground = value("special-format") === "JIN" && (prefix === "ex" || prefix === "in");
            if (!ignoreLinkBackground) {
                const bgColor = value(`${prefix}-bg-color`);
                const bgImage = cssBackgroundImage(`${prefix}-bg-image`);
                if (checked(`${prefix}-bg-enabled`) && bgColor) {
                    node.style.backgroundColor = bgColor;
                }
                if (checked(`${prefix}-bg-enabled`) && bgImage) {
                    node.style.backgroundImage = bgImage;
                }
            }
            if (checked(`${prefix}-border-enabled`)) {
                const borderColor = value(`${prefix}-border-color`);
                const borderStyle = value(`${prefix}-border-style`) || "solid";
                const borderWidth = intValue(`${prefix}-border-width`, 1);
                const borderRadius = intValue(`${prefix}-border-radius`, 0);
                if (borderStyle) {
                    node.style.setProperty("border", `${borderColor ? `${borderColor} ` : ""}${borderStyle} ${borderWidth}px`, "important");
                }
                if (borderRadius > 0) {
                    node.style.borderRadius = `${borderRadius}px`;
                }
            }
            if (checked(`${prefix}-shadow-enabled`)) {
                const shadowColor = value(`${prefix}-shadow-color`) || "rgba(0,0,0,0.3)";
                const shadowX = intValue(`${prefix}-shadow-x`, 8);
                const shadowY = intValue(`${prefix}-shadow-y`, 8);
                const shadowBlur = intValue(`${prefix}-shadow-blur`, 8);
                const shadowSpread = intValue(`${prefix}-shadow-spread`, 0);
                const shadowInset = checked(`${prefix}-shadow-inset`) ? "inset " : "";
                node.style.boxShadow = `${shadowInset}${shadowX}px ${shadowY}px ${shadowBlur}px ${shadowSpread}px ${shadowColor}`;
            }
        };
        const applyHeadlinePreset = () => {
            win.querySelectorAll("[data-pz-preview-card]").forEach(card => {
                const prefix = card.dataset.pzPreviewCard;
                if (prefix !== "ex" && prefix !== "in") return;

                const borderColor = value(`${prefix}-border-color`);
                const wrap = card.querySelector(".lkc-external-wrap, .lkc-internal-wrap");
                const cardBody = card.querySelector(".lkc-card");
                const heading = card.querySelector("[data-pz-preview-heading]");

                card.style.margin = "24px auto 30px auto";
                card.style.paddingLeft = "";
                card.style.paddingRight = "";
                card.querySelectorAll("p").forEach(node => {
                    node.style.setProperty("display", "none", "important");
                });

                if (wrap) {
                    wrap.style.margin = "0 auto";
                    wrap.style.background = "";
                    wrap.style.backgroundColor = "";
                    wrap.style.backgroundImage = "";
                    wrap.style.setProperty("border", `solid ${borderColor || ""} 4px`);
                }
                if (cardBody) {
                    cardBody.style.setProperty("margin", "24px 20px 20px 20px", "important");
                }
                if (heading && heading.textContent.trim() !== "") {
                    heading.style.setProperty("padding", "0 10px", "important");
                    heading.style.position = "absolute";
                    heading.style.top = "-15px";
                    heading.style.left = "20px";
                    heading.style.height = "20px";
                    heading.style.backgroundColor = borderColor;
                    heading.style.setProperty("border", `solid ${borderColor || ""} 4px`);
                }
            });
        };
        const applySpecialFormat = () => {
            if (value("special-format") === "JIN") {
                applyHeadlinePreset();
            }
            schedulePreviewCssCallback();
        };
        const updatePreview = () => {
            const thumbnailPosition = value("thumbnail-position");
            const infoPosition = value("info-position");
            const displayUrl = value("display-url");
            const displayDate = value("display-date");
            const width = value("width");
            const widthUnit = value("width-unit") || "px";
            const contentHeight = intValue("content-height", 0);
            const thumbnailWidth = intValue("thumbnail-width", 100);
            const thumbnailHeight = intValue("thumbnail-height", 100);
            const specialFormat = value("special-format");
            const usePresetLayout = specialFormat === "sqr";

            win.querySelectorAll("[data-pz-preview-card]").forEach(card => {
                try {
                const prefix = card.dataset.pzPreviewCard;
                const wrap = card.querySelector(".lkc-external-wrap, .lkc-internal-wrap");
                const cardBody = card.querySelector(".lkc-card");
                const content = card.querySelector("[data-pz-preview-content]");
                const info = card.querySelector("[data-pz-preview-info]");
                const title = card.querySelector(".lkc-title");
                const heading = card.querySelector("[data-pz-preview-heading]");
                const more = card.querySelector("[data-pz-preview-more]");
                const thumbnail = card.querySelector(".lkc-thumbnail");
                const thumbnailImg = card.querySelector(".lkc-thumbnail-img");
                let url = card.querySelector(".lkc-url");
                let infoUrl = card.querySelector(".lkc-url-info");
                let date = card.querySelector(".lkc-date");
                const excerpt = card.querySelector(".lkc-excerpt");

                resetCardOrder(card);
                resetStyle(card, ["marginTop", "marginBottom", "paddingLeft", "paddingRight"]);
                resetStyle(wrap, ["maxWidth", "width", "height", "margin", "transform", "background", "backgroundColor", "backgroundImage", "border", "borderColor", "borderStyle", "borderWidth", "borderRadius", "boxShadow", "opacity", "transition"]);
                resetStyle(cardBody, ["marginTop", "marginBottom", "marginLeft", "marginRight", "padding", "border", "background", "backgroundColor", "boxShadow"]);
                resetStyle(content, ["height", "margin", "padding", "boxShadow", "background", "backgroundColor", "borderTop", "borderBottom"]);
                resetStyle(info, ["color", "background", "backgroundColor"]);
                resetStyle(thumbnail, ["display", "float", "width", "margin", "transform", "background", "backgroundColor", "backgroundImage", "border", "borderColor", "borderStyle", "borderWidth", "borderRadius", "boxShadow", "opacity", "transition"]);
                resetStyle(thumbnailImg, ["width", "height"]);

                card.style.marginTop = cssSize("margin-top") || "";
                card.style.marginBottom = cssSize("margin-bottom") || "";
                card.style.paddingLeft = cssSize("margin-left") || "";
                card.style.paddingRight = cssSize("margin-right") || "";

                if (wrap) {
                    if (width) {
                        if (widthUnit === "%") {
                            wrap.style.width = `${intValue("width", 100)}%`;
                        } else {
                            wrap.style.maxWidth = `${intValue("width", 500)}px`;
                        }
                    }
                    wrap.style.margin = checked("centering") ? "0 auto" : "0";
                    applyPartBox(card, ".lkc-external-wrap, .lkc-internal-wrap", prefix);
                }

                if (cardBody) {
                    cardBody.style.marginTop = cssSize("card-top") || "8px";
                    cardBody.style.marginBottom = cssSize("card-bottom") || "8px";
                    cardBody.style.marginLeft = cssSize("card-left") || "8px";
                    cardBody.style.marginRight = cssSize("card-right") || "8px";
                }

                if (!usePresetLayout && thumbnail && thumbnailImg) {
                    applyDisplay(thumbnail, thumbnailPosition !== "0");
                    if (thumbnailPosition === "1") {
                        thumbnail.style.float = "right";
                        thumbnail.style.width = `${thumbnailWidth + 2}px`;
                        thumbnail.style.margin = "0 0 0 8px";
                        thumbnailImg.style.width = `${thumbnailWidth}px`;
                        thumbnailImg.style.height = `${thumbnailHeight}px`;
                    } else if (thumbnailPosition === "2") {
                        thumbnail.style.float = "left";
                        thumbnail.style.width = `${thumbnailWidth + 2}px`;
                        thumbnail.style.margin = "0 8px 0 0";
                        thumbnailImg.style.width = `${thumbnailWidth}px`;
                        thumbnailImg.style.height = `${thumbnailHeight}px`;
                    } else if (thumbnailPosition === "3") {
                        thumbnail.style.display = "block";
                        thumbnail.style.margin = "0 0 8px 0";
                        thumbnailImg.style.width = "calc(100% - 2px)";
                        thumbnailImg.style.height = `${thumbnailHeight}px`;
                    }
                }

                if (content && !usePresetLayout) {
                    const totalHeight = thumbnailPosition === "3" ? contentHeight + thumbnailHeight : contentHeight;
                    if (totalHeight > 0) content.style.height = `${totalHeight}px`;
                    if (checked("content-inset")) {
                        content.style.padding = "6px";
                        content.style.boxShadow = "inset 4px 4px 4px rgba(255,255,255,1)";
                        content.style.backgroundColor = "rgba(255,255,255,0.8)";
                    }
                    content.style.margin = infoPosition === "1" ? "6px 0 0 0" : (infoPosition === "2" ? "0 0 8px 0" : "0");
                    if (checked("separator")) {
                        if (infoPosition === "1") content.style.borderTop = `1px solid ${value("info-color") || "#222"}`;
                        if (infoPosition === "2") content.style.borderBottom = `1px solid ${value("info-color") || "#222"}`;
                    }
                }

                applyDisplay(info, infoPosition !== "");
                if (info && content && title && cardBody) {
                    if (infoPosition === "2") {
                        cardBody.appendChild(info);
                    } else if (infoPosition === "3") {
                        content.insertBefore(info, title);
                    }
                }

                const showDate = prefix !== "ex" && displayDate !== "";
                const urlText = previewUrlText(card, prefix);
                if (!showDate && displayUrl === "1" && content && !url) {
                    url = document.createElement("div");
                    url.className = "lkc-url";
                    url.title = urlText;
                    url.textContent = urlText;
                    content.insertBefore(url, titleInsertReference(content, title));
                }
                if (!showDate && displayUrl === "2" && info && !infoUrl) {
                    infoUrl = document.createElement("div");
                    infoUrl.className = "lkc-url-info";
                    infoUrl.textContent = urlText;
                    info.appendChild(infoUrl);
                }
                if (prefix !== "ex" && showDate && content && !date) {
                    date = document.createElement("div");
                    date.className = "lkc-date";
                    content.insertBefore(date, excerpt || titleInsertReference(content, title));
                }
                if (date && showDate) {
                    const postDate = labels.previewPostDate || "2026/09/12";
                    const modifiedDate = labels.previewModifiedDate || postDate;
                    if (displayDate === "2") {
                        date.textContent = `\u{1f552}\ufe0f${modifiedDate}`;
                    } else if (displayDate === "3") {
                        date.textContent = `\u{1f552}\ufe0f${postDate}\u2002\u{1f501}\ufe0f${modifiedDate}`;
                    } else {
                        date.textContent = `\u{1f552}\ufe0f${postDate}`;
                    }
                }
                applyDisplay(date, showDate);
                applyDisplay(url, displayUrl === "1" && !showDate);
                applyDisplay(infoUrl, displayUrl === "2" && !showDate);
                syncShare(card, content, info, title, infoUrl);
                applyDisplay(excerpt, checked("display-excerpt"));

                if (heading) {
                    let headingText = value(`${prefix}-heading-text`);
                    if (value("special-format") === "JIN" && headingText === "") {
                        if (prefix === "ex") headingText = labels.referenced || "Referenced";
                        if (prefix === "in") headingText = labels.youMayAlsoLike || "You may also like";
                    }
                    heading.textContent = headingText;
                    const showHeading = heading.textContent !== "";
                    applyDisplay(heading, showHeading);
                    if (showHeading) {
                        applyPartBox(card, ".lkc-heading", `${prefix}-heading`);
                    }
                }
                if (more) {
                    more.textContent = value(`${prefix}-more-text`);
                    const showMore = more.textContent !== "";
                    applyDisplay(more, showMore);
                    if (showMore) {
                        applyPartBox(card, ".lkc-more", `${prefix}-more`);
                    }
                }
                const addedText = value(`${prefix}-added-text`);
                let added = info?.querySelector(".lkc-added");
                if (!addedText && added) {
                    added.remove();
                    added = null;
                }
                if (addedText && !added && info) {
                    added = document.createElement("div");
                    added.className = "lkc-added";
                    const domain = info.querySelector(".lkc-domain");
                    if (domain) {
                        domain.insertAdjacentElement("afterend", added);
                    } else {
                        info.insertBefore(added, info.querySelector(".lkc-share"));
                    }
                }
                if (added) {
                    added.textContent = addedText;
                    applyDisplay(added, true);
                }
                } catch (err) {
                    console.warn("Pz-LinkCard preview card failed:", card.dataset.pzPreviewCard || "", err);
                }
            });

            textStyle(".lkc-title", "title");
            textStyle(".lkc-url, .lkc-url-info", "url");
            textStyle(".lkc-excerpt", "excerpt");
            textStyle(".lkc-date", "date");
            textStyle(".lkc-info, .lkc-domain", "info");
            textStyle(".lkc-added", "added");
            textStyle(".lkc-heading", "heading");
            textStyle(".lkc-more", "more");
            bindHoverTextStyle(".lkc-title", "title");
            bindHoverTextStyle(".lkc-excerpt", "excerpt");
            bindHoverTextStyle(".lkc-url, .lkc-url-info", "url");
            bindHoverTextStyle(".lkc-date", "date");
            bindHoverTextStyle(".lkc-heading", "heading");
            bindHoverTextStyle(".lkc-more", "more");
            bindHoverTextStyle(".lkc-info", "info");
            bindHoverTextStyle(".lkc-added", "added");
            win.querySelectorAll("[data-pz-preview-heading], [data-pz-preview-more], .lkc-added").forEach(node => {
                if (node.textContent.trim() === "") {
                    node.style.setProperty("display", "none", "important");
                }
            });
            applySpecialFormat();
        };

        const syncPreview = () => {
            updatePreview();
            getPreviewHoverStyleElement().textContent = getPreviewHoverCss();
            schedulePreviewCssCallback();
        };

        form.addEventListener("input", syncPreview, true);
        form.addEventListener("change", syncPreview, true);
        const observer = new MutationObserver(mutations => {
            if (!mutations.some(m => m.type === "attributes")) return;
            syncPreview();
        });
        observer.observe(form, {
            subtree: true,
            attributes: true,
            attributeFilter: ["class", "disabled", "readonly", "aria-disabled"],
        });
        updatePreview();
        getPreviewHoverStyleElement().textContent = getPreviewHoverCss();
        schedulePreviewCssCallback(0);
    }

    initSettingsPreviewWindow();
});
