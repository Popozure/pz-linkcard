(function() {
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
			ed.addButton(id, {
				title: "Insert Linkcard",
				cmd: id,
				image: url + "/mce-button.png"
			});
			ed.addCommand(id, function() {
				const insertButton = document.getElementById("pz-insert");
				if (insertButton && tinymce.translate) {
					const insertLabel = tinymce.translate("Insert Linkcard");
					insertButton.value = insertLabel;
				}
				document.getElementById("pz-overlay").style.display = "block";
				document.getElementById("pz-modal").style.display = "block";
				document.getElementById("pz-url").value = "";
				const st = tinymce.activeEditor.selection.getContent();
				const url = cut_url(st);
				document.getElementById("pz-url").value = url;
				updateInsertButton();
				schedulePostSearch(url);
				modal_move_center();
				const ob=document.querySelector("#pz-url");
				setTimeout(() => {
					ob.focus();
					ob.select();
				}, 100);
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
			clearPostSearch();
			document.getElementById("pz-overlay").style.display = "none";
			document.getElementById("pz-modal").style.display = "none";
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
			const title = document.createElement("span");
			title.className = "pz-post-search-result-title";
			title.textContent = item.title;
			button.appendChild(title);
			if (item.date) {
				const date = document.createElement("span");
				date.className = "pz-post-search-result-date";
				date.textContent = ` (${item.date})`;
				button.appendChild(date);
			}
			button.title = item.title + (item.date ? ` (${item.date})` : "");
			button.addEventListener("click", () => selectPostSearchResult(item));
			button.addEventListener("keydown", function(event) {
				const buttons = Array.from(postSearchResults.querySelectorAll(".pz-post-search-result"));
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
			const sc = `<p>[${code} url="${url}"]</p>`;
			tinymce.activeEditor.selection.setContent(sc);
		}
		tinymce.activeEditor.focus();
	});

	// ウィンドウのリサイズ
	window.addEventListener("resize", modalMoveCenter);
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
