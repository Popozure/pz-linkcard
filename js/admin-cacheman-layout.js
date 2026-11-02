(() => {
    function initCachemanLayout() {
        const editor = document.querySelector(".pz-man-cache-poststuff");
        if (!editor) return;

        const columns = new Map();
        editor.querySelectorAll(".pz-man-cache-sortables[data-pz-cache-column]").forEach(column => {
            const name = column.dataset.pzCacheColumn;
            if (name === "main" || name === "side") columns.set(name, column);
        });
        if (columns.size !== 2) return;

        const boxes = new Map();
        const originalColumns = new Map();
        const getBoxes = column => Array.from(column.children).filter(box =>
            box.matches(".pz-man-cache-postbox") && box.id
        );
        columns.forEach(column => getBoxes(column).forEach(box => {
            boxes.set(box.id, box);
            originalColumns.set(box.id, column);
        }));

        const visibilityBoxes = new Map(boxes);
        visibilityBoxes.delete("pz-man-cache-submit");
        const submitBox = boxes.get("pz-man-cache-submit");
        if (submitBox) {
            submitBox.classList.remove("pz-man-cache-block-hidden");
            submitBox.setAttribute("aria-hidden", "false");
        }
        const excerpt = editor.querySelector("#pz-man-cache-excerpt-box");
        if (excerpt) visibilityBoxes.set(excerpt.id, excerpt);
        const optionsRoot = document.querySelector(".pz-man-cache-screen-options");
        const visibilityControls = Array.from(optionsRoot?.querySelectorAll("input[type='checkbox'][data-pz-cache-block]") || [])
            .filter(control => visibilityBoxes.has(control.dataset.pzCacheBlock));
        const hiddenBoxes = new Set();
        visibilityBoxes.forEach((box, id) => {
            const control = visibilityControls.find(input => input.dataset.pzCacheBlock === id);
            if (control ? !control.defaultChecked : box.classList.contains("pz-man-cache-block-hidden")) {
                hiddenBoxes.add(id);
            }
        });
        const defaultHiddenBoxes = new Set(hiddenBoxes);
        const collapsedBoxes = new Set();

        const storageKey = editor.dataset.pzCacheLayoutKey;
        if (storageKey) {
            try {
                const saved = JSON.parse(window.localStorage.getItem(storageKey));
                if (saved?.version === 1 && saved.columns &&
                    Array.from(columns.keys()).every(name => Array.isArray(saved.columns[name]))) {
                    const placed = new Set();
                    columns.forEach((column, name) => saved.columns[name].forEach(id => {
                        if (typeof id !== "string" || placed.has(id) || !boxes.has(id)) return;
                        column.appendChild(boxes.get(id));
                        placed.add(id);
                    }));
                    // New blocks remain available at the end of their original column.
                    boxes.forEach((box, id) => {
                        if (!placed.has(id)) originalColumns.get(id).appendChild(box);
                    });
                }
                if (saved?.version === 1 && Array.isArray(saved.hidden)) {
                    hiddenBoxes.clear();
                    saved.hidden.forEach(id => {
                        if (typeof id === "string" && visibilityBoxes.has(id)) hiddenBoxes.add(id);
                    });
                }
                if (saved?.version === 1 && Array.isArray(saved.collapsed)) {
                    saved.collapsed.forEach(id => {
                        if (typeof id === "string" && boxes.has(id)) collapsedBoxes.add(id);
                    });
                }
            } catch {
                // Layout controls still work when stored data or storage is unavailable.
            }
        }

        const applyVisibility = () => {
            visibilityBoxes.forEach((box, id) => {
                const hidden = hiddenBoxes.has(id);
                box.classList.toggle("pz-man-cache-block-hidden", hidden);
                box.setAttribute("aria-hidden", hidden ? "true" : "false");
            });
            visibilityControls.forEach(control => {
                control.checked = !hiddenBoxes.has(control.dataset.pzCacheBlock);
            });
        };
        applyVisibility();

        const applyCollapsed = () => {
            boxes.forEach((box, id) => {
                const header = box.querySelector(".postbox-header");
                const body = Array.from(box.children).find(child => child.matches(".inside"));
                if (!header || !body) return;
                const collapsed = collapsedBoxes.has(id);
                box.classList.toggle("pz-man-cache-collapsed", collapsed);
                body.hidden = collapsed;
                if (!body.id) body.id = `${id}-body`;
                const heading = header.querySelector("h2");
                let toggle = header.querySelector(".pz-man-cache-collapse-toggle");
                if (!toggle) {
                    toggle = document.createElement("button");
                    toggle.type = "button";
                    toggle.className = "pz-man-cache-collapse-toggle";
                    toggle.setAttribute("aria-label", heading?.textContent.trim() || id);
                    header.appendChild(toggle);
                }
                toggle.textContent = collapsed ? "▼" : "▲";
                toggle.setAttribute("aria-controls", body.id);
                toggle.setAttribute("aria-expanded", collapsed ? "false" : "true");
                if (heading) {
                    heading.setAttribute("role", "button");
                    heading.setAttribute("tabindex", "0");
                    heading.setAttribute("aria-controls", body.id);
                    heading.setAttribute("aria-expanded", collapsed ? "false" : "true");
                }
            });
        };
        applyCollapsed();

        const saveLayout = () => {
            if (!storageKey) return;
            const layout = {
                version: 1,
                columns: {},
                hidden: Array.from(visibilityBoxes.keys()).filter(id => hiddenBoxes.has(id)),
                collapsed: Array.from(boxes.keys()).filter(id => collapsedBoxes.has(id)),
            };
            columns.forEach((column, name) => {
                layout.columns[name] = getBoxes(column).map(box => box.id);
            });
            try {
                window.localStorage.setItem(storageKey, JSON.stringify(layout));
            } catch {
                // Keep the current arrangement even when it cannot be saved.
            }
        };

        let $columns = null;
        const refreshSortable = () => $columns?.sortable("refresh");
        boxes.forEach((box, id) => {
            const header = box.querySelector(".postbox-header");
            if (!header) return;
            const toggleCollapsed = () => {
                if (collapsedBoxes.has(id)) collapsedBoxes.delete(id);
                else collapsedBoxes.add(id);
                applyCollapsed();
                refreshSortable();
                saveLayout();
            };
            header.querySelector(".pz-man-cache-collapse-toggle")?.addEventListener("click", event => {
                event.preventDefault();
                event.stopPropagation();
                toggleCollapsed();
            });
            header.addEventListener("dblclick", event => {
                if (editor.classList.contains("pz-man-cache-sorting") ||
                    event.target.closest("a, button, input, select, textarea, label, [contenteditable]")) return;
                event.preventDefault();
                toggleCollapsed();
            });
            header.querySelector("h2")?.addEventListener("keydown", event => {
                if (event.key !== "Enter" && event.key !== " ") return;
                event.preventDefault();
                toggleCollapsed();
            });
        });
        visibilityControls.forEach(control => control.addEventListener("change", () => {
            const id = control.dataset.pzCacheBlock;
            if (control.checked) hiddenBoxes.delete(id);
            else hiddenBoxes.add(id);
            applyVisibility();
            refreshSortable();
            saveLayout();
        }));
        document.querySelector("#pz-man-cache-layout-reset")?.addEventListener("click", () => {
            boxes.forEach((box, id) => originalColumns.get(id).appendChild(box));
            hiddenBoxes.clear();
            defaultHiddenBoxes.forEach(id => hiddenBoxes.add(id));
            collapsedBoxes.clear();
            applyVisibility();
            applyCollapsed();
            refreshSortable();
            saveLayout();
        });

        const optionsToggle = document.querySelector("#pz-man-cache-screen-options-toggle");
        const optionsPanel = document.querySelector("#pz-man-cache-screen-options-panel");
        if (optionsRoot && optionsToggle && optionsPanel) {
            const setOpen = open => {
                if (open) {
                    const cacheman = editor.closest(".pz-cacheman") || document;
                    cacheman.querySelectorAll(".pz-page-help-toggle[aria-expanded='true']").forEach(toggle => toggle.click());
                }
                optionsPanel.hidden = !open;
                optionsPanel.classList.toggle("is-open", open);
                optionsToggle.setAttribute("aria-expanded", open ? "true" : "false");
            };
            setOpen(false);
            optionsToggle.addEventListener("click", event => {
                event.preventDefault();
                setOpen(optionsPanel.hidden);
            });
            document.addEventListener("click", event => {
                if (!optionsPanel.hidden && !optionsRoot.contains(event.target)) setOpen(false);
            });
            document.addEventListener("keydown", event => {
                if (optionsPanel.hidden || event.isComposing) return;
                if (event.key === "Escape" || event.key === "Esc") {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    setOpen(false);
                    optionsToggle.focus();
                } else if (event.key === "F1" && !event.ctrlKey && !event.altKey && !event.metaKey && !event.shiftKey) {
                    setOpen(false);
                }
            }, { capture: true });
        }

        const $ = window.jQuery;
        if (typeof $?.fn?.sortable !== "function") return;
        $columns = $(Array.from(columns.values()));
        $columns.sortable({
            connectWith: ".pz-man-cache-poststuff .pz-man-cache-sortables",
            items: "> .pz-man-cache-postbox:visible",
            handle: ".postbox-header",
            cancel: "a, button, input, select, textarea, label, [contenteditable]",
            helper: "original",
            placeholder: "pz-man-cache-sort-placeholder",
            forcePlaceholderSize: true,
            dropOnEmpty: true,
            tolerance: "pointer",
            distance: 6,
            cursor: "move",
            scrollSensitivity: 60,
            scrollSpeed: 15,
            start(event, ui) {
                editor.classList.add("pz-man-cache-sorting");
                ui.placeholder.height(ui.item.outerHeight());
            },
            stop() {
                editor.classList.remove("pz-man-cache-sorting");
                saveLayout();
                refreshSortable();
            },
        });
        editor.classList.add("pz-man-cache-layout-ready");

        let resizeFrame = null;
        window.addEventListener("resize", () => {
            if (resizeFrame !== null) return;
            resizeFrame = window.requestAnimationFrame(() => {
                resizeFrame = null;
                refreshSortable();
            });
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initCachemanLayout, { once: true });
    } else {
        initCachemanLayout();
    }
})();
