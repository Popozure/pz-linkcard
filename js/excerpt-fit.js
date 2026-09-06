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

		excerpt.style.maxHeight = '';

		const contentStyle = window.getComputedStyle(content);
		if (contentStyle.overflowY === 'visible' && contentStyle.overflow === 'visible') {
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
			excerpt.style.maxHeight = '0px';
			return;
		}

		const maxHeight = lines * lineHeight + (excerptStyle.boxSizing === 'border-box' ? reservedHeight : 0);
		excerpt.style.maxHeight = maxHeight + 'px';
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
})();
