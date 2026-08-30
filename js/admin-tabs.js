(function() {
	// Pz-LinkCardの設定画面のときのみ動作
	if (document.querySelector('.pz-dashboard')) {
		// 最後に開いていたタブを開く
		document.querySelector('input[name="tab-now"]').addEventListener('change', tab_open_last);
	}

	// 画面表示された時に実行
	window.addEventListener('load', function() {
		if (document.querySelector('.pz-dashboard')) {
			// 処理中オーバーレイを非表示
			document.querySelector("#pz-overlay-proc").style.display = "none";

			// 最後に開いていたタブを開く
			tab_open_last();
			// カーソルキーでタブを移動
			window.addEventListener('keydown', tab_move_key);
			// クリックされたタブを開く
			document.querySelectorAll('.pz-tab').forEach(tab => {
				tab.addEventListener('click', tab_open);
			});
			// 各タブの有効化／無効化
			document.querySelectorAll('input[name^="properties"], input[name$="-mode"]').forEach(input => {
				input.addEventListener('change', tab_enabled);
			});
		}
	});

	// 最後に開いていたタブを開く
	function tab_open_last() {
		let name = document.querySelector('input[name="tab-now"]').value;
		let tab = document.querySelector(`a[name="${name}"]`);
		// 無効化されているタブの場合、次のタブにする
		if (!tab || tab.style.display === 'none') {
			document.querySelectorAll('.pz-tab').forEach(el => {
				if (el.style.display !== 'none') {
					name = el.getAttribute('name');
					return;
				}
			});
		}
		// タブを選択する
		document.querySelectorAll('.pz-tab-active, .pz-page-active').forEach(el => el.classList.remove('pz-tab-active', 'pz-page-active'));
		document.querySelector(`a[name="${name}"]`)?.classList.add('pz-tab-active');
		document.getElementById(name)?.classList.add('pz-page-active');
	}

	// 選択されたタブを開く
	function tab_open(event) {
		event.preventDefault();
		document.querySelectorAll('.pz-page').forEach(el => el.classList.remove('pz-page-active'));
		document.querySelectorAll('.pz-tab').forEach(el => el.classList.remove('pz-tab-active'));

		this.classList.add('pz-tab-active');
		document.querySelector(this.getAttribute('href'))?.classList.add('pz-page-active');
		document.querySelector('input[name="tab-now"]').value = this.getAttribute('name');
	}

	// タブをカーソルキーで移動する
	function tab_move_key(e) {
		if (e.ctrlKey) {
			if (e.keyCode === 37) tab_select(-1);
			if (e.keyCode === 39) tab_select(1);
		}
	}

	// 移動先のタブの処理
	function tab_select(direction) {
		let tabs = [...document.querySelectorAll('.pz-tab')].filter(tab => tab.style.display !== 'none');
		let activeIndex = tabs.findIndex(tab => tab.classList.contains('pz-tab-active'));

		if (direction === -1 && activeIndex > 0) activeIndex--;
		if (direction === 1 && activeIndex < tabs.length - 1) activeIndex++;

		tabs.forEach(tab => tab.classList.remove('pz-tab-active'));
		document.querySelectorAll('.pz-page-active').forEach(page => page.classList.remove('pz-page-active'));
		tabs[activeIndex].classList.add('pz-tab-active');
		document.querySelector(tabs[activeIndex].getAttribute('href')).classList.add('pz-page-active');
		document.querySelector('input[name="tab-now"]').value = tabs[activeIndex].getAttribute('name');
	}

	// タブの有効化／無効化
	function tab_enabled() {
		const modeSettings = {
			'error-mode':		'pz-error',
			'flg-initialize':	'pz-initialize',
			'multi-mode':		'pz-multisite',
			'debug-mode':		'pz-debug-only',
			'admin-mode':		'pz-admin',
			'develop-mode':		'pz-develop-only'
		};
        
		Object.entries(modeSettings).forEach(([mode, className]) => {
			const checkbox = document.querySelector(`input[type=checkbox][name="properties[${mode}]"]`);
			if (checkbox) {
				const elements = document.querySelectorAll(`.${className}, a[name="${className}"]`);
				elements.forEach(el => {
					if (checkbox.checked) {
						el.style.display = '';
						el.classList.add('pz-show');
						el.classList.remove('pz-hide');
					} else {
						el.style.display = 'none';
						el.classList.add('pz-hide');
						el.classList.remove('pz-show');
					}
				});
			}
		});
	}
})();
