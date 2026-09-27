/* Pz-LinkCard settings search. */

document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.querySelector("#pz-search-box");
    const searchBtn = document.querySelector("#pz-search-btn");
    const searchPrevBtn = document.querySelector("#pz-search-prev-btn");
    const searchStatus = document.querySelector("#pz-search-status");
    if (!searchInput || !searchBtn || !searchPrevBtn || !searchStatus) return;

    const searchBox = searchInput.closest(".pz-infobar-search-box");
    const historyList = document.createElement("div");
    const historyStorageKey = "pz-linkcard-settings-search-history";
    const historyLimit = 8;
    const targetSelector = "h1, h2, h3, h4, h5, h6, label, th, span, p";
    const state = { matches: [], currentIndex: -1, lastKeyword: "" };
    const historyState = { items: [], selectedIndex: -1, open: false };
    let focusedElement = null;
    let focusTimer = null;
    historyList.className = "pz-search-history";
    historyList.setAttribute("role", "listbox");
    historyList.hidden = true;
    searchBox?.appendChild(historyList);
    const escapeRegExp = value => String(value).replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
    const getKeyword = () => searchInput.value.trim().toLowerCase();
    const getTargets = (root = document) => Array.from(root.querySelectorAll(targetSelector))
        .filter(element => !element.closest(".pz-changelog"));

    const resetHighlight = element => {
        element.querySelectorAll("span.highlight").forEach(span => {
            span.replaceWith(document.createTextNode(span.textContent));
        });
        element.normalize();
    };
    const resetAllHighlights = () => getTargets().forEach(resetHighlight);

    const highlightKeyword = (element, keyword) => {
        const regex = new RegExp(`(${escapeRegExp(keyword)})`, "gi");
        Array.from(element.childNodes).forEach(node => {
            if (node.nodeType !== Node.TEXT_NODE || !node.textContent) return;
            const source = node.textContent;
            const fragment = document.createDocumentFragment();
            let lastIndex = 0;
            source.replace(regex, (match, text, offset) => {
                if (offset > lastIndex) fragment.appendChild(document.createTextNode(source.slice(lastIndex, offset)));
                const span = document.createElement("span");
                span.className = "highlight";
                span.textContent = text;
                fragment.appendChild(span);
                lastIndex = offset + text.length;
                return match;
            });
            if (!lastIndex) return;
            if (lastIndex < source.length) fragment.appendChild(document.createTextNode(source.slice(lastIndex)));
            node.replaceWith(fragment);
        });
    };

    const getSearchPages = () => Array.from(document.querySelectorAll("#pz-tabbar .pz-tab"))
        .filter(tab => tab.getClientRects().length && getComputedStyle(tab).display !== "none")
        .map(tab => document.querySelector(tab.getAttribute("href")))
        .filter(Boolean);

    const searchSettings = keyword => {
        const normalizedKeyword = keyword.trim().toLowerCase();
        if (!normalizedKeyword) return [];
        resetAllHighlights();
        const found = [];
        getSearchPages().forEach(page => {
            getTargets(page).forEach(element => {
                if (!element.innerText.toLowerCase().includes(normalizedKeyword)) return;
                highlightKeyword(element, normalizedKeyword);
                found.push(element);
            });
        });
        return found;
    };

    const updateStatus = () => {
        searchStatus.textContent = state.matches.length ? `${state.currentIndex + 1}/${state.matches.length}` : "";
        searchPrevBtn.disabled = !state.matches.length || getKeyword() !== state.lastKeyword;
    };

    const openTabForElement = element => {
        const page = element.closest(".pz-page");
        if (!page?.id || page.classList.contains("pz-page-active")) return;
        document.querySelector(`#pz-tabbar .pz-tab[href="#${CSS.escape(page.id)}"]`)?.click();
    };

    const focusResult = element => {
        if (!element) return;
        if (focusTimer !== null) {
            window.clearTimeout(focusTimer);
            focusTimer = null;
        }
        if (focusedElement) focusedElement.style.backgroundColor = "";
        focusedElement = element;
        openTabForElement(element);
        window.setTimeout(() => {
            element.scrollIntoView({ behavior: "smooth", block: "center" });
            element.style.transition = "background-color 0.5s";
            element.style.backgroundColor = "orange";
            focusTimer = window.setTimeout(() => {
                element.style.backgroundColor = "";
                if (focusedElement === element) focusedElement = null;
                focusTimer = null;
            }, 5000);
        }, 0);
    };

    const runSearch = (keyword = getKeyword()) => {
        if (!keyword) return false;
        if (keyword !== state.lastKeyword) {
            state.matches = searchSettings(keyword);
            state.lastKeyword = keyword;
            state.currentIndex = -1;
        }
        return state.matches.length > 0;
    };

    const moveResult = direction => {
        if (!runSearch()) {
            updateStatus();
            return;
        }
        state.currentIndex = (state.currentIndex + direction + state.matches.length) % state.matches.length;
        focusResult(state.matches[state.currentIndex]);
        updateStatus();
    };

    const clearSearch = () => {
        resetAllHighlights();
        state.matches = [];
        state.currentIndex = -1;
        state.lastKeyword = "";
        updateStatus();
    };

    const readHistory = () => {
        try {
            const value = JSON.parse(window.localStorage.getItem(historyStorageKey) || "[]");
            return Array.isArray(value) ? value.filter(item => typeof item === "string" && item.trim()).slice(0, historyLimit) : [];
        } catch (error) {
            return [];
        }
    };

    const writeHistory = () => {
        try {
            window.localStorage.setItem(historyStorageKey, JSON.stringify(historyState.items));
        } catch (error) {
            // Ignore unavailable or full browser storage.
        }
    };

    const getVisibleHistory = () => historyState.items;

    const closeHistory = () => {
        historyState.open = false;
        historyState.selectedIndex = -1;
        historyList.hidden = true;
        historyList.replaceChildren();
    };

    const updateHistorySelection = () => {
        Array.from(historyList.children).forEach((option, index) => {
            const selected = index === historyState.selectedIndex;
            option.classList.toggle("is-selected", selected);
            option.setAttribute("aria-selected", selected ? "true" : "false");
        });
    };

    const renderHistory = () => {
        const items = getVisibleHistory();
        historyList.replaceChildren();
        historyState.selectedIndex = Math.min(historyState.selectedIndex, items.length - 1);
        items.forEach((keyword, index) => {
            const option = document.createElement("button");
            option.type = "button";
            option.className = "pz-search-history-item";
            option.textContent = keyword;
            option.setAttribute("role", "option");
            option.setAttribute("aria-selected", index === historyState.selectedIndex ? "true" : "false");
            option.classList.toggle("is-selected", index === historyState.selectedIndex);
            option.addEventListener("pointerenter", () => {
                historyState.selectedIndex = index;
                updateHistorySelection();
            });
            option.addEventListener("click", () => {
                searchInput.value = keyword;
                closeHistory();
                searchFromStart(keyword);
                searchInput.focus({ preventScroll: true });
            });
            historyList.appendChild(option);
        });
        historyState.open = items.length > 0;
        historyList.hidden = !historyState.open;
    };

    const addHistory = keyword => {
        const value = keyword.trim();
        if (!value) return;
        historyState.items = [value, ...historyState.items.filter(item => item.toLowerCase() !== value.toLowerCase())].slice(0, historyLimit);
        writeHistory();
    };

    const deleteSelectedHistory = () => {
        const keyword = getVisibleHistory()[historyState.selectedIndex];
        if (!keyword) return false;
        historyState.items = historyState.items.filter(item => item !== keyword);
        writeHistory();
        historyState.selectedIndex = Math.min(historyState.selectedIndex, getVisibleHistory().length - 1);
        renderHistory();
        return true;
    };

    const moveHistorySelection = direction => {
        const items = getVisibleHistory();
        if (!items.length) return false;
        historyState.selectedIndex = historyState.selectedIndex < 0
            ? (direction > 0 ? 0 : items.length - 1)
            : (historyState.selectedIndex + direction + items.length) % items.length;
        updateHistorySelection();
        historyList.querySelector(".is-selected")?.scrollIntoView({ block: "nearest" });
        return true;
    };

    const searchFromStart = keyword => {
        const value = keyword.trim();
        if (!value) return;
        addHistory(value);
        state.matches = searchSettings(value);
        state.lastKeyword = value.toLowerCase();
        state.currentIndex = state.matches.length ? 0 : -1;
        focusResult(state.matches[state.currentIndex]);
        updateStatus();
    };

    historyState.items = readHistory();

    searchBtn.addEventListener("click", () => {
        closeHistory();
        const keyword = getKeyword();
        if (keyword && keyword === state.lastKeyword && state.matches.length) {
            addHistory(searchInput.value);
            moveResult(1);
            return;
        }
        searchFromStart(searchInput.value);
    });

    searchPrevBtn.addEventListener("click", () => {
        if (searchPrevBtn.disabled) return;
        closeHistory();
        addHistory(searchInput.value);
        moveResult(-1);
    });

    searchInput.addEventListener("keydown", event => {
        if (event.key === "Escape") {
            event.preventDefault();
            if (historyState.open) closeHistory();
            else clearSearch();
            return;
        }
        if (event.key === "ArrowDown") {
            event.preventDefault();
            if (!historyState.open) renderHistory();
            moveHistorySelection(1);
            return;
        }
        if (event.key === "ArrowUp" && historyState.open) {
            event.preventDefault();
            moveHistorySelection(-1);
            return;
        }
        if (event.key === "Delete" && historyState.open && historyState.selectedIndex >= 0) {
            event.preventDefault();
            deleteSelectedHistory();
            return;
        }
        if (event.key !== "Enter") return;
        event.preventDefault();
        const selectedHistory = getVisibleHistory()[historyState.selectedIndex];
        if (historyState.open && selectedHistory) {
            searchInput.value = selectedHistory;
            closeHistory();
            searchFromStart(selectedHistory);
        } else {
            closeHistory();
            addHistory(searchInput.value);
            moveResult(event.shiftKey ? -1 : 1);
        }
        try {
            searchInput.focus({ preventScroll: true });
        } catch (error) {
            searchInput.focus();
        }
    });

    searchInput.addEventListener("input", () => {
        searchStatus.textContent = "";
        if (state.lastKeyword && getKeyword() !== state.lastKeyword) resetAllHighlights();
        searchPrevBtn.disabled = !state.matches.length || getKeyword() !== state.lastKeyword;
        historyState.selectedIndex = -1;
        if (searchInput.value.trim()) closeHistory();
        else renderHistory();
    });

    searchInput.addEventListener("focus", () => {
        if (!searchInput.value.trim()) renderHistory();
    });

    document.addEventListener("pointerdown", event => {
        if (!event.target.closest(".pz-infobar-search-box")) closeHistory();
    });

    document.addEventListener("keydown", event => {
        if (event.altKey && !event.ctrlKey && !event.metaKey && event.key.toLowerCase() === "q") {
            event.preventDefault();
            searchInput.focus();
            searchInput.select();
            if (!searchInput.value.trim()) renderHistory();
            return;
        }

        const selectedText = window.getSelection().toString().trim();
        if (event.ctrlKey && event.key.toLowerCase() === "f") {
            event.preventDefault();
            if (selectedText) searchInput.value = selectedText;
            searchInput.focus();
            searchInput.select();
            closeHistory();
            searchFromStart(searchInput.value);
            return;
        }
        if (event.key !== "F3") return;
        event.preventDefault();
        if (!state.matches.length && selectedText) searchInput.value = selectedText;
        if (!state.matches.length) {
            searchInput.focus();
            searchInput.select();
        }
        moveResult(event.shiftKey ? -1 : 1);
    });
});
