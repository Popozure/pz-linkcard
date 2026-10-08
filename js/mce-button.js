(function() {
	let activeVisualCard = null;
	let activeVisualDirection = 1;

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
			let labels = {};
			try { labels = JSON.parse(config?.dataset.labels || "{}"); } catch (error) { labels = {}; }
			let names = [];
			try { names = JSON.parse(config?.dataset.shortcodes || "[]"); } catch (error) { names = []; }
			if (!names.length && config?.value) names.push(config.value);
			const escapeRegExp = value => String(value).replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
			const escapeHtml = value => String(value).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
			const pattern = names.length ? new RegExp("\\[(" + names.map(escapeRegExp).join("|") + ")\\b([^\\]]*)\\]", "gi") : null;

			function decodeCard(card) {
				try { return decodeURIComponent(card.dataset.pzShortcode || ""); } catch (error) { return ""; }
			}

			function cardHtml(shortcode) {
				return '<div class="pz-lkc-mce-card is-loading" contenteditable="false" data-mce-contenteditable="false" data-mce-resize="false" tabindex="0" data-pz-shortcode="' + escapeHtml(encodeURIComponent(shortcode)) + '">' +
					'<div class="pz-lkc-mce-preview" contenteditable="false" data-mce-contenteditable="false"><span class="pz-lkc-mce-message">' + escapeHtml(labels.loading || "Loading Pz-LinkCard...") + '</span></div></div>';
			}

			function convertShortcodes(content) {
				return pattern ? String(content || "").replace(pattern, cardHtml) : content;
			}

			function loadPreview(card) {
				if (!card || card.dataset.pzLoading || card.dataset.pzLoaded) return;
				const preview = card.querySelector(".pz-lkc-mce-preview") || card;
				card.dataset.pzLoading = "1";
				const body = new URLSearchParams({ action: "pz_lkc_mce_render_card", nonce: config?.dataset.renderNonce || "", shortcode: decodeCard(card) });
				fetch(config?.dataset.ajaxUrl || window.ajaxurl, {
					method: "POST", credentials: "same-origin",
					headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, body: body.toString()
				}).then(response => response.json()).then(result => {
					if (!card.isConnected) return;
					if (!result?.success || !result.data?.html) throw new Error("preview");
					preview.innerHTML = result.data.html;
					card.classList.remove("is-loading", "is-error");
					card.dataset.pzLoaded = "1";
					card.querySelectorAll("a").forEach(link => link.tabIndex = -1);
				}).catch(() => {
					if (!card.isConnected) return;
					card.classList.remove("is-loading");
					card.classList.add("is-error");
					preview.innerHTML = '<span class="pz-lkc-mce-message">' + escapeHtml(labels.previewError || "The preview could not be displayed. Click to check the URL.") + '</span>';
				}).finally(() => { if (card.isConnected) delete card.dataset.pzLoading; });
			}

			function loadPreviews() {
				ed.getBody().querySelectorAll(".pz-lkc-mce-card").forEach(loadPreview);
			}

			function updateCardUrl(card, nextUrl) {
				const safeUrl = String(nextUrl || "").trim().replace(/"/g, "%22");
				if (!isLikelyUrl(safeUrl)) return false;
				let shortcode = decodeCard(card);
				if (cut_url(shortcode) === safeUrl) return true;
				shortcode = /\burl\s*=\s*(["']).*?\1/i.test(shortcode)
					? shortcode.replace(/(\burl\s*=\s*)(["']).*?\2/i, '$1"' + safeUrl + '"')
					: shortcode.replace(/\]$/, ' url="' + safeUrl + '"]');
				ed.undoManager.transact(() => {
					card.dataset.pzShortcode = encodeURIComponent(shortcode);
					delete card.dataset.pzLoaded;
					const preview = card.querySelector(".pz-lkc-mce-preview");
					if (preview) preview.innerHTML = '<span class="pz-lkc-mce-message">' + escapeHtml(labels.loading || "Loading Pz-LinkCard...") + '</span>';
					card.classList.add("is-loading");
					loadPreview(card);
				});
				ed.nodeChanged();
				ed.setDirty(true);
				return true;
			}

			function closeCardEditors(except = null) {
				ed.getBody().querySelectorAll(".pz-lkc-mce-card.is-editing").forEach(card => {
					if (card === except) return;
					card.classList.remove("is-editing");
					card.querySelector(".pz-lkc-mce-editor")?.remove();
					card.setAttribute("contenteditable", "false");
					card.setAttribute("data-mce-contenteditable", "false");
				});
			}

			function openCardEditor(card) {
				if (!card?.isConnected) return;
				closeCardEditors(card);
				card.classList.add("is-editing");
				card.setAttribute("contenteditable", "true");
				card.setAttribute("data-mce-contenteditable", "true");
				let panel = card.querySelector(".pz-lkc-mce-editor");
				if (!panel) {
					panel = ed.getDoc().createElement("div");
					panel.className = "pz-lkc-mce-editor";
					panel.setAttribute("contenteditable", "true");
					panel.setAttribute("data-mce-contenteditable", "true");
					panel.setAttribute("data-mce-bogus", "all");
					panel.innerHTML = '<div class="pz-lkc-mce-editor-title">Pz-LinkCard</div>' +
						'<label class="pz-lkc-mce-editor-label">' + escapeHtml(labels.urlPrompt || "Enter a URL or search keyword.") +
						'<span class="pz-lkc-mce-editor-controls"><input type="text" class="pz-lkc-mce-editor-input" inputmode="url" autocomplete="off"></span></label>' +
						'<div class="pz-lkc-mce-editor-results" role="listbox" aria-label="' + escapeHtml(labels.searchResults || "Search results") + '"></div>';
					card.insertBefore(panel, card.firstChild);
					panel.addEventListener("mousedown", event => event.stopPropagation());
					panel.addEventListener("click", event => event.stopPropagation());
					panel.addEventListener("keydown", event => event.stopPropagation());
					panel.addEventListener("beforeinput", event => {
						if (!event.target.closest("input")) event.preventDefault();
					});
					const input = panel.querySelector(".pz-lkc-mce-editor-input");
					const results = panel.querySelector(".pz-lkc-mce-editor-results");
					const focusInputFromPanel = event => {
						if (event.target.closest("input, button")) return;
						event.preventDefault();
						input.focus();
					};
					panel.addEventListener("mousedown", focusInputFromPanel);
					panel.addEventListener("focusin", focusInputFromPanel);
					let searchTimer = null;
					let searchController = null;
					const clearResults = () => {
						if (searchTimer) clearTimeout(searchTimer);
						if (searchController) searchController.abort();
						searchTimer = null;
						searchController = null;
						results.replaceChildren();
					};
					const setSelectedResult = button => {
						results.querySelectorAll(".pz-lkc-mce-editor-result.is-selected").forEach(result => {
							result.classList.remove("is-selected");
							result.setAttribute("aria-selected", "false");
						});
						if (!button) return;
						button.classList.add("is-selected");
						button.setAttribute("aria-selected", "true");
					};
					const selectResult = item => {
						input.value = item.url;
						clearResults();
						updateCardUrl(card, item.url);
						input.focus();
						input.select();
					};
					const search = keyword => {
						searchController = new AbortController();
						const body = new URLSearchParams({ action: "pz_lkc_mce_post_search", nonce: config?.dataset.searchNonce || "", keyword });
						fetch(config?.dataset.ajaxUrl || window.ajaxurl, { method: "POST", credentials: "same-origin", headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, body: body.toString(), signal: searchController.signal })
							.then(response => response.json())
							.then(result => {
								if (input.value.trim() !== keyword) return;
								results.replaceChildren();
								(result?.success && Array.isArray(result.data) ? result.data : []).forEach(item => {
									const button = ed.getDoc().createElement("button");
									button.type = "button";
									button.className = "pz-lkc-mce-editor-result";
									button.setAttribute("role", "option");
									button.setAttribute("aria-selected", "false");
									button.title = item.title;
									const resultBody = ed.getDoc().createElement("span");
									resultBody.className = "pz-lkc-mce-editor-result-body";
									const title = ed.getDoc().createElement("span");
									title.className = "pz-lkc-mce-editor-result-title";
									title.textContent = item.title;
									resultBody.appendChild(title);
									if (item.published_date || item.modified_date) {
										const date = ed.getDoc().createElement("span");
										date.className = "pz-lkc-mce-editor-result-date";
										if (item.published_date) {
											const publishedLabel = ed.getDoc().createElement("span");
											publishedLabel.className = "pz-lkc-mce-editor-date-label";
											publishedLabel.textContent = labels.postDate || "Post Date";
											date.append(publishedLabel, item.published_date);
										}
										if (item.modified_date && String(item.modified_date).trim() !== String(item.published_date || "").trim()) {
											const modifiedLabel = ed.getDoc().createElement("span");
											modifiedLabel.className = "pz-lkc-mce-editor-date-label";
											modifiedLabel.textContent = labels.modifiedDate || "Modified Date";
											date.append(item.published_date ? "　" : "", modifiedLabel, item.modified_date);
										}
										resultBody.appendChild(date);
									}
									if (item.excerpt) {
										const excerpt = ed.getDoc().createElement("span");
										excerpt.className = "pz-lkc-mce-editor-result-excerpt";
										excerpt.textContent = item.excerpt;
										resultBody.appendChild(excerpt);
									}
									button.appendChild(resultBody);
									if (item.thumbnail) {
										const thumbnail = ed.getDoc().createElement("img");
										thumbnail.className = "pz-lkc-mce-editor-result-thumbnail";
										thumbnail.src = item.thumbnail;
										thumbnail.alt = "";
										thumbnail.loading = "lazy";
										button.appendChild(thumbnail);
									}
									button.addEventListener("click", event => { event.stopPropagation(); selectResult(item); });
									button.addEventListener("focus", () => setSelectedResult(button));
									button.addEventListener("mouseenter", () => button.focus());
									results.appendChild(button);
								});
							})
							.catch(error => { if (error.name !== "AbortError") results.replaceChildren(); });
					};
					input.value = cut_url(decodeCard(card));
					input.addEventListener("click", event => event.stopPropagation());
					input.addEventListener("paste", event => {
						event.preventDefault();
						event.stopPropagation();
						if (typeof event.stopImmediatePropagation === "function") event.stopImmediatePropagation();
						const pastedText = event.clipboardData?.getData("text/plain") || "";
						const start = input.selectionStart ?? input.value.length;
						const end = input.selectionEnd ?? start;
						input.setRangeText(pastedText, start, end, "end");
						input.dispatchEvent(new input.ownerDocument.defaultView.Event("input"));
						setTimeout(() => {
							panel.querySelector(".pz-lkc-mce-editor-title").textContent = "Pz-LinkCard";
							const label = panel.querySelector(".pz-lkc-mce-editor-label");
							const controls = panel.querySelector(".pz-lkc-mce-editor-controls");
							Array.from(label.childNodes).forEach(node => { if (node !== controls) node.remove(); });
							label.insertBefore(ed.getDoc().createTextNode(labels.urlPrompt || "Enter a URL or search keyword."), controls);
							Array.from(panel.childNodes).forEach(node => {
								if (node.nodeType === 3) node.remove();
							});
							Array.from(card.childNodes).forEach(node => {
								if (node !== panel && !node.classList?.contains("pz-lkc-mce-preview")) node.remove();
							});
							input.focus();
						}, 0);
					});
					input.addEventListener("input", () => {
						clearResults();
						const value = input.value.trim();
						if (!value || isLikelyUrl(value)) return;
						searchTimer = setTimeout(() => search(value), 250);
					});
					input.addEventListener("blur", () => updateCardUrl(card, input.value));
					input.addEventListener("keydown", event => {
						event.stopPropagation();
						const selectionStart = input.selectionStart;
						const selectionEnd = input.selectionEnd;
						const resultButtons = Array.from(results.querySelectorAll(".pz-lkc-mce-editor-result"));
						if (event.key === "ArrowUp" && (selectionStart !== 0 || selectionEnd !== 0)) {
							event.preventDefault();
							input.setSelectionRange(0, 0);
						} else if (event.key === "ArrowUp" && resultButtons.length) {
							event.preventDefault();
							resultButtons[resultButtons.length - 1].focus();
						} else if (event.key === "ArrowDown" && resultButtons.length) {
							event.preventDefault();
							resultButtons[0].focus();
						} else if (event.key === "ArrowUp" && selectionStart === 0 && selectionEnd === 0) {
							event.preventDefault();
							closeCardEditors();
							focusLineBesideCard(ed, card, -1);
						} else if (event.key === "ArrowDown" && selectionStart === input.value.length && selectionEnd === input.value.length) {
							event.preventDefault();
							closeCardEditors();
							focusLineBesideCard(ed, card, 1);
						} else if (event.key === "Enter") {
							if (resultButtons.length) {
								event.preventDefault();
								resultButtons[0].click();
							} else if (updateCardUrl(card, input.value)) {
								event.preventDefault();
								clearResults();
								input.select();
							}
						} else if (event.key === "Escape") {
							event.preventDefault();
							closeCardEditors();
							ed.focus();
						}
					});
					results.addEventListener("keydown", event => {
						const button = event.target.closest(".pz-lkc-mce-editor-result");
						if (!button) return;
						const buttons = Array.from(results.querySelectorAll(".pz-lkc-mce-editor-result"));
						const index = buttons.indexOf(button);
						const pageFocus = direction => {
							const targetIndex = Math.max(0, Math.min(buttons.length - 1, index + direction * 4));
							const target = buttons[targetIndex];
							if (!target) return;
							target.focus({ preventScroll: true });
							const targetTop = target.offsetTop;
							const targetBottom = targetTop + target.offsetHeight;
							if (targetTop < results.scrollTop) {
								results.scrollTop = targetTop;
							} else if (targetBottom > results.scrollTop + results.clientHeight) {
								results.scrollTop = targetBottom - results.clientHeight;
							}
						};
						if (event.key === "Enter") {
							event.preventDefault();
							button.click();
						} else if (event.key === "ArrowDown") {
							event.preventDefault();
							(buttons[index + 1] || buttons[index]).focus();
						} else if (event.key === "ArrowUp") {
							event.preventDefault();
							if (index === 0) {
								setSelectedResult(null);
								input.focus();
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
						}
					});
				}
				const input = panel.querySelector(".pz-lkc-mce-editor-input");
				setTimeout(() => { input.focus(); input.select(); }, 0);
			}

			function adjacentCard(direction) {
				const range = ed.selection.getRng();
				if (!range.collapsed) return null;
				let block = range.startContainer.nodeType === 1 ? range.startContainer : range.startContainer.parentNode;
				while (block && block.parentNode !== ed.getBody()) block = block.parentNode;
				if (!block) return null;
				const remainder = range.cloneRange();
				if (direction > 0) remainder.setEndAfter(block.lastChild || block);
				else remainder.setStartBefore(block.firstChild || block);
				if (remainder.toString().trim()) return null;
				let sibling = direction > 0 ? block.nextSibling : block.previousSibling;
				while (sibling?.nodeType === 3 && !sibling.nodeValue.trim()) sibling = direction > 0 ? sibling.nextSibling : sibling.previousSibling;
				if (sibling?.classList?.contains("pz-lkc-mce-card")) return sibling;
				return sibling?.children?.length === 1 && sibling.firstElementChild?.classList?.contains("pz-lkc-mce-card") ? sibling.firstElementChild : null;
			}

			function cardAtCaret() {
				const range = ed.selection.getRng();
				if (!range.collapsed) return null;
				const cardFromNode = node => {
					if (node?.nodeType !== 1) return null;
					if (node.classList.contains("pz-lkc-mce-card")) return node;
					return node.closest?.(".pz-lkc-mce-card") || null;
				};
				const cardFromCandidate = node => cardFromNode(node) ||
					(node?.nodeType === 1 && node.children.length === 1 && node.firstElementChild?.classList?.contains("pz-lkc-mce-card")
						? node.firstElementChild
						: null);
				const container = range.startContainer;
				return cardFromNode(container) ||
					cardFromCandidate(container.nodeType === 1 ? container.childNodes[range.startOffset] : null) ||
					cardFromNode(ed.selection.getNode()) ||
					cardFromNode(ed.getDoc().activeElement);
			}

			function insertInlineCard() {
				const currentCard = cardAtCaret();
				if (currentCard) {
					openCardEditor(currentCard);
					return;
				}

				let selectedUrl = "";
				try {
					selectedUrl = cut_url(ed.selection.getContent({ format: "text" }));
				} catch (error) {
					selectedUrl = "";
				}
				if (!isLikelyUrl(selectedUrl)) selectedUrl = "";
				const safeUrl = selectedUrl.replace(/"/g, "%22");
				const shortcodeName = names[0] || config?.value || "blogcard";
				const shortcode = `[${shortcodeName} url="${safeUrl}"]`;
				const markerId = "pz-lkc-inserted-" + Date.now() + "-" + Math.random().toString(36).slice(2);
				let insertedCard = null;

				closeCardEditors();
				ed.undoManager.transact(() => {
					ed.selection.setContent(cardHtml(shortcode).replace("<div ", '<div id="' + markerId + '" '));
					insertedCard = ed.getBody().querySelector("#" + markerId);
					if (insertedCard) insertedCard.removeAttribute("id");
				});
				if (!insertedCard) return;

				if (selectedUrl) {
					loadPreview(insertedCard);
				} else {
					insertedCard.classList.remove("is-loading");
					insertedCard.dataset.pzLoaded = "1";
					const preview = insertedCard.querySelector(".pz-lkc-mce-preview");
					if (preview) preview.innerHTML = '<span class="pz-lkc-mce-message">' + escapeHtml(labels.urlPrompt || "Enter a URL or search keyword.") + '</span>';
				}
				ed.nodeChanged();
				ed.setDirty(true);
				openCardEditor(insertedCard);
			}

			if (previewEnabled) {
				ed.on("PastePreProcess", event => {
					if (ed.getDoc().activeElement?.classList?.contains("pz-lkc-mce-editor-input")) event.content = "";
				});
				ed.on("BeforeSetContent", event => { event.content = convertShortcodes(event.content); });
				ed.on("SetContent", () => setTimeout(loadPreviews, 0));
				ed.on("PreProcess", event => {
					const cards = event.node.querySelectorAll ? event.node.querySelectorAll(".pz-lkc-mce-card") : [];
					Array.prototype.forEach.call(cards, card => card.parentNode.replaceChild(event.node.ownerDocument.createTextNode(decodeCard(card)), card));
				});
			}
			ed.on("init", function() {
				if (previewEnabled) {
					if (config?.dataset.styleUrl) ed.dom.loadCSS(config.dataset.styleUrl);
					if (config?.dataset.additionalStyleUrl) ed.dom.loadCSS(config.dataset.additionalStyleUrl);
					ed.dom.addStyle(".pz-lkc-mce-card{display:block;box-sizing:border-box;margin:12px 0;cursor:pointer}.pz-lkc-mce-card:hover,.pz-lkc-mce-card:focus{outline:2px solid #2271b1;outline-offset:2px}.pz-lkc-mce-card.is-editing{padding:3px 36px 18px;border:1px solid #222;outline:0;cursor:default}.pz-lkc-mce-card.is-loading>.pz-lkc-mce-preview,.pz-lkc-mce-card.is-error>.pz-lkc-mce-preview{min-height:72px;padding:24px;border:1px solid #dcdcde;background:#f6f7f7;text-align:center}.pz-lkc-mce-card a{pointer-events:none}.pz-lkc-mce-editor{margin:0 -34px 18px;text-align:left;font:13px/1.4 sans-serif;color:#111}.pz-lkc-mce-editor-title{margin-bottom:6px}.pz-lkc-mce-editor-label{display:block;padding-left:36px}.pz-lkc-mce-editor-input{display:block;box-sizing:border-box;width:100%;height:27px;margin-top:4px;padding:2px 4px;border:1px solid #555;background:#fff;color:#111}.pz-lkc-mce-editor-results{box-sizing:border-box;width:calc(100% - 36px);max-height:360px;margin-left:36px;background:#fff;overflow-x:hidden;overflow-y:auto}.pz-lkc-mce-editor-result{display:flex;align-items:flex-start;gap:8px;box-sizing:border-box;width:100%;min-height:76px;padding:7px 8px;text-align:left;border:0;border-bottom:1px solid #ddd;background:#fff;color:#1d2327;cursor:pointer}.pz-lkc-mce-editor-result-body{display:block;flex:1 1 auto;min-width:0}.pz-lkc-mce-editor-result-title,.pz-lkc-mce-editor-result-date{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.pz-lkc-mce-editor-result-title{font-weight:600}.pz-lkc-mce-editor-result-date{margin-top:2px;color:#646970;font-size:11px;line-height:1.3}.pz-lkc-mce-editor-date-label{display:inline-block;padding:1px 4px;border-radius:4px;background:#a4a9b0;color:#fff}.pz-lkc-mce-editor-result-excerpt{display:-webkit-box;margin-top:2px;overflow:hidden;color:#50575e;font-size:12px;line-height:1.35;-webkit-box-orient:vertical;-webkit-line-clamp:2}.pz-lkc-mce-editor-result-thumbnail{display:block;flex:0 0 64px;width:64px;height:64px;object-fit:cover}.pz-lkc-mce-editor-result.is-selected{background:#f0f6fc;color:#135e96;outline:1px solid #2271b1;outline-offset:-1px}.pz-lkc-mce-editor-result.is-selected .pz-lkc-mce-editor-result-date,.pz-lkc-mce-editor-result.is-selected .pz-lkc-mce-editor-result-excerpt{color:#135e96}");
					ed.dom.addStyle(".pz-lkc-mce-card.is-editing{padding-bottom:4px}.pz-lkc-mce-editor-label{box-sizing:border-box;padding-right:36px}.pz-lkc-mce-editor-controls{display:block;margin-top:4px}.pz-lkc-mce-editor-input{margin-top:0}.pz-lkc-mce-editor-results{width:calc(100% - 72px)}");
					ed.dom.addStyle(".pz-lkc-mce-editor{position:relative}.pz-lkc-mce-editor-results{position:absolute;top:100%;left:36px;z-index:20;margin-left:0;box-shadow:0 2px 5px rgba(0,0,0,.2)}");
					const current = ed.getContent({ format: "raw" });
					const converted = convertShortcodes(current);
					if (converted !== current) ed.setContent(converted, { format: "raw" });
					loadPreviews();
					ed.getBody().addEventListener("click", event => {
						const card = event.target.closest(".pz-lkc-mce-card");
						if (!card) { closeCardEditors(); return; }
						event.preventDefault();
						openCardEditor(card);
					});
				}
			});
			ed.on("keydown", event => {
				if (!previewEnabled) return;
				if (!["ArrowDown", "ArrowUp", "ArrowRight", "ArrowLeft"].includes(event.key)) return;
				const direction = event.key === "ArrowDown" || event.key === "ArrowRight" ? 1 : -1;
				const card = adjacentCard(direction);
				if (card) {
					event.preventDefault();
					openCardEditor(card);
					return;
				}
				setTimeout(() => {
					if (document.getElementById("pz-modal")?.style.display === "block") return;
					const landedCard = cardAtCaret();
					if (landedCard) openCardEditor(landedCard);
				}, 0);
			});
			ed.pzCardHtml = cardHtml;
			ed.pzLoadPreviews = loadPreviews;
			ed.addButton(id, {
				title: "Insert Linkcard",
				cmd: id,
				image: url + "/mce-button.png"
			});
			ed.addCommand(id, function() {
				if (previewEnabled) {
					insertInlineCard();
				} else {
					openDialog(ed, null);
				}
			} );
		},
	} );
	tinymce.PluginManager.add("pz_linkcard_tinymce", tinymce.plugins.pz_linkcard_tinymce);
	tinymce.PluginManager.requireLangPack("pz_linkcard_tinymce");

	function openDialog(editor, card, direction = 1) {
		activeVisualCard = card?.isConnected ? card : null;
		activeVisualDirection = activeVisualCard && direction < 0 ? -1 : 1;
		const insertButton = document.getElementById("pz-insert");
		if (insertButton && tinymce.translate) insertButton.value = tinymce.translate("Insert Linkcard");
		document.getElementById("pz-overlay").style.display = "block";
		document.getElementById("pz-modal").style.display = "block";
		let value = "";
		try { value = activeVisualCard ? cut_url(decodeURIComponent(activeVisualCard.dataset.pzShortcode || "")) : cut_url(editor.selection.getContent()); } catch (error) { value = ""; }
		const input = document.getElementById("pz-url");
		input.value = value;
		updateInsertButton();
		schedulePostSearch(value);
		modal_move_center();
		setTimeout(() => { input.focus(); input.select(); }, 100);
	}

	function focusLineBesideCard(editor, card, direction) {
		if (!card?.isConnected) return;
		const body = editor.getBody();
		let cardLine = card;
		while (cardLine.parentNode && cardLine.parentNode !== body) cardLine = cardLine.parentNode;

		let targetLine = direction < 0 ? cardLine.previousSibling : cardLine.nextSibling;
		while (targetLine?.nodeType === 3 && !targetLine.nodeValue.trim()) {
			targetLine = direction < 0 ? targetLine.previousSibling : targetLine.nextSibling;
		}
		const isCardLine = targetLine?.nodeType === 1 && (
			targetLine.getAttribute("contenteditable") === "false" ||
			targetLine.classList.contains("pz-lkc-mce-card") ||
			(targetLine.children.length === 1 && targetLine.firstElementChild?.classList.contains("pz-lkc-mce-card"))
		);
		if (!targetLine || isCardLine) {
			const paragraph = editor.dom.create("p", {}, '<br data-mce-bogus="1">');
			if (direction < 0) body.insertBefore(paragraph, cardLine);
			else if (targetLine) body.insertBefore(paragraph, targetLine);
			else body.appendChild(paragraph);
			targetLine = paragraph;
		}

		if (targetLine.nodeType === 3) {
			editor.selection.setCursorLocation(targetLine, direction < 0 ? targetLine.nodeValue.length : 0);
		} else {
			editor.selection.select(targetLine, true);
			editor.selection.collapse(direction >= 0);
		}
		editor.nodeChanged();
	}

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
			clearPostSearch();
			document.getElementById("pz-overlay").style.display = "none";
			document.getElementById("pz-modal").style.display = "none";
			activeVisualCard = null;
			activeVisualDirection = 1;
			tinymce.activeEditor.focus();
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
					publishedLabel.textContent = labels.postDate || "Post Date";
					date.append(publishedLabel, item.published_date);
				}
				if (item.modified_date && String(item.modified_date).trim() !== String(item.published_date || "").trim()) {
					const modifiedLabel = document.createElement("span");
					modifiedLabel.className = "pz-post-search-date-label";
					modifiedLabel.textContent = labels.modifiedDate || "Modified Date";
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
		document.getElementById("pz-overlay").style.display = "none";
		document.getElementById("pz-modal").style.display = "none";
		const url = document.getElementById("pz-url").value;
		const code = document.getElementById("pz-code").value;
		if (url) {
			const editor = tinymce.activeEditor;
			const focusDirection = activeVisualDirection;
			const safeUrl = url.replace(/"/g, "%22");
			let shortcode = `[${code} url="${safeUrl}"]`;
			if (document.getElementById("pz-code")?.dataset.previewEnabled !== "1") {
				editor.selection.setContent(`<p>${shortcode}</p>`);
				activeVisualCard = null;
				activeVisualDirection = 1;
				editor.focus();
				return;
			}
			const markerId = "pz-lkc-inserted-" + Date.now() + "-" + Math.random().toString(36).slice(2);
			if (activeVisualCard?.isConnected) {
				try {
					const oldShortcode = decodeURIComponent(activeVisualCard.dataset.pzShortcode || "");
					shortcode = /\burl\s*=\s*(["']).*?\1/i.test(oldShortcode)
						? oldShortcode.replace(/(\burl\s*=\s*)(["']).*?\2/i, '$1"' + safeUrl + '"')
						: oldShortcode.replace(/\]$/, ' url="' + safeUrl + '"]');
				} catch (error) {}
				editor.undoManager.transact(() => {
					activeVisualCard.outerHTML = editor.pzCardHtml(shortcode).replace("<div ", '<div id="' + markerId + '" ');
					const insertedCard = editor.getBody().querySelector("#" + markerId);
					if (insertedCard) insertedCard.removeAttribute("id");
					focusLineBesideCard(editor, insertedCard, focusDirection);
				});
			} else {
				editor.undoManager.transact(() => {
					editor.selection.setContent(editor.pzCardHtml(shortcode).replace("<div ", '<div id="' + markerId + '" '));
					const insertedCard = editor.getBody().querySelector("#" + markerId);
					if (insertedCard) insertedCard.removeAttribute("id");
					focusLineBesideCard(editor, insertedCard, focusDirection);
				});
			}
			activeVisualCard = null;
			activeVisualDirection = 1;
			editor.pzLoadPreviews();
		}
		tinymce.activeEditor.focus();
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
