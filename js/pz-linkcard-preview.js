document.addEventListener("DOMContentLoaded", () => {
    const dashboard = document.querySelector(".pz-dashboard");
    if (!dashboard) return;

    function initSettingsPreviewWindow() {
        const win = document.querySelector(".pz-settings-preview-window");
        const handle = win?.querySelector("[data-pz-preview-handle]");
        const modeButton = win?.querySelector("[data-pz-preview-mode]");
        const secondaryModeButton = win?.querySelector("[data-pz-preview-mode-secondary]");
        const closeButton = win?.querySelector("[data-pz-preview-close]");
        const backgroundButtons = win?.querySelectorAll("[data-pz-preview-background]");
        const twoCardsCheckbox = win?.querySelector("[data-pz-preview-two-cards]");
        const twoCardsControl = twoCardsCheckbox?.closest(".pz-settings-preview-two-cards-control");
        if (!win || !handle) return;
        const form = document.querySelector(".pz-settings form");
        const storageKey = "pz-linkcard-preview-state";
        const labels = (typeof pzLinkCardPreview !== "undefined" && pzLinkCardPreview.labels) || {};
        const iconBase = closeButton?.querySelector("img")?.src;
        const setButtonIcon = (button, filename) => {
            if (!iconBase) return;
            const icon = document.createElement("img");
            icon.src = new URL(filename, iconBase).href;
            icon.width = 16;
            icon.height = 16;
            icon.alt = "";
            button.replaceChildren(icon);
        };
        const restoreButton = document.createElement("button");
        restoreButton.type = "button";
        restoreButton.className = "pz-settings-preview-restore";
        restoreButton.textContent = labels.restorePreview || "□Preview";
        restoreButton.setAttribute("aria-label", labels.restorePreviewAria || "Show preview");
        const previewTooltip = `${labels.restorePreviewAria || "Preview"} Alt+V`;
        restoreButton.title = previewTooltip;
        if (closeButton) closeButton.title = previewTooltip;
        restoreButton.hidden = true;
        document.body.appendChild(restoreButton);
        let previewFadeTimer = null;
        let previewClosed = false;
        let previewGeometryAnimation = null;
        let openingIconRect = null;
        const animateIconTransition = (iconRect, closing, finish = () => {}) => {
            previewGeometryAnimation?.cancel();
            previewGeometryAnimation = null;
            const rect = win.getBoundingClientRect();
            if (!iconRect || !rect.width || !rect.height || !win.animate
                || window.matchMedia?.("(prefers-reduced-motion: reduce)").matches) {
                finish();
                return;
            }
            const iconFrame = {
                transformOrigin: "0 0",
                transform: `translate(${iconRect.left - rect.left}px, ${iconRect.top - rect.top}px) scale(${iconRect.width / rect.width}, ${iconRect.height / rect.height})`,
                opacity: 0.15,
            };
            const windowFrame = { transformOrigin: "0 0", transform: "none", opacity: 1 };
            const animation = win.animate(closing ? [windowFrame, iconFrame] : [iconFrame, windowFrame], {
                duration: 220,
                easing: "cubic-bezier(0.22, 1, 0.36, 1)",
            });
            previewGeometryAnimation = animation;
            animation.onfinish = () => {
                if (previewGeometryAnimation !== animation) return;
                previewGeometryAnimation = null;
                finish();
                schedulePreviewFit();
            };
        };
        const showPreviewWindow = () => {
            previewClosed = false;
            previewGeometryAnimation?.cancel();
            previewGeometryAnimation = null;
            window.clearTimeout(previewFadeTimer);
            win.style.display = "";
            if (openingIconRect) {
                win.classList.add("pz-settings-preview-ready");
                animateIconTransition(openingIconRect, false);
                openingIconRect = null;
            }
            if (previewDocked) {
                win.classList.add("pz-settings-preview-ready");
            }
            window.requestAnimationFrame(() => {
                win.classList.add("pz-settings-preview-ready");
                schedulePreviewFit();
            });
        };
        const showRestoreButton = () => {
            restoreButton.hidden = false;
            window.requestAnimationFrame(() => {
                restoreButton.classList.add("pz-settings-preview-restore-ready");
            });
        };
        const hideRestoreButton = () => {
            if (!restoreButton.hidden) openingIconRect = restoreButton.getBoundingClientRect();
            previewGeometryAnimation?.cancel();
            previewGeometryAnimation = null;
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
            const wasVisible = !previewClosed && win.style.display !== "none"
                && win.classList.contains("pz-settings-preview-ready");
            previewClosed = true;
            window.clearTimeout(previewFadeTimer);
            showRestoreButton();
            const finish = () => {
                if (!previewClosed) return;
                win.classList.remove("pz-settings-preview-ready", "pz-settings-preview-docked", "pz-settings-preview-docked-right");
                win.style.display = "none";
            };
            if (wasVisible) {
                animateIconTransition(restoreButton.getBoundingClientRect(), true, finish);
            } else {
                finish();
            }
            setDockedScrollSpace(0);
            setRightDockedScrollSpace(0);
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
        const backgroundStorageKey = `${storageKey}-background:${window.location.pathname}`;
        const applyPreviewBackground = name => {
            if (!Object.prototype.hasOwnProperty.call(previewBackgrounds, name)) name = "white";
            const background = previewBackgrounds[name];
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
                try {
                    window.localStorage.setItem(backgroundStorageKey, button.dataset.pzPreviewBackground);
                } catch {
                    // Keep the palette usable when browser storage is unavailable.
                }
                button.blur();
            });
        });
        let savedBackground = "white";
        try {
            savedBackground = window.localStorage.getItem(backgroundStorageKey) || "white";
        } catch {
            // Use the default background when browser storage is unavailable.
        }
        applyPreviewBackground(savedBackground);

        const preventPreviewLink = e => {
            if (!e.target?.closest?.(".pz-settings-preview-window a")) return;
            e.preventDefault();
            e.stopPropagation();
        };
        win.addEventListener("click", preventPreviewLink, true);
        win.addEventListener("auxclick", preventPreviewLink, true);

        const minPreviewWidth = 240;
        const minPreviewHeight = 100;
        const rightDockedMinWidth = 180;
        const dockedMinHeightFallback = 28;
        let previewDocked = false;
        let previewDockSide = null;
        let floatingPreviewRect = null;
        let lastBottomDockedHeight = null;
        let lastHandleClick = null;
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
        const readRightDockedWidth = state => {
            const storedMode = state?.["preview-mode"];
            const restoreMode = state?.["preview-restore-mode"];
            const legacyWidth = storedMode === "right" || (storedMode === "icon" && restoreMode === "right")
                ? readStateNumber(state, "preview-right-docked-width")
                : null;
            return readStateNumber(state, "right-docked-width") ?? legacyWidth ?? minPreviewWidth;
        };
        const readBottomDockedHeight = state => {
            return [lastBottomDockedHeight,
                readStateNumber(state, "docked-height"),
                readStateNumber(state, "last-bottom-docked-height"),
                readStateNumber(state, "preview-docked-height"),
            ].find(value => Number.isFinite(value) && value > 0) ?? minPreviewHeight;
        };
        const readStoredState = () => {
            const state = {};
            form?.querySelectorAll("[data-pz-preview-state]").forEach(input => {
                if (input.value !== "") state[input.dataset.pzPreviewState] = input.value;
            });
            return state;
        };
        const persistStoredState = state => {
            Object.entries(state).forEach(([name, value]) => setStateInput(name, value));
        };
        const applyTwoCards = enabled => {
            const active = !!enabled;
            win.classList.toggle("pz-settings-preview-two-cards", active);
            ["ex", "in"].forEach(prefix => {
                win.querySelectorAll(`[data-pz-preview-card="${prefix}"]`).forEach((card, index) => {
                    card.querySelectorAll('.lkc-title').forEach(title => {
                    if (title.dataset.pzPreviewBaseTitle === undefined) {
                        title.dataset.pzPreviewBaseTitle = title.textContent;
                    }
                    title.textContent = title.dataset.pzPreviewBaseTitle + (active ? ` (${index + 1})` : "");
                    });
                });
            });
            if (twoCardsCheckbox) twoCardsCheckbox.checked = active;
            setStateInput("preview-two-cards", active ? "1" : "0");
        };
        const updateModeButton = () => {
            if (!modeButton || !secondaryModeButton) return;
            if (!previewDocked) {
                setButtonIcon(modeButton, "preview-dock-bottom.svg");
                modeButton.setAttribute("aria-label", labels.dockPreviewBottom || "Dock preview bottom");
                setButtonIcon(secondaryModeButton, "preview-dock-right.svg");
                secondaryModeButton.setAttribute("aria-label", labels.dockPreviewRight || "Dock preview right");
            } else if (previewDockSide === "right") {
                setButtonIcon(modeButton, "preview-dock-bottom.svg");
                modeButton.setAttribute("aria-label", labels.dockPreviewBottom || "Dock preview bottom");
                setButtonIcon(secondaryModeButton, "preview-window.svg");
                secondaryModeButton.setAttribute("aria-label", labels.windowPreview || "Window preview");
            } else {
                setButtonIcon(modeButton, "preview-dock-right.svg");
                modeButton.setAttribute("aria-label", labels.dockPreviewRight || "Dock preview right");
                setButtonIcon(secondaryModeButton, "preview-window.svg");
                secondaryModeButton.setAttribute("aria-label", labels.windowPreview || "Window preview");
            }
            modeButton.title = modeButton.getAttribute("aria-label");
            secondaryModeButton.title = secondaryModeButton.getAttribute("aria-label");
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
            const rect = getWindowRect();
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
            // During a mode transition, save the destination rather than an intermediate frame.
            const value = name => {
                const inline = win.style[name];
                return win.classList.contains("pz-settings-preview-animating") && inline.endsWith("px")
                    ? parseFloat(inline) : rect[name];
            };
            return {
                left: value("left"),
                top: value("top"),
                width: value("width"),
                height: value("height"),
            };
        };
        const syncPreviewState = () => {
            if (win.style.display === "none") return;
            const rect = getWindowRect();
            if (rect.width <= 0 || rect.height <= 0) return null;

            const storedState = readStoredState() || {};
            const savedBottomDockedHeight = previewDockSide === "bottom"
                ? rect.height
                : lastBottomDockedHeight
                    ?? readStateNumber(storedState, "last-bottom-docked-height")
                    ?? readStateNumber(storedState, "preview-docked-height")
                    ?? readStateNumber(null, "preview-docked-height");
            const state = {
                ...storedState,
                "preview-mode": previewDockSide === "right" ? "right" : (previewDocked ? "docked" : "window"),
                "preview-left": Math.round(rect.left),
                "preview-top": Math.round(rect.top),
                "preview-width": Math.round(rect.width),
                "preview-height": Math.round(rect.height),
                "preview-docked-height": Math.round(savedBottomDockedHeight || minPreviewHeight),
                "last-bottom-docked-height": Math.round(savedBottomDockedHeight || minPreviewHeight),
                "preview-right-docked-width": Math.round(previewDockSide === "right"
                    ? rect.width
                    : (readStateNumber(storedState, "right-docked-width")
                        ?? readStateNumber(storedState, "preview-right-docked-width")
                        ?? readStateNumber(null, "preview-right-docked-width")
                        ?? minPreviewWidth)),
                "preview-two-cards": twoCardsCheckbox?.checked ? 1 : 0,
            };
            if (previewDockSide === "right") {
                state["right-docked-width"] = Math.round(rect.width);
            } else if (previewDockSide === "bottom") {
                state["docked-height"] = Math.round(rect.height);
                state["docked-width"] = Math.round(rect.width);
            } else if (previewDocked) {
                Object.assign(state, {
                    "docked-width": Math.round(rect.width),
                });
            } else {
                Object.assign(state, {
                    "window-left": Math.round(rect.left),
                    "window-top": Math.round(rect.top),
                    "window-width": Math.round(rect.width),
                    "window-height": Math.round(rect.height),
                });
            }
            persistStoredState(state);
            return state;
        };
        const saveIconState = () => {
            const rect = getWindowRect();
            const storedState = readStoredState() || {};
            const savedBottomDockedHeight = previewDockSide === "bottom"
                ? rect.height
                : lastBottomDockedHeight
                    ?? readStateNumber(storedState, "last-bottom-docked-height")
                    ?? readStateNumber(storedState, "preview-docked-height")
                    ?? readStateNumber(null, "preview-docked-height");
            const state = {
                ...storedState,
                "preview-mode": "icon",
                "preview-restore-mode": previewDockSide === "right" ? "right" : (previewDocked ? "docked" : "window"),
                "preview-left": Math.round(rect.left),
                "preview-top": Math.round(rect.top),
                "preview-width": Math.round(rect.width),
                "preview-height": Math.round(rect.height),
                "preview-right-docked-width": Math.round(previewDockSide === "right"
                    ? rect.width
                    : (readStateNumber(storedState, "right-docked-width")
                        ?? readStateNumber(storedState, "preview-right-docked-width")
                        ?? readStateNumber(null, "preview-right-docked-width")
                        ?? minPreviewWidth)),
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
            persistStoredState(state);
        };
        let previewFitFrame = null;
        const updatePreviewFit = () => {
            if (previewClosed || win.style.display === "none") return;

            const rect = win.getBoundingClientRect();
            const handleHeight = Math.ceil(handle.getBoundingClientRect().height);
            if (!twoCardsControl) return;
            twoCardsControl.classList.remove("pz-settings-preview-two-cards-control-hidden");
            const controlRect = twoCardsControl.getBoundingClientRect();
            const controlFitsWidth = previewDockSide === "right"
                ? rect.width >= rightDockedMinWidth
                : rect.width >= 32 + Math.ceil(controlRect.width) + 8;
            const controlFitsHeight = rect.height >= handleHeight + Math.ceil(controlRect.height) + 12;
            twoCardsControl.classList.toggle(
                "pz-settings-preview-two-cards-control-hidden",
                !controlFitsWidth || !controlFitsHeight
            );
        };
        const schedulePreviewFit = () => {
            if (previewFitFrame !== null) return;
            previewFitFrame = window.requestAnimationFrame(() => {
                previewFitFrame = null;
                updatePreviewFit();
            });
        };
        const persistPreviewState = async () => {
            return;
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
            const cornerSize = 28;
            if (previewDocked) {
                if (previewDockSide === "right") {
                    return e.target?.closest?.("[data-pz-preview-handle]")
                        ? { top: false, right: false, bottom: false, left: true }
                        : null;
                }
                return e.clientY - rect.top <= edgeSize ? { top: true, right: false, bottom: false, left: false } : null;
            }
            if (rect.right - e.clientX <= cornerSize && rect.bottom - e.clientY <= cornerSize) {
                return { top: false, right: true, bottom: true, left: false };
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
            if (e.target?.closest?.("[data-pz-preview-handle]") && previewDockSide !== "right") {
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
            const preferredWidth = Math.min(minPreviewWidth, maxWidth);
            const preferredHeight = Math.min(minPreviewHeight, maxHeight);
            if (rect.width > maxWidth) {
                win.style.width = `${Math.round(maxWidth)}px`;
            } else if (rect.width < preferredWidth) {
                win.style.width = `${Math.round(preferredWidth)}px`;
            }
            if (rect.height > maxHeight) {
                win.style.height = `${Math.round(maxHeight)}px`;
                win.style.maxHeight = "none";
            } else if (rect.height < preferredHeight) {
                win.style.height = `${Math.round(preferredHeight)}px`;
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
            const width = Math.min(Math.max(minPreviewWidth, next.width), Math.max(1, bounds.maxRight - bounds.minLeft));
            const height = Math.min(Math.max(minPreviewHeight, next.height), Math.max(1, bounds.maxBottom - bounds.minTop));
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
            // The right dock's vertical handle spans the entire viewport height.
            // Switch its layout before measuring the bottom dock's minimum height.
            previewDocked = true;
            previewDockSide = "bottom";
            win.classList.add("pz-settings-preview-docked");
            win.classList.remove("pz-settings-preview-docked-right");
            const dockedMinHeight = getDockedMinHeight();
            const maxHeight = Math.max(dockedMinHeight, window.innerHeight - bounds.minTop);
            // An unset/zero bottom height must never inherit the right dock's
            // viewport height.
            const requestedHeight = Number.isFinite(height) && height > 0 ? height : readBottomDockedHeight(readStoredState());
            const nextHeight = Math.min(maxHeight, Math.max(dockedMinHeight, requestedHeight));

            setRightDockedScrollSpace(0);
            setDockedScrollSpace(nextHeight);
            updateModeButton();
            win.style.left = `${Math.round(dockLeft)}px`;
            win.style.top = `${Math.round(window.innerHeight - nextHeight)}px`;
            win.style.right = "auto";
            win.style.bottom = "auto";
            const dockedWidth = Math.max(1, getViewportClientRight() - dockLeft);
            win.style.width = `${Math.round(dockedWidth)}px`;
            win.style.height = `${Math.round(nextHeight)}px`;
            win.style.maxHeight = "none";
            syncPreviewState();
        };
        const applyRightDockedRect = width => {
            const bounds = getViewportBounds();
            const availableWidth = Math.max(1, getViewportClientRight() - bounds.minLeft);
            const minWidth = Math.min(rightDockedMinWidth, availableWidth);
            const maxWidth = Math.max(minWidth, availableWidth);
            const nextWidth = Math.min(maxWidth, Math.max(minWidth, width || getWindowRect().width));

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
            }, 100);
        };
        const normalizeBottomDockedHeight = (height, stored = {}) => {
            const bounds = getViewportBounds();
            const minHeight = getDockedMinHeight();
            const maxHeight = Math.max(minHeight, window.innerHeight - bounds.minTop);
            let nextHeight = Number.isFinite(height) ? height : minPreviewHeight;
            return Math.min(maxHeight, Math.max(minHeight, nextHeight));
        };
        const switchPreviewMode = targetMode => {
            if (previewDockSide === "bottom") {
                lastBottomDockedHeight = Math.round(getWindowRect().height);
            }
            if (!previewDocked) floatingPreviewRect = getWindowRect();
            syncPreviewState();
            const stored = readStoredState() || {};
            animatePreviewWindow();

            if (targetMode === "window") {
                const windowRect = {
                    left: readStateNumber(stored, "window-left"),
                    top: readStateNumber(stored, "window-top"),
                    width: readStateNumber(stored, "window-width"),
                    height: readStateNumber(stored, "window-height"),
                };
                const hasWindowRect = !!windowRect && Object.values(windowRect).every(value => value !== null);
                applyFloatingRect(hasWindowRect ? windowRect : floatingPreviewRect);
            } else if (targetMode === "bottom") {
                const dockedHeight = readBottomDockedHeight(stored);
                applyDockedRect(dockedHeight);
            } else if (targetMode === "right") {
                const dockedWidth = readRightDockedWidth(stored);
                applyRightDockedRect(dockedWidth);
            }
            persistPreviewState();
        };
        const toggleDockedPreview = () => {
            switchPreviewMode(previewDocked ? "window" : "bottom");
        };
        const cyclePreviewMode = () => {
            if (!previewDocked) {
                switchPreviewMode("bottom");
            } else if (previewDockSide === "bottom") {
                switchPreviewMode("right");
            } else {
                switchPreviewMode("window");
            }
        };
        const cyclePreviewModeWithIcon = () => {
            if (previewClosed) {
                hideRestoreButton();
                previewClosed = false;
                win.style.display = "";
                restorePreviewState();
                showPreviewWindow();
                persistPreviewState();
                return;
            }

            if (previewDockSide === "right") {
                syncPreviewState();
                persistPreviewState();
                saveIconState();
                showIconPreview();
                return;
            }

            if (!previewDocked) {
                switchPreviewMode("bottom");
            } else {
                switchPreviewMode("right");
            }
        };
        const dockPreviewRight = () => {
            switchPreviewMode("right");
        };
        const restorePreviewState = () => {
            const stored = readStoredState() || {};
            const storedMode = stored["preview-mode"] || readStateInput("preview-mode");
            const mode = storedMode === "icon" ? (stored["preview-restore-mode"] || "window") : storedMode;
            // The server may already have rendered a docked rectangle while the
            // client mode flags still have their initial floating values.
            const initiallyDocked = mode === "right" || mode === "docked";
            let initialFloatingRect = !previewDocked && !initiallyDocked ? getWindowRect() : null;
            const windowRect = {
                left: readStateNumber(stored, "window-left"),
                top: readStateNumber(stored, "window-top"),
                width: readStateNumber(stored, "window-width"),
                height: readStateNumber(stored, "window-height"),
            };
            const hasWindowRect = Object.values(windowRect).every(value => value !== null);
            if (hasWindowRect) {
                floatingPreviewRect = windowRect;
            } else if (!floatingPreviewRect) {
                if (initiallyDocked) {
                    // Measure the normal window defaults without painting the
                    // temporary layout or adopting a dock's full-screen size.
                    const savedStyle = win.style.cssText;
                    const savedClass = win.className;
                    win.classList.remove("pz-settings-preview-docked", "pz-settings-preview-docked-right", "pz-settings-preview-animating");
                    ["left", "top", "right", "bottom", "width", "height", "max-height"].forEach(name => win.style.removeProperty(name));
                    initialFloatingRect = getWindowRect();
                    win.style.cssText = savedStyle;
                    win.className = savedClass;
                }
                if (initialFloatingRect?.width > 0 && initialFloatingRect?.height > 0) {
                    floatingPreviewRect = initialFloatingRect;
                    persistStoredState({
                        "window-left": Math.round(initialFloatingRect.left),
                        "window-top": Math.round(initialFloatingRect.top),
                        "window-width": Math.round(initialFloatingRect.width),
                        "window-height": Math.round(initialFloatingRect.height),
                    });
                }
            }

            if (mode === "docked") {
                const dockedHeight = readBottomDockedHeight(stored);
                applyDockedRect(dockedHeight);
                return;
            }
            if (mode === "right") {
                const dockedWidth = readRightDockedWidth(stored);
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
            syncPreviewState();
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
            if (e.target?.closest?.("[data-pz-preview-handle]")) {
                const now = Date.now();
                const distance = lastHandleClick
                    ? Math.hypot(e.clientX - lastHandleClick.x, e.clientY - lastHandleClick.y) : Infinity;
                const isDoubleClick = lastHandleClick && now - lastHandleClick.time < 400 && distance < 8;
                lastHandleClick = { time: now, x: e.clientX, y: e.clientY };
                if (e.detail === 2 || isDoubleClick) {
                    e.preventDefault();
                    e.stopPropagation();
                    lastHandleClick = null;
                    cyclePreviewMode();
                    return;
                }
            }
            if (e.target?.closest?.("[data-pz-preview-handle]") && previewDockSide !== "right") return;
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
                if (startedRightDocked && !undockedFromRight
                    && Math.abs(moveEvent.clientX - e.clientX) <= 64
                    && Math.abs(moveEvent.clientY - e.clientY) >= 64) {
                    const stored = readStoredState() || {};
                    const windowRect = {
                        left: readStateNumber(stored, "window-left"),
                        top: readStateNumber(stored, "window-top"),
                        width: readStateNumber(stored, "window-width"),
                        height: readStateNumber(stored, "window-height"),
                    };
                    const hasWindowRect = Object.values(windowRect).every(value => value !== null);
                    applyFloatingRect(hasWindowRect ? windowRect : floatingPreviewRect);
                    const floatingRect = getWindowRect();
                    dragOffsetX = Math.min(floatingRect.width, floatingRect.width / 2);
                    dragOffsetY = Math.min(floatingRect.height, handle.getBoundingClientRect().height / 2);
                    undockedFromRight = true;
                    win.classList.remove("pz-settings-preview-resizing");
                    win.classList.add("pz-settings-preview-dragging");
                    win.style.cursor = "move";
                    handle.style.cursor = "move";
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
                if (!previewDocked) floatingPreviewRect = getWindowRect();
                syncPreviewState();
                persistPreviewState();
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
                if (startedBottomDocked && !undockedFromBottom
                    && Math.abs(moveEvent.clientY - e.clientY) <= 64
                    && Math.abs(moveEvent.clientX - e.clientX) >= 64) {
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
            // Mode switching is handled on the second pointerdown in every mode.
            e.stopPropagation();
        });
        modeButton?.addEventListener("click", e => {
            e.preventDefault();
            e.stopPropagation();
            if (previewDockSide === "bottom") {
                dockPreviewRight();
            } else if (previewDockSide === "right") {
                switchPreviewMode("bottom");
            } else {
                toggleDockedPreview();
            }
            modeButton.blur();
        });
        secondaryModeButton?.addEventListener("click", e => {
            e.preventDefault();
            e.stopPropagation();
            if (previewDocked) {
                toggleDockedPreview();
            } else {
                dockPreviewRight();
            }
            secondaryModeButton.blur();
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

        document.addEventListener("keydown", e => {
            if (e.altKey && !e.ctrlKey && !e.metaKey && !e.shiftKey && !e.isComposing && !e.repeat && e.key.toLowerCase() === "v") {
                e.preventDefault();
                if (previewClosed) restoreButton.click();
                else closeButton?.click();
                return;
            }
            if (!e.altKey || e.ctrlKey || e.metaKey || e.shiftKey || e.isComposing || e.key.toLowerCase() !== "p") return;

            e.preventDefault();
            cyclePreviewModeWithIcon();
        });

        window.addEventListener("resize", keepInViewport);
        if (window.ResizeObserver) {
            new ResizeObserver(schedulePreviewFit).observe(win);
        }
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
        schedulePreviewFit();
        initSettingsPreviewLiveStyles(win, syncPreviewState, () => applyTwoCards(twoCardsCheckbox?.checked));
    }

    function initSettingsPreviewLiveStyles(win, syncPreviewState, refreshPreviewTitles) {
        const form = document.querySelector(".pz-settings form");
        if (!form) return;

        // Save the exact preview geometry before the settings form navigates/reloads.
        form.addEventListener("submit", () => {
            syncPreviewState();
        }, true);

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
                ["url", ".lkc-url, .lkc-url-info, .lkc-url > a.lkc-link, .lkc-url-info > a.lkc-link"],
                ["date", ".lkc-date"],
                ["heading", ".lkc-heading"],
                ["more", ".lkc-more"],
                ["info", ".lkc-info"],
                ["added", ".lkc-added"],
            ];
            return ["ex", "in"].flatMap(prefix => items.map(([name, selector]) => {
                const decoration = value(`${name}-hover`) !== "" ? "underline" : "none";
                const selectors = selector.split(",").map(part => `.pz-settings-preview-window [data-pz-preview-card="${prefix}"] ${part.trim()}:hover`);
                return `${selectors.join(", ")} { text-decoration: ${decoration} !important; }`;
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
        const kickPreviewCssCallback = async seq => {
            if (typeof pzLinkCardPreview === "undefined" || !pzLinkCardPreview.ajaxUrl) return;

            const fd = collectAllProperties();
            fd.append("action", pzLinkCardPreview.action || "pz_lkc_preview_render");
            fd.append("nonce", pzLinkCardPreview.nonce || "");

            try {
                const response = await fetch(pzLinkCardPreview.ajaxUrl, { method: "POST", body: fd });
                const json = await response.json();
                if (seq !== previewCssRequestSeq || !json?.success) return;
                getPreviewStyleElement().textContent = json.data?.css || "";
                if (Array.isArray(json.data?.cards)) {
                    win.querySelectorAll('.pz-settings-preview-item').forEach((item, index) => {
                        if (typeof json.data.cards[index] === 'string') item.innerHTML = json.data.cards[index];
                    });
                    refreshPreviewTitles();
                }
            } catch (err) {
                console.warn("Pz-LinkCard preview CSS failed:", err);
            }
        };
        const schedulePreviewCssCallback = (delay = 120) => {
            if (previewCssTimer) clearTimeout(previewCssTimer);
            // Invalidate older responses as soon as settings change, including
            // while the next request is still waiting for its debounce timer.
            const seq = ++previewCssRequestSeq;
            previewCssTimer = setTimeout(() => kickPreviewCssCallback(seq), delay);
        };

        const findControl = name => {
            const controls = Array.from(form.querySelectorAll(`[name="properties[${name}]"]`));
            const visibleControls = controls.filter(el => el.type !== "hidden" && !isControlDisabled(el) && (el.getClientRects?.().length || el.offsetParent !== null));
            if (visibleControls.length) return visibleControls[0];
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
                const displayValue = node.matches(".lkc-info") ? "flex" : (node.matches(".lkc-sns-tw, .lkc-sns-x, .lkc-sns-fb, .lkc-sns-hb, .lkc-sns-qiita, .lkc-sns-gp") ? "inline" : "block");
                node.style.setProperty("display", displayValue, "important");
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
        const syncShare = (card) => {
            const enabled = checked("sns-tw") || checked("sns-fb") || checked("sns-hb") || checked("sns-qiita");
            card.querySelectorAll(".lkc-share").forEach(share => {
                const twText = checked("sns-tw-x") ? "1234 tweets" : "1234 posts";
                const tw = ensureShareNode(share, ".lkc-sns-tw, .lkc-sns-x", checked("sns-tw-x") ? "lkc-sns-tw no_icon" : "lkc-sns-x no_icon", twText);
                const fb = ensureShareNode(share, ".lkc-sns-fb", "lkc-sns-fb no_icon", "1234 shares");
                const hb = ensureShareNode(share, ".lkc-sns-hb", "lkc-sns-hb no_icon", "1234 users");
                const qiitaLikes = ensureShareNode(share, ".lkc-sns-qiita:not(.lkc-sns-qiita-stock)", "lkc-sns-qiita lkc-sns-qiita-likes no_icon", labels.qiitaLikes || "1234 likes");
                const qiitaStocks = ensureShareNode(share, ".lkc-sns-qiita-stock", "lkc-sns-qiita lkc-sns-qiita-stock no_icon", labels.qiitaStocks || "1234 stocks");
                applyDisplay(tw, enabled && checked("sns-tw"));
                applyDisplay(fb, enabled && checked("sns-fb"));
                applyDisplay(hb, enabled && checked("sns-hb"));
                applyDisplay(qiitaLikes, enabled && checked("sns-qiita"));
                applyDisplay(qiitaStocks, enabled && checked("sns-qiita"));
                applyDisplay(share, enabled);
            });
        };
        const previewUrlText = (card, fallbackPrefix) => {
            const current = card.querySelector(".lkc-url, .lkc-url-info")?.textContent?.trim();
            if (current) return current;
            const href = card.querySelector("a.lkc-link")?.getAttribute("href");
            if (href) return href;
            return fallbackPrefix === "in" ? "/" : "https://example.com/pz-linkcard-preview";
        };
        const fallbackPreviewDate = daysAgo => {
            const date = new Date();
            date.setDate(date.getDate() - daysAgo);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, "0");
            const day = String(date.getDate()).padStart(2, "0");
            return `${year}/${month}/${day}`;
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
        const applyConfiguredBoxShadow = (node, prefix, hover = false) => {
            if (!node) return false;

            const settingPrefix = hover ? `${prefix}-hover` : prefix;
            if (!checked(`${settingPrefix}-shadow-enabled`)) return false;

            const shadowColor = value(`${settingPrefix}-shadow-color`) || "rgba(0,0,0,0.3)";
            const shadowInset = checked(`${settingPrefix}-shadow-inset`) ? "inset " : "";
            const shadow = `${shadowInset}${intValue(`${settingPrefix}-shadow-x`, 8)}px ${intValue(`${settingPrefix}-shadow-y`, 8)}px ${intValue(`${settingPrefix}-shadow-blur`, 8)}px ${intValue(`${settingPrefix}-shadow-spread`, 0)}px ${shadowColor}`;
            node.style.setProperty("box-shadow", shadow, hover ? "important" : "");
            return true;
        };
        const applyConfiguredLinkBoxHover = (node, prefix) => {
            if (!node) return;
			const specialFormat = value("special-format");
			const isFloatingLinkBox = ["floating", "floating2", "fbd"].includes(specialFormat);
			const isAccentLinkBox = specialFormat === "accent";

            if (checked(`${prefix}-hover-transform-enabled`)) {
                const transformX = intValue(`${prefix}-hover-transform-x`, 0);
                const transformY = intValue(`${prefix}-hover-transform-y`, 0);
                const transformRotate = intValue(`${prefix}-hover-transform-rotate`, 0);
                const transformScale = intValue(`${prefix}-hover-transform-scale`, 100);
                if (specialFormat !== "tracking") {
                    node.style.setProperty("transform", `translate(${transformX}px, ${transformY}px) rotate(${transformRotate}deg) scale(${transformScale / 100})`, "important");
                }
                node.style.setProperty("opacity", String(Math.max(0, Math.min(100, intValue(`${prefix}-hover-opacity`, 100))) / 100), "important");
            }
            if (checked(`${prefix}-hover-bg-enabled`) && !isAccentLinkBox) {
                const bgColor = value(`${prefix}-hover-bg-color`);
                const bgImage = cssBackgroundImage(`${prefix}-hover-bg-image`);
                if (bgColor) node.style.setProperty("background-color", bgColor, "important");
                if (bgImage) node.style.setProperty("background-image", bgImage, "important");
            }
            if (checked(`${prefix}-hover-border-enabled`) && !isFloatingLinkBox) {
                const borderColor = value(`${prefix}-hover-border-color`);
                const borderStyle = value(`${prefix}-hover-border-style`) || "solid";
                const borderWidth = intValue(`${prefix}-hover-border-width`, 1);
                const borderRadius = intValue(`${prefix}-hover-border-radius`, 0);
                node.style.setProperty("border", `${borderColor ? `${borderColor} ` : ""}${borderStyle} ${borderWidth}px`, "important");
                node.style.setProperty("border-radius", `${borderRadius}px`, "important");
            }
			if (isFloatingLinkBox) {
				node.style.setProperty("border", "none", "important");
				node.style.setProperty("border-radius", `${intValue(`${prefix}-border-radius`, 0)}px`, "important");
			}
			if (isAccentLinkBox) {
				node.style.setProperty("background-color", "#ffffff", "important");
			}
            applyConfiguredBoxShadow(node, prefix, true);

            const transition = parseFloat(value(`${prefix}-hover-transition`));
            if (specialFormat !== "tracking" && Number.isFinite(transition) && transition > 0) {
                node.style.setProperty("transition", `all ${transition}s ease`, "important");
            }
        };
        const applySimpleLinkBox = (node, prefix, hover = false) => {
            if (!node) return;

            node.style.setProperty("background", "none", "important");
            node.style.setProperty("border", "none", "important");

            if (hover) {
                if (checked(`${prefix}-hover-bg-enabled`)) {
                    const bgColor = value(`${prefix}-hover-bg-color`);
                    const bgImage = cssBackgroundImage(`${prefix}-hover-bg-image`);
                    if (bgColor) node.style.setProperty("background-color", bgColor, "important");
                    if (bgImage) node.style.setProperty("background-image", bgImage, "important");
                }
                if (checked(`${prefix}-hover-border-enabled`)) {
                    const borderColor = value(`${prefix}-hover-border-color`);
                    const borderStyle = value(`${prefix}-hover-border-style`) || "solid";
                    const borderWidth = intValue(`${prefix}-hover-border-width`, 1);
                    node.style.setProperty("border", `${borderColor ? `${borderColor} ` : ""}${borderStyle} ${borderWidth}px`, "important");
                    const borderRadius = intValue(`${prefix}-hover-border-radius`, 0);
                    if (borderRadius > 0) node.style.borderRadius = `${borderRadius}px`;
                }
                return;
            }

            const borderRadius = checked(`${prefix}-border-enabled`) ? intValue(`${prefix}-border-radius`, 0) : 0;
            node.style.borderRadius = borderRadius > 0 ? `${borderRadius}px` : "";
        };
        const stitchPresets = {
            sBR: {
                ex: { background: "#bcddff", outline: "#aabbee" },
                in: { background: "#f8d0d0", outline: "#e8a8a8" },
            },
			"stitch-free": {
				ex: {
					get background() { return value("ex-bg-color") || "transparent"; },
					get outline() { return value("ex-border-color") || "transparent"; },
				},
				in: {
					get background() { return value("in-bg-color") || "transparent"; },
					get outline() { return value("in-border-color") || "transparent"; },
				},
			},
            sGY: {
                ex: { background: "#acefdd", outline: "#8abecb" },
                in: { background: "#ffde51", outline: "#fbca4d" },
            },
        };
        const ingressPresets = {
            inI: {
                color: "#ebbc4a",
                ex: { background: "rgba(94, 75, 29, 0.90)" },
                in: { background: "rgba(23, 18, 7, 0.90)" },
            },
            inN: {
                color: "#59fbea",
                ex: { background: "rgba(35, 100, 93, 0.90)" },
                in: { background: "rgba(8, 25, 23, 0.90)" },
            },
            inE: {
                color: "#28f428",
                ex: { background: "rgba(16, 97, 16, 0.90)" },
                in: { background: "rgba(4, 24, 4, 0.90)" },
            },
            inR: {
                color: "#00c2ff",
                ex: { background: "rgba(0, 77, 102, 0.90)" },
                in: { background: "rgba(0, 19, 25, 0.90)" },
            },
        };
        const applyStitchLinkBox = (node, prefix, preset, hover = false) => {
            if (!node || !preset) return;

            node.style.setProperty("background", preset.background, "important");
            node.style.setProperty("border", "2px dashed rgba(255,255,255,0.5)", "important");

            node.style.boxShadow = `0 0 0 5px ${preset.outline}, 3px 3px 6px 4px rgba(0,0,0,0.6)`;

            if (hover) {
                if (checked(`${prefix}-hover-bg-enabled`)) {
                    const bgColor = value(`${prefix}-hover-bg-color`);
                    const bgImage = cssBackgroundImage(`${prefix}-hover-bg-image`);
                    if (bgColor) node.style.setProperty("background-color", bgColor, "important");
                    if (bgImage) node.style.setProperty("background-image", bgImage, "important");
                }
                if (checked(`${prefix}-hover-border-enabled`)) {
                    const borderColor = value(`${prefix}-hover-border-color`);
                    const borderStyle = value(`${prefix}-hover-border-style`) || "solid";
                    const borderWidth = intValue(`${prefix}-hover-border-width`, 1);
                    node.style.setProperty("border", `${borderColor ? `${borderColor} ` : ""}${borderStyle} ${borderWidth}px`, "important");
                    const borderRadius = intValue(`${prefix}-hover-border-radius`, 0);
                    if (borderRadius > 0) node.style.borderRadius = `${borderRadius}px`;
                }
                if (checked(`${prefix}-hover-shadow-enabled`)) {
                    const shadowColor = value(`${prefix}-hover-shadow-color`) || "rgba(0,0,0,0.3)";
                    const shadowInset = checked(`${prefix}-hover-shadow-inset`) ? "inset " : "";
                    node.style.boxShadow = `${shadowInset}${intValue(`${prefix}-hover-shadow-x`, 8)}px ${intValue(`${prefix}-hover-shadow-y`, 8)}px ${intValue(`${prefix}-hover-shadow-blur`, 8)}px ${intValue(`${prefix}-hover-shadow-spread`, 0)}px ${shadowColor}`;
                }
                return;
            }

            const borderRadius = checked(`${prefix}-border-enabled`) ? intValue(`${prefix}-border-radius`, 0) : 0;
            node.style.borderRadius = borderRadius > 0 ? `${borderRadius}px` : "";
        };
        const applyIngressLinkBox = (node, prefix, preset, hover = false) => {
            if (!node || !preset) return;

            const bgImage = cssBackgroundImage(`${prefix}-bg-image`);
            node.style.setProperty("background-color", preset.background, "important");
            node.style.backgroundImage = checked(`${prefix}-bg-enabled`) && bgImage ? bgImage : "";
            node.style.setProperty("border", `4px solid ${preset.color}`, "important");

            const borderRadius = checked(`${prefix}-border-enabled`) ? intValue(`${prefix}-border-radius`, 0) : 0;
            node.style.borderRadius = borderRadius > 0 ? `${borderRadius}px` : "";
            if (checked(`${prefix}-shadow-enabled`)) {
                const shadowColor = value(`${prefix}-shadow-color`) || "rgba(0,0,0,0.3)";
                const shadowInset = checked(`${prefix}-shadow-inset`) ? "inset " : "";
                node.style.boxShadow = `${shadowInset}${intValue(`${prefix}-shadow-x`, 8)}px ${intValue(`${prefix}-shadow-y`, 8)}px ${intValue(`${prefix}-shadow-blur`, 8)}px ${intValue(`${prefix}-shadow-spread`, 0)}px ${shadowColor}`;
            } else {
                node.style.boxShadow = "";
            }

            if (!hover) return;

            if (checked(`${prefix}-hover-bg-enabled`)) {
                const hoverBgColor = value(`${prefix}-hover-bg-color`);
                const hoverBgImage = cssBackgroundImage(`${prefix}-hover-bg-image`);
                if (hoverBgColor) node.style.setProperty("background-color", hoverBgColor, "important");
                if (hoverBgImage) node.style.backgroundImage = hoverBgImage;
            }
            if (checked(`${prefix}-hover-border-enabled`)) {
                const hoverBorderColor = value(`${prefix}-hover-border-color`);
                const hoverBorderStyle = value(`${prefix}-hover-border-style`) || "solid";
                const hoverBorderWidth = intValue(`${prefix}-hover-border-width`, 1);
                node.style.setProperty("border", `${hoverBorderColor ? `${hoverBorderColor} ` : ""}${hoverBorderStyle} ${hoverBorderWidth}px`, "important");
                const hoverBorderRadius = intValue(`${prefix}-hover-border-radius`, 0);
                if (hoverBorderRadius > 0) node.style.borderRadius = `${hoverBorderRadius}px`;
            }
            if (checked(`${prefix}-hover-shadow-enabled`)) {
                const hoverShadowColor = value(`${prefix}-hover-shadow-color`) || "rgba(0,0,0,0.3)";
                const hoverShadowInset = checked(`${prefix}-hover-shadow-inset`) ? "inset " : "";
                node.style.boxShadow = `${hoverShadowInset}${intValue(`${prefix}-hover-shadow-x`, 8)}px ${intValue(`${prefix}-hover-shadow-y`, 8)}px ${intValue(`${prefix}-hover-shadow-blur`, 8)}px ${intValue(`${prefix}-hover-shadow-spread`, 0)}px ${hoverShadowColor}`;
            }
        };
        const applyGlassBackground = (node, prefix) => {
            const color = value(`${prefix}-bg-color`) || (prefix === "in" ? "#fff4d9" : "#dbeeff");
            const hex = color.replace("#", "");
            const rgb = hex.length === 3 ? hex.split("").map(ch => parseInt(ch + ch, 16)) : [parseInt(hex.slice(0, 2), 16), parseInt(hex.slice(2, 4), 16), parseInt(hex.slice(4, 6), 16)];
            node.style.backgroundColor = `rgba(${rgb.join(",")},.5)`;
            node.style.backgroundImage = "radial-gradient(circle at 18% 12%, rgba(255,255,255,.5), transparent 34%), radial-gradient(circle at 82% 88%, rgba(176,215,255,.22), transparent 42%), linear-gradient(135deg, rgba(255,255,255,.38), rgba(220,235,255,.14))";
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
            const isLinkBox = prefix === "ex" || prefix === "in";
            const specialFormat = value("special-format");
            const isSimpleLinkBox = specialFormat === "smp" && isLinkBox;
			const isFloatingLinkBox = isLinkBox && ["floating", "floating2", "fbd"].includes(specialFormat);
			const isAccentLinkBox = isLinkBox && specialFormat === "accent";
            const stitchPreset = isLinkBox ? stitchPresets[specialFormat]?.[prefix] : null;
            const ingressPreset = isLinkBox ? ingressPresets[specialFormat]?.[prefix] : null;
            const ignoreLinkBackground = isLinkBox && (specialFormat === "JIN" || isAccentLinkBox || isSimpleLinkBox || stitchPreset);
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
            if (isLinkBox && specialFormat === "glass") applyGlassBackground(node, prefix);
            if (checked(`${prefix}-border-enabled`)) {
                const borderColor = value(`${prefix}-border-color`);
                const borderStyle = value(`${prefix}-border-style`) || "solid";
                const borderWidth = intValue(`${prefix}-border-width`, 1);
                const borderRadius = intValue(`${prefix}-border-radius`, 0);
                if (borderStyle && !isSimpleLinkBox && !stitchPreset) {
                    node.style.setProperty("border", `${borderColor ? `${borderColor} ` : ""}${borderStyle} ${borderWidth}px`, "important");
                }
                if (borderRadius > 0) {
                    node.style.borderRadius = `${borderRadius}px`;
                }
            } else {
                node.style.setProperty("border", "none", "important");
                node.style.borderRadius = "";
            }
			if (isFloatingLinkBox) {
				node.style.setProperty("border", "none", "important");
				node.style.borderRadius = `${intValue(`${prefix}-border-radius`, 0)}px`;
			}
			if (isAccentLinkBox) {
				node.style.setProperty("background-color", "#ffffff", "important");
			}
            if (isSimpleLinkBox) {
                node.style.setProperty("box-shadow", "none", "important");
            } else {
                applyConfiguredBoxShadow(node, prefix);
            }
            if (isLinkBox && !isSimpleLinkBox && !stitchPreset && !ingressPreset) {
                if (node.matches(":hover")) applyConfiguredLinkBoxHover(node, prefix);
                if (!node.dataset.pzPreviewBoxShadowHoverBound) {
                    node.addEventListener("pointerenter", () => {
                        applyConfiguredLinkBoxHover(node, prefix);
                    });
                    node.addEventListener("pointerleave", () => {
                        applyPartBox(card, selector, prefix);
                    });
                    node.dataset.pzPreviewBoxShadowHoverBound = "1";
                }
            }
            if (isSimpleLinkBox) {
                applySimpleLinkBox(node, prefix, node.matches(":hover"));
                if (!node.dataset.pzPreviewSimpleHoverBound) {
                    node.addEventListener("pointerenter", () => {
                        if (value("special-format") === "smp") applySimpleLinkBox(node, prefix, true);
                    });
                    node.addEventListener("pointerleave", () => {
                        if (value("special-format") === "smp") applySimpleLinkBox(node, prefix);
                    });
                    node.dataset.pzPreviewSimpleHoverBound = "1";
                }
            }
            if (stitchPreset) {
                applyStitchLinkBox(node, prefix, stitchPreset, node.matches(":hover"));
                if (!node.dataset.pzPreviewStitchHoverBound) {
                    node.addEventListener("pointerenter", () => {
                        const preset = stitchPresets[value("special-format")]?.[prefix];
                        if (preset) applyStitchLinkBox(node, prefix, preset, true);
                    });
                    node.addEventListener("pointerleave", () => {
                        const preset = stitchPresets[value("special-format")]?.[prefix];
                        if (preset) applyStitchLinkBox(node, prefix, preset);
                    });
                    node.dataset.pzPreviewStitchHoverBound = "1";
                }
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
                    heading.style.setProperty("display", "inline-flex", "important");
                    heading.style.alignItems = "center";
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
        const applyIngressPreset = preset => {
            win.querySelectorAll("[data-pz-preview-card]").forEach(card => {
                const prefix = card.dataset.pzPreviewCard;
                const linkPreset = preset?.[prefix];
                if (!linkPreset) return;

                const wrap = card.querySelector(".lkc-external-wrap, .lkc-internal-wrap");
                applyIngressLinkBox(wrap, prefix, { ...linkPreset, color: preset.color }, wrap?.matches(":hover"));
                if (wrap && !wrap.dataset.pzPreviewIngressHoverBound) {
                    wrap.addEventListener("pointerenter", () => {
                        const currentPreset = ingressPresets[value("special-format")];
                        const currentLinkPreset = currentPreset?.[prefix];
                        if (currentLinkPreset) {
                            applyIngressLinkBox(wrap, prefix, { ...currentLinkPreset, color: currentPreset.color }, true);
                        }
                    });
                    wrap.addEventListener("pointerleave", () => {
                        const currentPreset = ingressPresets[value("special-format")];
                        const currentLinkPreset = currentPreset?.[prefix];
                        if (currentLinkPreset) {
                            applyIngressLinkBox(wrap, prefix, { ...currentLinkPreset, color: currentPreset.color });
                        }
                    });
                    wrap.dataset.pzPreviewIngressHoverBound = "1";
                }
            });

            win.querySelectorAll(".lkc-title, .lkc-url, .lkc-url-info, .lkc-excerpt, .lkc-more, .lkc-info, .lkc-domain, .lkc-added").forEach(node => {
                node.style.color = preset.color;
            });
        };
        const applySpecialFormat = () => {
            const specialFormat = value("special-format");
            if (specialFormat === "JIN") {
                applyHeadlinePreset();
            }
            if (["glass", "metal", "mac", "browser", "ref", "ct1", "ct2", "ct3", "ct4", "tac", "floating", "floating2", "fbd"].includes(specialFormat)) {
                win.querySelectorAll("[data-pz-preview-heading]").forEach(heading => {
                    heading.style.zIndex = ["glass", "metal"].includes(specialFormat)
                        ? "6"
                        : (["ref", "ct1", "ct2", "ct3", "ct4", "tac"].includes(specialFormat) ? "3" : "2");
                });
            }
            if (specialFormat === "browser") {
                win.querySelectorAll("[data-pz-preview-card]").forEach(card => {
                    const prefix = card.dataset.pzPreviewCard;
                    const cardBody = card.querySelector(".lkc-card");
                    const info = card.querySelector(".lkc-browser-tab");
                    const content = card.querySelector("[data-pz-preview-content], .lkc-content");
                    const domain = card.querySelector(".lkc-browser-site-name");
                    if (cardBody) {
                        cardBody.style.margin = "0";
                        cardBody.style.position = "relative";
                        cardBody.style.overflow = "hidden";
                        cardBody.style.borderRadius = `${Math.max(0, intValue(`${prefix}-border-radius`, 0))}px`;
                        cardBody.style.background = checked(`${prefix}-bg-enabled`)
                            ? (value(`${prefix}-bg-color`) || "transparent")
                            : "transparent";
                    }
                    if (info) {
                        info.style.setProperty("display", "flex", "important");
                        info.style.position = "relative";
                        info.style.width = "min(220px, calc(100% - 28px))";
                        info.style.minHeight = "30px";
                        info.style.margin = "-2px 0 0 4px";
                        info.style.padding = "5px 30px 5px 10px";
                        info.style.alignItems = "center";
                        info.style.gap = "6px";
                        info.style.borderRadius = "8px 8px 0 0";
                        info.style.background = "#fff";
                        info.style.boxSizing = "border-box";
                    }
                    if (content) {
                        content.style.position = "relative";
                        content.style.margin = "0 -8px -8px";
                        content.style.padding = "42px 8px 8px";
                        content.style.background = "#fff";
                    }
                    const contentUrl = content?.querySelector(".lkc-browser-url");
                    if (contentUrl) {
                        contentUrl.style.position = "absolute";
                        contentUrl.style.zIndex = "1";
                        contentUrl.style.top = "8px";
                        contentUrl.style.right = "22px";
                        contentUrl.style.left = "92px";
                        contentUrl.style.margin = "0";
                        contentUrl.style.padding = "0";
                        contentUrl.style.background = "transparent";
                        contentUrl.style.setProperty("color", "#3c4043", "important");
                        contentUrl.style.fontSize = "11px";
                        contentUrl.style.lineHeight = "18px";
                    }
                    if (domain) {
                        domain.style.padding = "0";
                        domain.style.background = "transparent";
                        domain.style.border = "0";
                        domain.style.setProperty("color", "#3c4043", "important");
                    }
                });
            }
            const ingressPreset = ingressPresets[specialFormat];
            if (ingressPreset) {
                applyIngressPreset(ingressPreset);
            }
            schedulePreviewCssCallback();
        };
        const updatePreview = () => {
            const thumbnailPosition = value("thumbnail-position");
            const infoControl = Array.from(form.querySelectorAll('select[name="properties[info-position]"]')).find(el => el.getClientRects?.().length || el.offsetParent !== null);
            const infoPosition = String(infoControl ? infoControl.value : value("info-position") ?? "").trim();
            const showInfo = !["", "0", "none", "なし"].includes(infoPosition.toLowerCase());
            const displayDate = value("date-pattern");
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
                const info = card.querySelector("[data-pz-preview-info], .lkc-info");
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
                resetStyle(wrap, ["display", "position", "maxWidth", "width", "height", "margin", "padding", "transform", "background", "backgroundColor", "backgroundImage", "backgroundBlendMode", "backdropFilter", "webkitBackdropFilter", "border", "borderColor", "borderStyle", "borderWidth", "borderRadius", "boxShadow", "opacity", "overflow", "transition"]);
                resetStyle(cardBody, ["position", "overflow", "marginTop", "marginBottom", "marginLeft", "marginRight", "padding", "border", "borderRadius", "background", "backgroundColor", "boxShadow", "transform"]);
                resetStyle(content, ["position", "height", "margin", "padding", "boxShadow", "background", "backgroundColor", "borderTop", "borderBottom", "borderRadius"]);
                resetStyle(info, ["display", "position", "width", "minHeight", "margin", "padding", "color", "background", "backgroundColor", "alignItems", "gap", "borderRadius", "boxSizing"]);
                resetStyle(thumbnail, ["display", "float", "width", "margin", "transform", "background", "backgroundColor", "backgroundImage", "border", "borderColor", "borderStyle", "borderWidth", "borderRadius", "boxShadow", "opacity", "transition"]);
                resetStyle(thumbnailImg, ["width", "height", "borderRadius"]);
                resetStyle(url, ["position", "zIndex", "top", "right", "left", "margin"]);
				const thumbnailBorderRadius = intValue(`${prefix}-thumbnail-border-radius`, 0);
				if (thumbnail) thumbnail.style.borderRadius = `${thumbnailBorderRadius}px`;
				if (thumbnailImg) thumbnailImg.style.borderRadius = `${thumbnailBorderRadius}px`;

                card.style.marginTop = cssSize("margin-top") || "";
                card.style.marginBottom = cssSize("margin-bottom") || "";
                card.style.paddingLeft = cssSize("margin-left") || "";
                card.style.paddingRight = cssSize("margin-right") || "";
					card.querySelectorAll(".lkc-info, .lkc-title, .lkc-url, .lkc-url-info, .lkc-date, .lkc-excerpt").forEach(node => {
						node.style.removeProperty("text-shadow");
					});

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
					card.querySelectorAll(".lkc-siteicon, .lkc-share").forEach(node => { node.style.removeProperty("display"); });
					if (info) { info.style.removeProperty("text-align"); }
					card.querySelectorAll(".lkc-domain").forEach(node => { node.style.removeProperty("color"); });
					card.querySelectorAll(".lkc-domain").forEach(node => {
						node.style.removeProperty("margin-left");
						node.style.removeProperty("margin-right");
						node.style.removeProperty("text-align");
						node.style.removeProperty("padding");
						node.style.removeProperty("background");
						node.style.removeProperty("border");
					});

                    if (specialFormat === "mac") {
						if (info) info.style.setProperty("text-align", "center", "important");
						card.querySelectorAll(".lkc-siteicon, .lkc-share").forEach(node => { node.style.setProperty("display", "none", "important"); });
						card.querySelectorAll(".lkc-domain").forEach(node => { node.style.setProperty("color", "#000", "important"); });
                    } else if (specialFormat === "metal") {
                        wrap.style.backgroundImage = "linear-gradient(165deg, #f1f3f5 0%, #b7bec5 45%, #e7eaed 52%, #9aa3ac 100%)";
                        wrap.style.backgroundBlendMode = "multiply";
                        wrap.style.boxShadow = "0 2px 5px rgba(0,0,0,.25)";
                    } else if (specialFormat === "glass") {
                        applyGlassBackground(wrap, prefix);
                        [cardBody, content, info].forEach(node => {
                            if (!node) return;
                            node.style.setProperty("background-color", "transparent", "important");
                            node.style.setProperty("background-image", "none", "important");
                        });
                        wrap.style.backdropFilter = "blur(2px) saturate(125%)";
                        wrap.style.webkitBackdropFilter = "blur(2px) saturate(125%)";
                        wrap.style.boxShadow = "0 8px 24px rgba(31,56,85,.18)";
                        card.querySelectorAll(".lkc-info, .lkc-title, .lkc-url, .lkc-url-info, .lkc-date, .lkc-excerpt").forEach(node => {
                            node.style.setProperty("text-shadow", "4px 4px 5px #00000088", "important");
                        });
                    } else if (specialFormat === "ppc" || specialFormat === "tac") {
                        wrap.style.boxShadow = "0 2px 6px rgba(0, 0, 0, 0.8)";
                    } else if (specialFormat === "accent") {
                        if (wrap) wrap.style.position = "relative";
                        if (wrap) wrap.style.setProperty("background-color", "#ffffff", "important");
                        if (cardBody) {
                            cardBody.style.setProperty("padding-left", "24px", "important");
                            cardBody.style.setProperty("background-color", "transparent", "important");
                        }
                    } else if (["floating", "floating2", "fbd"].includes(specialFormat)) {
                        if (wrap) {
                            wrap.style.position = "relative";
                            wrap.style.display = "flow-root";
                            wrap.style.margin = "12px";
                            wrap.style.padding = "12px";
                            wrap.style.setProperty("border", "none", "important");
                            wrap.style.borderRadius = `${intValue(`${prefix}-border-radius`, 0)}px`;
                            wrap.style.boxShadow = "none";
                            wrap.style.overflow = "visible";
                            wrap.querySelector(".pz-preview-floating-frame")?.remove();
                        }
                        if (cardBody) {
                            cardBody.style.margin = "0";
                            cardBody.style.padding = "0";
                            cardBody.style.boxShadow = "none";
                            cardBody.style.position = "relative";
                            cardBody.style.zIndex = "1";
							if (specialFormat === "floating2") {
								cardBody.style.transform = "translate(-12px, -12px)";
							}
                        }
                        if (content) {
                            content.style.position = "relative";
                            content.style.zIndex = "1";
                        }
                        if (info) {
                            info.style.borderRadius = "10px";
                        }
                    }
                }

                if (cardBody) {
                    if (specialFormat === "cmp") {
                        cardBody.style.margin = "0";
                        cardBody.style.padding = "0";
                    } else {
                        cardBody.style.marginTop = cssSize("card-top") || "8px";
                        cardBody.style.marginBottom = cssSize("card-bottom") || "8px";
                        cardBody.style.marginLeft = cssSize("card-left") || "8px";
                        cardBody.style.marginRight = cssSize("card-right") || "8px";
                    }
                }

                if (specialFormat === "cmp") {
					if (wrap) {
						wrap.style.margin = "0";
						wrap.style.overflow = "hidden";
					}
                    if (content) {
                        content.style.height = "100px";
                        content.style.margin = "0";
                        content.style.padding = "0";
                    }
					if (info) info.style.setProperty("display", "none", "important");
                    if (thumbnail) {
						const compactBorderRadius = intValue(`${prefix}-border-radius`, 0);
                        thumbnail.style.display = "block";
                        thumbnail.style.float = "left";
                        thumbnail.style.width = "100px";
                        thumbnail.style.height = "108px";
                        thumbnail.style.margin = "-2px 8px 0 -1px";
						thumbnail.style.borderRadius = `${compactBorderRadius}px ${thumbnailBorderRadius}px ${thumbnailBorderRadius}px ${compactBorderRadius}px`;
						if (thumbnailImg) {
							thumbnailImg.style.borderRadius = `${compactBorderRadius}px ${thumbnailBorderRadius}px ${thumbnailBorderRadius}px ${compactBorderRadius}px`;
						}
                    }
                    if (thumbnailImg) {
                        const compactBorderColor = value(`${prefix}-border-color`) || "#999";
                        const compactBorderWidth = intValue(`${prefix}-border-width`, 1);
                        thumbnailImg.style.boxSizing = "border-box";
                        thumbnailImg.style.borderRight = `${compactBorderWidth}px solid ${compactBorderColor}`;
                    }
                    if (title) title.style.margin = "0 0 0 108px";
                    if (url) url.style.margin = "0 0 0 108px";
                    if (excerpt) excerpt.style.margin = "0 0 0 108px";
                }

                if (specialFormat !== "cmp" && !usePresetLayout && thumbnail && thumbnailImg) {
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

                if (specialFormat !== "cmp" && content && !usePresetLayout) {
                    const totalHeight = thumbnailPosition === "3" ? contentHeight + thumbnailHeight : contentHeight;
                    if (totalHeight > 0) content.style.height = `${totalHeight}px`;
                    if (checked("content-inset")) {
                        content.style.padding = "6px";
                        content.style.boxShadow = "inset 4px 4px 4px rgba(255,255,255,1)";
                        content.style.backgroundColor = "rgba(255,255,255,0.8)";
                    }
                    content.style.margin = checked("separator") ? "0" : (infoPosition === "1" ? "6px 0 0 0" : (infoPosition === "2" ? "0 0 8px 0" : "0"));
                }

                if (specialFormat !== "cmp") applyDisplay(info, showInfo);
				if (specialFormat === "mac") {
					card.querySelectorAll(".lkc-share, .lkc-siteicon").forEach(node => node.style.setProperty("display", "none", "important"));
					if (info) info.style.setProperty("text-align", "center", "important");
					card.querySelectorAll(".lkc-domain").forEach(node => {
						node.style.setProperty("margin-left", "auto", "important");
						node.style.setProperty("margin-right", "auto", "important");
						node.style.setProperty("text-align", "center", "important");
					});
				}
                info?.querySelectorAll?.(".lkc-siteicon").forEach(icon => {
                    icon.style.flexShrink = "0";
                    icon.style.flexGrow = "0";
                });
                if (info && content && title && cardBody) {
					if (infoPosition === "1") {
						cardBody.insertBefore(info, content);
					} else if (infoPosition === "2") {
                        cardBody.appendChild(info);
                    } else if (infoPosition === "3") {
                        content.insertBefore(info, title);
                    }
                }
                const showDate = displayDate !== "";
                card.querySelectorAll(".lkc-date").forEach(dateNode => {
                    if (showDate) {
                        const postIconSelect = form.querySelector('[name="properties[date-icon-post]"]');
                        const modifiedIconSelect = form.querySelector('[name="properties[date-icon-update]"]');
                        const postIcon = postIconSelect?.value ? postIconSelect.selectedOptions[0]?.textContent || "" : "";
                        const modifiedIcon = modifiedIconSelect?.value ? modifiedIconSelect.selectedOptions[0]?.textContent || "" : "";
                        const postDate = labels.previewPostDate || fallbackPreviewDate(1);
                        const modifiedDate = labels.previewModifiedDate || fallbackPreviewDate(0);
                        if (displayDate === "u") {
                            dateNode.textContent = `${modifiedIcon}${modifiedDate}`;
                        } else if (displayDate === "pu") {
                            dateNode.textContent = `${postIcon}${postDate}\u2002${modifiedIcon}${modifiedDate}`;
                        } else {
                            dateNode.textContent = `${postIcon}${postDate}`;
                        }
                    }
                    applyDisplay(dateNode, showDate);
                });
                syncShare(card);

                if (heading) {
                    resetStyle(heading, ["alignItems", "position", "top", "left", "height", "padding", "zIndex"]);
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
				if (specialFormat === "mac") {
					card.querySelectorAll(".lkc-share, .lkc-siteicon").forEach(node => node.style.setProperty("display", "none", "important"));
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
            let infoVisibilityStyle = win.querySelector("#pz-preview-info-visibility");
            if (!infoVisibilityStyle) {
                infoVisibilityStyle = win.ownerDocument.createElement("style");
                infoVisibilityStyle.id = "pz-preview-info-visibility";
                win.appendChild(infoVisibilityStyle);
            }
            infoVisibilityStyle.textContent = showInfo ? ".lkc-info { display: flex !important; }" : ".lkc-info { display: none !important; }";
        };

        const syncPreview = () => {
            // Keep the current card until the selected layout and CSS are ready.
            // The legacy DOM updater restores fixed items before the AJAX render.
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
        getPreviewHoverStyleElement().textContent = getPreviewHoverCss();
        schedulePreviewCssCallback(0);
    }

    initSettingsPreviewWindow();
});
