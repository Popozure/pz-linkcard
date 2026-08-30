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
				document.getElementById("pz-overlay").style.display = "block";
				document.getElementById("pz-modal").style.display = "block";
				document.getElementById("pz-url").value = "";
				const st = tinymce.activeEditor.selection.getContent();
				const url = cut_url(st);
				document.getElementById("pz-url").value = url;
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
			document.getElementById("pz-close").click();
		}
	});

	// 右上の「×」、もしくは画面の暗い部分をクリックしたらモーダルを閉じる
	document.querySelectorAll("#pz-overlay, #pz-close").forEach(el => {
		el.addEventListener("click", function() {
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
				e.preventDefault();
			}
		}
	});

	// 挿入ボタン
	document.getElementById("pz-insert").addEventListener("click", function() {
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
