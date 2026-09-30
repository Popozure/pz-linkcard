(function () {
	'use strict';

	const selector = '.lkc-content .lkc-excerpt';

	function getLineHeight(style) {
		const lineHeight = parseFloat(style.lineHeight);
		if (Number.isFinite(lineHeight) && lineHeight > 0) {
			return lineHeight;
		}

		const fontSize = parseFloat(style.fontSize);
		return Number.isFinite(fontSize) && fontSize > 0 ? fontSize * 1.2 : 16;
	}

	function fitExcerpt(excerpt) {
		const content = excerpt.closest('.lkc-content');
		if (!content) {
			return;
		}

		const contentStyle = window.getComputedStyle(content);
		if (contentStyle.overflowY === 'visible' && contentStyle.overflow === 'visible') {
			excerpt.style.maxHeight = '';
			return;
		}

		const excerptStyle = window.getComputedStyle(excerpt);
		const lineHeight = getLineHeight(excerptStyle);
		const contentRect = content.getBoundingClientRect();
		const excerptRect = excerpt.getBoundingClientRect();
		const paddingTop = parseFloat(excerptStyle.paddingTop) || 0;
		const paddingBottom = parseFloat(excerptStyle.paddingBottom) || 0;
		const borderTop = parseFloat(excerptStyle.borderTopWidth) || 0;
		const borderBottom = parseFloat(excerptStyle.borderBottomWidth) || 0;
		const reservedHeight = paddingTop + paddingBottom + borderTop + borderBottom;
		const available = contentRect.bottom - excerptRect.top - reservedHeight;
		const lines = Math.floor(available / lineHeight);

		if (lines <= 0) {
			if (excerpt.style.maxHeight !== '0px') {
				excerpt.style.maxHeight = '0px';
			}
			return;
		}

		const maxHeight = lines * lineHeight + (excerptStyle.boxSizing === 'border-box' ? reservedHeight : 0);
		const nextMaxHeight = maxHeight + 'px';
		if (excerpt.style.maxHeight !== nextMaxHeight) {
			excerpt.style.maxHeight = nextMaxHeight;
		}
	}

	function fitAll() {
		document.querySelectorAll(selector).forEach(fitExcerpt);
	}

	let resizeTimer = 0;
	function requestFit() {
		window.clearTimeout(resizeTimer);
		resizeTimer = window.setTimeout(fitAll, 50);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', fitAll);
	} else {
		fitAll();
	}

	window.addEventListener('load', fitAll);
	window.addEventListener('resize', requestFit);

	if (document.fonts && document.fonts.ready) {
		document.fonts.ready.then(requestFit);
	}

	if ('MutationObserver' in window) {
		const observer = new MutationObserver((mutations) => {
			if (mutations.some((mutation) => Array.from(mutation.addedNodes).some((node) =>
				node.nodeType === 1 && (node.matches?.(selector) || node.querySelector?.(selector))
			))) {
				requestFit();
			}
		});
		observer.observe(document.documentElement, { childList: true, subtree: true });
	}
})();
