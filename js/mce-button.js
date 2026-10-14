(function() {
	let activeVisualCard = null;
	let activeVisualBookmark = null;
	let activeVisualScroll = null;
	let activeVisualShortcode = null;
	let urlDialogFocusTimer = 0;

	function rememberScrollPosition(editor) {
		const editorWindow = editor.getWin();
		activeVisualScroll = {
			pageX: window.scrollX,
			pageY: window.scrollY,
			editorX: editorWindow.scrollX,
			editorY: editorWindow.scrollY
		};
	}

	function restoreScrollPosition(editor, clear = false) {
		if (!activeVisualScroll) return;
		const position = activeVisualScroll;
		window.requestAnimationFrame(() => {
			window.scrollTo(position.pageX, position.pageY);
			editor.getWin().scrollTo(position.editorX, position.editorY);
			if (clear) activeVisualScroll = null;
		});
	}

	function focusVisualEditor(editor) {
		const iframe = editor.iframeElement || editor.getContentAreaContainer()?.querySelector("iframe");
		try { iframe?.focus({ preventScroll: true }); } catch (error) { iframe?.focus(); }
		editor.focus();
		editor.getWin().focus();
		try { editor.getBody().focus({ preventScroll: true }); } catch (error) { editor.getBody().focus(); }
	}

	function releaseUrlDialogFocus() {
		if (urlDialogFocusTimer) {
			clearTimeout(urlDialogFocusTimer);
			urlDialogFocusTimer = 0;
		}
		const modal = document.getElementById("pz-modal");
		const activeElement = document.activeElement;
		if (activeElement && modal?.contains(activeElement) && typeof activeElement.blur === "function") {
			activeElement.blur();
		}
		document.getElementById("pz-url")?.blur();
		document.getElementById("pz-insert")?.blur();
	}

	function openUrlDialog(editor, value) {
		const insertButton = document.getElementById("pz-insert");
		if (insertButton && tinymce.translate) insertButton.value = tinymce.translate("Insert Linkcard");
		document.getElementById("pz-overlay").style.display = "block";
		document.getElementById("pz-modal").style.display = "block";
		const input = document.getElementById("pz-url");
		input.value = value || "";
		updateInsertButton();
		schedulePostSearch(input.value);
		modal_move_center();
		urlDialogFocusTimer = setTimeout(() => {
			urlDialogFocusTimer = 0;
			input.focus();
			input.select();
		}, 100);
	}

	function focusInsertedCardCaret(editor, markerId) {
		setTimeout(() => {
			const marker = editor.getBody().querySelector("#" + markerId);
			if (!marker) {
				focusVisualEditor(editor);
				return;
			}
			marker.removeAttribute("id");
			focusVisualEditor(editor);
			editor.selection.setCursorLocation(marker, 0);
			focusVisualEditor(editor);
			editor.nodeChanged();
			const waitsForPreview = document.getElementById("pz-code")?.dataset.previewEnabled === "1";
			if (!waitsForPreview) marker.removeAttribute("data-pz-caret-marker");
			restoreScrollPosition(editor, !waitsForPreview);
		}, 0);
	}

	function focusAfterCard(editor, card) {
		if (!card?.isConnected) return;
		let target = card.nextElementSibling;
		if (!target || target.classList.contains("pz-lkc-mce-card")) {
			target = editor.dom.create("p", {}, '<br data-mce-bogus="1">');
			card.parentNode.insertBefore(target, card.nextSibling);
		}
		focusVisualEditor(editor);
		editor.selection.setCursorLocation(target, 0);
		focusVisualEditor(editor);
		editor.nodeChanged();
		restoreScrollPosition(editor, true);
	}

	function shortcodeAtCaret(editor, pattern) {
		if (!pattern) return null;
		const selectionRange = editor.selection.getRng();
		let block = selectionRange.commonAncestorContainer;
		if (block.nodeType !== 1) block = block.parentNode;
		block = block.closest?.("p,div") || block;
		if (!block || block === editor.getBody()) return null;

		const beforeCaret = editor.getDoc().createRange();
		beforeCaret.selectNodeContents(block);
		beforeCaret.setEnd(selectionRange.startContainer, selectionRange.startOffset);
		const caretOffset = beforeCaret.toString().length;
		const source = block.textContent || "";
		const matcher = new RegExp(pattern.source, pattern.flags);
		let match;
		let found = null;
		while ((match = matcher.exec(source)) !== null) {
			if (caretOffset > match.index && caretOffset < match.index + match[0].length) {
				found = { text: match[0], start: match.index, end: match.index + match[0].length };
				break;
			}
		}
		if (!found) return null;

		const walker = editor.getDoc().createTreeWalker(block, editor.getWin().NodeFilter.SHOW_TEXT);
		let node;
		let offset = 0;
		let startNode = null;
		let startOffset = 0;
		let endNode = null;
		let endOffset = 0;
		while ((node = walker.nextNode())) {
			const nextOffset = offset + node.nodeValue.length;
			if (!startNode && found.start <= nextOffset) {
				startNode = node;
				startOffset = found.start - offset;
			}
			if (found.end <= nextOffset) {
				endNode = node;
				endOffset = found.end - offset;
				break;
			}
			offset = nextOffset;
		}
		if (!startNode || !endNode) return null;
		const range = editor.getDoc().createRange();
		range.setStart(startNode, startOffset);
		range.setEnd(endNode, endOffset);
		editor.selection.setRng(range);
		return { text: found.text, bookmark: editor.selection.getBookmark(2, true) };
	}

	function focusInlineMarker(editor, markerId) {
		setTimeout(() => {
			const marker = editor.getBody().querySelector("#" + markerId);
			if (!marker) return;
			const range = editor.getDoc().createRange();
			range.setStartBefore(marker);
			range.collapse(true);
			focusVisualEditor(editor);
			editor.selection.setRng(range);
			marker.remove();
			focusVisualEditor(editor);
			editor.nodeChanged();
			restoreScrollPosition(editor, true);
		}, 0);
	}

	function insertParagraphAtCaretBoundary(editor, shortcode, markerId) {
		const selectionRange = editor.selection.getRng();
		if (!selectionRange.collapsed) return false;
		let block = selectionRange.startContainer;
		if (block.nodeType !== 1) block = block.parentNode;
		block = block.closest?.("p");
		if (!block) return false;

		const beforeCaret = editor.getDoc().createRange();
		beforeCaret.selectNodeContents(block);
		beforeCaret.setEnd(selectionRange.startContainer, selectionRange.startOffset);
		const caretOffset = beforeCaret.toString().length;
		const blockLength = (block.textContent || "").length;
		if (caretOffset !== 0 && caretOffset !== blockLength) return false;

		const paragraph = editor.getDoc().createElement("p");
		paragraph.appendChild(editor.getDoc().createTextNode(shortcode));
		const marker = editor.getDoc().createElement("span");
		marker.id = markerId;
		marker.setAttribute("data-mce-bogus", "all");
		marker.textContent = "\ufeff";

		if (caretOffset === 0) {
			block.parentNode.insertBefore(paragraph, block);
			block.insertBefore(marker, block.firstChild);
			return true;
		}

		const nextBlock = block.nextElementSibling;
		block.parentNode.insertBefore(paragraph, block.nextSibling);
		if (nextBlock?.nodeName === "P") {
			nextBlock.insertBefore(marker, nextBlock.firstChild);
		} else {
			const caretParagraph = editor.getDoc().createElement("p");
			caretParagraph.appendChild(marker);
			const bogusBreak = editor.getDoc().createElement("br");
			bogusBreak.setAttribute("data-mce-bogus", "1");
			caretParagraph.appendChild(bogusBreak);
			block.parentNode.insertBefore(caretParagraph, paragraph.nextSibling);
		}
		return true;
	}

	tinymce.create( "tinymce.plugins.pz_linkcard_tinymce", {
		getInfo: function() {
			return {
				longname:	"Pz-LinkCard Insert Button",
				author:		"poporon",
				authorurl:	"https://popozure.info",
				infourl:	"https://popozure.info/pz-linkcard",
				version:	"0.8"
			};
		},
		init: function(ed, url) {
			var id = "pz_linkcard_insert_shortcode";
			const config = document.getElementById("pz-code");
			const previewEnabled = config?.dataset.previewEnabled === "1";
			let names = [];
			try { names = JSON.parse(config?.dataset.shortcodes || "[]"); } catch (error) { names = []; }
			if (!names.length && config?.value) names.push(config.value);
			const escapeRegExp = value => String(value).replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
			const escapeHtml = value => String(value).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
			const pattern = names.length ? new RegExp("\\[(" + names.map(escapeRegExp).join("|") + ")\\b([^\\]]*)\\]", "gi") : null;
			const decodeCard = card => {
				try { return decodeURIComponent(card.dataset.pzShortcode || ""); } catch (error) { return ""; }
			};
			const cardHtml = shortcode => '<div class="pz-lkc-mce-card" contenteditable="false" data-mce-contenteditable="false" data-mce-resize="false" draggable="false" data-pz-shortcode="' + escapeHtml(encodeURIComponent(shortcode)) + '"><div class="pz-lkc-mce-preview">Pz-LinkCard</div></div>';
			const loadPreview = card => {
				if (!card || card.dataset.pzLoading || card.dataset.pzLoaded) return;
				card.dataset.pzLoading = "1";
				const body = new URLSearchParams({ action: "pz_lkc_mce_render_card", nonce: config?.dataset.renderNonce || "", shortcode: decodeCard(card) });
				fetch(config?.dataset.ajaxUrl || window.ajaxurl, { method: "POST", credentials: "same-origin", headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, body: body.toString() })
					.then(response => response.json())
					.then(result => {
						if (!card.isConnected || !result?.success || !result.data?.html) return;
						const preview = card.querySelector(".pz-lkc-mce-preview");
						preview.innerHTML = result.data.html;
						card.dataset.pzLoaded = "1";
						card.querySelectorAll("a").forEach(link => link.tabIndex = -1);
						const caretMarker = card.nextElementSibling;
						if (caretMarker?.hasAttribute("data-pz-caret-marker")) {
							restoreScrollPosition(ed, true);
							caretMarker.removeAttribute("data-pz-caret-marker");
						}
					})
					.finally(() => {
						if (!card.isConnected) return;
						delete card.dataset.pzLoading;
						const caretMarker = card.nextElementSibling;
						if (caretMarker?.hasAttribute("data-pz-caret-marker")) {
							restoreScrollPosition(ed, true);
							caretMarker.removeAttribute("data-pz-caret-marker");
						}
					});
			};
			const loadPreviews = () => ed.getBody().querySelectorAll(".pz-lkc-mce-card").forEach(loadPreview);
			ed.pzLoadPreview = loadPreview;

			ed.on("BeforeSetContent", event => {
				if (previewEnabled && pattern) event.content = String(event.content || "").replace(pattern, cardHtml);
			});
			ed.on("SetContent", () => {
				if (previewEnabled) setTimeout(loadPreviews, 0);
			});
			ed.on("PreProcess", event => {
				if (!previewEnabled) return;
				const cards = event.node.querySelectorAll ? event.node.querySelectorAll(".pz-lkc-mce-card") : [];
				Array.prototype.forEach.call(cards, card => {
					if (card.parentNode?.nodeName === "P") {
						card.parentNode.replaceChild(event.node.ownerDocument.createTextNode(decodeCard(card)), card);
						return;
					}
					const paragraph = event.node.ownerDocument.createElement("p");
					paragraph.textContent = decodeCard(card);
					card.parentNode.replaceChild(paragraph, card);
				});
			});
			ed.on("init", () => {
				if (!previewEnabled) return;
				if (config?.dataset.styleUrl) ed.dom.loadCSS(config.dataset.styleUrl);
				if (config?.dataset.additionalStyleUrl) ed.dom.loadCSS(config.dataset.additionalStyleUrl);
				ed.dom.addStyle(".pz-lkc-mce-card{display:block;box-sizing:border-box;margin:12px 0}.pz-lkc-mce-card a{pointer-events:none}.pz-lkc-mce-card .pz-lkc-quickmenu-indicator{display:none!important}");
				const current = ed.getContent({ format: "raw" });
				const converted = pattern ? current.replace(pattern, cardHtml) : current;
				if (converted !== current) ed.setContent(converted, { format: "raw" });
				loadPreviews();
				ed.getBody().addEventListener("dragstart", event => {
					if (event.target?.closest?.(".pz-lkc-mce-card")) event.preventDefault();
				}, true);
				ed.getBody().addEventListener("click", event => {
					const card = event.target?.closest?.(".pz-lkc-mce-card");
					if (!card) return;
					event.preventDefault();
					activeVisualCard = card;
					activeVisualBookmark = null;
					activeVisualShortcode = null;
					rememberScrollPosition(ed);
					openUrlDialog(ed, cut_url(decodeCard(card)));
				});
			});
			ed.addButton(id, {
				title: "Insert Linkcard",
				cmd: id,
				image: url + "/mce-button.png"
			});
			ed.addCommand(id, function() {
				activeVisualCard = null;
				rememberScrollPosition(ed);
				activeVisualShortcode = !previewEnabled ? shortcodeAtCaret(ed, pattern) : null;
				activeVisualBookmark = activeVisualShortcode?.bookmark || ed.selection.getBookmark(2, true);
				const st = tinymce.activeEditor.selection.getContent();
				openUrlDialog(ed, cut_url(activeVisualShortcode?.text || st));
			} );
		},
	} );
	tinymce.PluginManager.add("pz_linkcard_tinymce", tinymce.plugins.pz_linkcard_tinymce);
	tinymce.PluginManager.requireLangPack("pz_linkcard_tinymce");

	// [ESC]キーが押されたらCLOSEをクリック
	document.addEventListener("keydown", function(e) {
		if (e.key === "Escape") {
			const modal = document.getElementById("pz-modal");
			const input = document.getElementById("pz-url");
			if (modal && modal.style.display !== "none" && input) {
				const value = input.value.trim();
				if (value && !isLikelyUrl(value)) {
					e.preventDefault();
					e.stopPropagation();
					input.value = "";
					updateInsertButton();
					clearPostSearch();
					input.focus();
					return;
				}
			}
			document.getElementById("pz-close").click();
		}
	});

	// 右上の「×」、もしくは画面の暗い部分をクリックしたらモーダルを閉じる
	document.querySelectorAll("#pz-overlay, #pz-close").forEach(el => {
		el.addEventListener("click", function() {
			const editor = tinymce.activeEditor;
			activeVisualCard = null;
			activeVisualBookmark = null;
			activeVisualShortcode = null;
			clearPostSearch();
			releaseUrlDialogFocus();
			document.getElementById("pz-overlay").style.display = "none";
			document.getElementById("pz-modal").style.display = "none";
			setTimeout(() => {
				focusVisualEditor(editor);
				restoreScrollPosition(editor, true);
			}, 0);
		});
	});

	// 貼り付け
	document.getElementById("pz-url").addEventListener("paste", function(e) {
		if (this.value === "") {
			let cb;
			if (e.clipboardData && e.clipboardData.getData) {
				cb = e.clipboardData.getData("text/plain");
			}
			const url = cut_url(cb);
			if (url) {
				this.value = url;
				this.select();
				updateInsertButton();
				clearPostSearch();
				e.preventDefault();
			}
		}
	});

	// URL以外が入力されたとき、記事タイトルを検索
	const postSearchResults = document.getElementById("pz-post-search-results");
	let postSearchTimer = null;
	let postSearchController = null;

	function isUrl(value) {
		return /^(https?|file|ftp|data|ogg):\/\//i.test(String(value || "").trim());
	}

	function isLikelyUrl(value) {
		const text = String(value || "").trim();
		if (!isUrl(text)) return false;
		try {
			const parsed = new URL(text);
			return ["http:", "https:", "file:", "ftp:", "data:", "ogg:"].includes(parsed.protocol);
		} catch (error) {
			return false;
		}
	}

	function updateInsertButton() {
		const input = document.getElementById("pz-url");
		const button = document.getElementById("pz-insert");
		if (input && button) button.disabled = !isLikelyUrl(input.value);
	}

	function clearPostSearch() {
		if (postSearchTimer) {
			clearTimeout(postSearchTimer);
			postSearchTimer = null;
		}
		if (postSearchController) {
			postSearchController.abort();
			postSearchController = null;
		}
		if (postSearchResults) {
			postSearchResults.replaceChildren();
			postSearchResults.style.display = "none";
		}
	}

	function selectPostSearchResult(item) {
		const input = document.getElementById("pz-url");
		input.value = item.url;
		updateInsertButton();
		clearPostSearch();
		input.focus();
		input.select();
	}

	function renderPostSearchResults(items) {
		if (!postSearchResults) return;
		if (!items.length) {
			clearPostSearch();
			return;
		}
		postSearchResults.replaceChildren();
		items.forEach((item, index) => {
			const button = document.createElement("button");
			button.type = "button";
			button.className = "pz-post-search-result";
			button.setAttribute("role", "option");
			const body = document.createElement("span");
			body.className = "pz-post-search-result-body";
			const title = document.createElement("span");
			title.className = "pz-post-search-result-title";
			title.textContent = item.title;
			body.appendChild(title);
			if (item.published_date || item.modified_date) {
				const date = document.createElement("span");
				date.className = "pz-post-search-result-date";
				if (item.published_date) {
					const publishedLabel = document.createElement("span");
					publishedLabel.className = "pz-post-search-date-label";
					publishedLabel.textContent = document.getElementById("pz-code")?.dataset.postDateLabel || "Post Date";
					date.append(publishedLabel, item.published_date);
				}
				if (item.modified_date && item.modified_date !== item.published_date) {
					const modifiedLabel = document.createElement("span");
					modifiedLabel.className = "pz-post-search-date-label";
					modifiedLabel.textContent = document.getElementById("pz-code")?.dataset.modifiedDateLabel || "Modified Date";
					date.append(item.published_date ? "　" : "", modifiedLabel, item.modified_date);
				}
				body.appendChild(date);
			}
			if (item.excerpt) {
				const excerpt = document.createElement("span");
				excerpt.className = "pz-post-search-result-excerpt";
				excerpt.textContent = item.excerpt;
				body.appendChild(excerpt);
			}
			button.appendChild(body);
			if (item.thumbnail) {
				const thumbnail = document.createElement("img");
				thumbnail.className = "pz-post-search-result-thumbnail";
				thumbnail.src = item.thumbnail;
				thumbnail.alt = "";
				thumbnail.loading = "lazy";
				button.appendChild(thumbnail);
			}
			button.title = item.title;
			button.addEventListener("mouseenter", function() {
				this.focus();
			});
			button.addEventListener("click", () => selectPostSearchResult(item));
			button.addEventListener("keydown", function(event) {
				const buttons = Array.from(postSearchResults.querySelectorAll(".pz-post-search-result"));
				const pageFocus = (direction) => {
					const viewportTop = postSearchResults.scrollTop;
					const viewportBottom = viewportTop + postSearchResults.clientHeight;
					const visibleCount = Math.max(1, buttons.filter((candidate) =>
						candidate.offsetTop >= viewportTop && candidate.offsetTop + candidate.offsetHeight <= viewportBottom
					).length);
					const targetIndex = Math.max(0, Math.min(buttons.length - 1, index + direction * visibleCount));
					const target = buttons[targetIndex];
					if (!target) return;
					target.focus({ preventScroll: true });
					const targetTop = target.offsetTop;
					const targetBottom = targetTop + target.offsetHeight;
					if (targetTop < postSearchResults.scrollTop) {
						postSearchResults.scrollTop = targetTop;
					} else if (targetBottom > postSearchResults.scrollTop + postSearchResults.clientHeight) {
						postSearchResults.scrollTop = targetBottom - postSearchResults.clientHeight;
					}
				};
				if (event.key === "ArrowDown") {
					event.preventDefault();
					(buttons[index + 1] || buttons[index]).focus();
				} else if (event.key === "ArrowUp") {
					event.preventDefault();
					if (index === 0) {
						document.getElementById("pz-url").focus();
					} else {
						buttons[index - 1].focus();
					}
				} else if (event.key === "Home") {
					event.preventDefault();
					buttons[0]?.focus();
				} else if (event.key === "End") {
					event.preventDefault();
					buttons[buttons.length - 1]?.focus();
				} else if (event.key === "PageDown") {
					event.preventDefault();
					pageFocus(1);
				} else if (event.key === "PageUp") {
					event.preventDefault();
					pageFocus(-1);
				} else if (event.key === "Escape") {
					event.preventDefault();
					event.stopPropagation();
					document.getElementById("pz-url").focus();
				} else if (event.key === "Enter") {
					event.preventDefault();
					selectPostSearchResult(item);
				}
			});
			postSearchResults.appendChild(button);
		});
		postSearchResults.style.display = "block";
		postSearchResults.scrollTop = 0;
	}

	function searchPosts(keyword) {
		if (!postSearchResults) return;
		postSearchController = new AbortController();
		const body = new URLSearchParams({
			action: "pz_lkc_mce_post_search",
			nonce: postSearchResults.dataset.nonce || "",
			keyword: keyword
		});
		fetch(postSearchResults.dataset.ajaxUrl, {
			method: "POST",
			headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
			credentials: "same-origin",
			body: body.toString(),
			signal: postSearchController.signal
		})
			.then(response => response.json())
			.then(result => {
				if (document.getElementById("pz-url").value.trim() !== keyword) return;
				renderPostSearchResults(result && result.success && Array.isArray(result.data) ? result.data : []);
			})
			.catch(error => {
				if (error.name !== "AbortError") renderPostSearchResults([]);
			});
	}

	function schedulePostSearch(value) {
		clearPostSearch();
		const keyword = String(value || "").trim();
		if (!keyword || isUrl(keyword) || !postSearchResults) return;
		postSearchTimer = setTimeout(() => searchPosts(keyword), 250);
	}

	document.getElementById("pz-url").addEventListener("input", function() {
		updateInsertButton();
		schedulePostSearch(this.value);
	});
	document.getElementById("pz-url").addEventListener("keydown", function(event) {
		if (event.key === "Enter") {
			event.preventDefault();
			const button = document.getElementById("pz-insert");
			if (!button.disabled) button.click();
		} else if (event.key === "ArrowDown" && postSearchResults) {
			const firstResult = postSearchResults.querySelector(".pz-post-search-result");
			if (firstResult) {
				event.preventDefault();
				firstResult.focus();
			}
		}
	});

	// 挿入ボタン
	document.getElementById("pz-insert").addEventListener("click", function() {
		clearPostSearch();
		releaseUrlDialogFocus();
		document.getElementById("pz-overlay").style.display = "none";
		document.getElementById("pz-modal").style.display = "none";
		const editor = tinymce.activeEditor;
		setTimeout(() => focusVisualEditor(editor), 0);
		const url = document.getElementById("pz-url").value;
		const code = document.getElementById("pz-code").value;
		if (url) {
			const safeUrl = url.replace(/"/g, "%22");
			if (activeVisualCard?.isConnected) {
				let shortcode = decodeURIComponent(activeVisualCard.dataset.pzShortcode || "");
				shortcode = /\burl\s*=\s*(["']).*?\1/i.test(shortcode)
					? shortcode.replace(/(\burl\s*=\s*)(["']).*?\2/i, '$1"' + safeUrl + '"')
					: shortcode.replace(/\]$/, ' url="' + safeUrl + '"]');
				editor.undoManager.transact(() => {
					activeVisualCard.dataset.pzShortcode = encodeURIComponent(shortcode);
					delete activeVisualCard.dataset.pzLoaded;
					activeVisualCard.querySelector(".pz-lkc-mce-preview").textContent = "Pz-LinkCard";
					editor.pzLoadPreview(activeVisualCard);
				});
				editor.nodeChanged();
				editor.setDirty(true);
				focusAfterCard(editor, activeVisualCard);
				activeVisualCard = null;
				activeVisualBookmark = null;
				return;
			} else if (activeVisualShortcode && activeVisualBookmark) {
				let shortcode = activeVisualShortcode.text;
				shortcode = /\burl\s*=\s*(["']).*?\1/i.test(shortcode)
					? shortcode.replace(/(\burl\s*=\s*)(["']).*?\2/i, '$1"' + safeUrl + '"')
					: shortcode.replace(/\]$/, ' url="' + safeUrl + '"]');
				const markerId = "pz-lkc-inline-caret-" + Date.now() + "-" + Math.random().toString(36).slice(2);
				editor.selection.moveToBookmark(activeVisualBookmark);
				editor.selection.setContent(shortcode + `<span id="${markerId}" data-mce-bogus="all">&#xfeff;</span>`);
				activeVisualShortcode = null;
				activeVisualBookmark = null;
				focusInlineMarker(editor, markerId);
				return;
			} else {
				const markerId = "pz-lkc-caret-" + Date.now() + "-" + Math.random().toString(36).slice(2);
				if (activeVisualBookmark) editor.selection.moveToBookmark(activeVisualBookmark);
				const shortcode = `[${code} url="${safeUrl}"]`;
				const previewEnabled = document.getElementById("pz-code")?.dataset.previewEnabled === "1";
				if (previewEnabled) {
					editor.selection.setContent(`${shortcode}<p><span id="${markerId}" data-mce-bogus="all">&#xfeff;</span><br data-mce-bogus="1"></p>`);
				} else {
					editor.undoManager.transact(() => {
						if (!insertParagraphAtCaretBoundary(editor, shortcode, markerId)) {
							editor.selection.setContent(`<p>${shortcode}</p><p><span id="${markerId}" data-mce-bogus="all">&#xfeff;</span><br data-mce-bogus="1"></p>`);
						}
					});
				}
				activeVisualCard = null;
				activeVisualBookmark = null;
				focusInlineMarker(editor, markerId);
				return;
			}
		}
		activeVisualCard = null;
		activeVisualBookmark = null;
		activeVisualShortcode = null;
		focusVisualEditor(editor);
	});

	// ダイアログの白い余白をドラッグして移動
	const draggableModal = document.getElementById("pz-modal");
	const modalContent = document.getElementById("pz-content");
	let modalDrag = null;
	if (draggableModal) {
		draggableModal.addEventListener("pointerdown", function(event) {
			if (event.button !== 0 || (event.target !== draggableModal && event.target !== modalContent)) return;
			const rect = draggableModal.getBoundingClientRect();
			modalDrag = {
				pointerId: event.pointerId,
				offsetX: event.clientX - rect.left,
				offsetY: event.clientY - rect.top
			};
			draggableModal.setPointerCapture(event.pointerId);
			draggableModal.classList.add("is-dragging");
			event.preventDefault();
		});

		draggableModal.addEventListener("pointermove", function(event) {
			if (!modalDrag || event.pointerId !== modalDrag.pointerId) return;
			const maxLeft = Math.max(0, window.innerWidth - draggableModal.offsetWidth);
			const maxTop = Math.max(0, window.innerHeight - draggableModal.offsetHeight);
			const left = Math.max(0, Math.min(maxLeft, event.clientX - modalDrag.offsetX));
			const top = Math.max(0, Math.min(maxTop, event.clientY - modalDrag.offsetY));
			draggableModal.style.left = left + "px";
			draggableModal.style.top = top + "px";
		});

		const stopModalDrag = function(event) {
			if (!modalDrag || event.pointerId !== modalDrag.pointerId) return;
			if (draggableModal.hasPointerCapture(event.pointerId)) {
				draggableModal.releasePointerCapture(event.pointerId);
			}
			modalDrag = null;
			draggableModal.classList.remove("is-dragging");
		};
		draggableModal.addEventListener("pointerup", stopModalDrag);
		draggableModal.addEventListener("pointercancel", stopModalDrag);
	}

	// ウィンドウのリサイズ
	window.addEventListener("resize", modal_move_center);
	function modal_move_center() {
		const w = window.innerWidth;
		const h = window.innerHeight;
		const modal = document.getElementById("pz-modal");
		if (modal) {
			const mw = modal.offsetWidth;
			const mh = modal.offsetHeight;
			modal.style.left = (w - mw) / 2 + "px";
			modal.style.top = (h - mh) / 2 + "px";
		}
	}

	// 文字列からURLを切り出す
	function cut_url(s) {
		if (!s ) return "";
		const r = /((https?|file|ftp|data|ogg):\/\/[^ "<,]+)/;
		const u = s.match(r);
		return u ? u[1] : "";
	}
})();
