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

	function fitExcerpts(excerpts) {
		const candidates = Array.from(excerpts)
			.filter((excerpt) => excerpt.isConnected)
			.map((excerpt) => ({ excerpt, content: excerpt.closest('.lkc-content') }))
			.filter(({ content }) => content);

		candidates.forEach(({ excerpt }) => {
			excerpt.style.maxHeight = '';
		});

		const measurements = candidates.map(({ excerpt, content }) => {
			const contentStyle = window.getComputedStyle(content);
			if (contentStyle.overflowY === 'visible' && contentStyle.overflow === 'visible') {
				return { excerpt, maxHeight: '' };
			}

			const excerptStyle = window.getComputedStyle(excerpt);
			const lineHeight = getLineHeight(excerptStyle);
			const paddingTop = parseFloat(excerptStyle.paddingTop) || 0;
			const paddingBottom = parseFloat(excerptStyle.paddingBottom) || 0;
			const borderTop = parseFloat(excerptStyle.borderTopWidth) || 0;
			const borderBottom = parseFloat(excerptStyle.borderBottomWidth) || 0;
			const reservedHeight = paddingTop + paddingBottom + borderTop + borderBottom;
			const available = content.getBoundingClientRect().bottom - excerpt.getBoundingClientRect().top - reservedHeight;
			const lines = Math.max(0, Math.floor(available / lineHeight));
			const maxHeight = lines * lineHeight + (excerptStyle.boxSizing === 'border-box' ? reservedHeight : 0);
			return { excerpt, maxHeight: maxHeight + 'px' };
		});

		measurements.forEach(({ excerpt, maxHeight }) => {
			if (excerpt.style.maxHeight !== maxHeight) excerpt.style.maxHeight = maxHeight;
		});
	}

	function fitAll() {
		fitExcerpts(document.querySelectorAll(selector));
	}

	const pendingExcerpts = new Set();
	let fitFrame = 0;
	function requestFit(excerpts = document.querySelectorAll(selector)) {
		excerpts.forEach((excerpt) => pendingExcerpts.add(excerpt));
		if (fitFrame) return;
		fitFrame = window.requestAnimationFrame(() => {
			fitFrame = 0;
			const excerptsToFit = Array.from(pendingExcerpts);
			pendingExcerpts.clear();
			fitExcerpts(excerptsToFit);
		});
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
			const addedExcerpts = new Set();
			mutations.forEach((mutation) => mutation.addedNodes.forEach((node) => {
				if (node.nodeType !== 1) return;
				if (node.matches?.('.lkc-excerpt')) addedExcerpts.add(node);
				node.querySelectorAll?.('.lkc-excerpt').forEach((excerpt) => addedExcerpts.add(excerpt));
			}));
			if (addedExcerpts.size) requestFit(addedExcerpts);
		});
		observer.observe(document.documentElement, { childList: true, subtree: true });
	}
})();
