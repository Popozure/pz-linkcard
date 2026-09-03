/* Pz-LinkCard block editor integration. */

(() => {
	const { wp, pz_lkc_block_icon: blockIcon } = window;
	if (!wp?.blocks || !wp?.element || !wp?.data || !wp?.blockEditor || !wp?.hooks || !wp?.compose) return;

	const { createBlock, registerBlockType, registerBlockVariation } = wp.blocks;
	const { createElement: el, useEffect, useState } = wp.element;
	const { useDispatch, useSelect } = wp.data;
	const { store: blockEditorStore } = wp.blockEditor;
	const useEditorBlockProps = wp.blockEditor.useBlockProps || ((props) => props);
	const { addFilter } = wp.hooks;
	const { createHigherOrderComponent } = wp.compose;
	const ServerSideRender = wp.serverSideRender;
	const blockName = blockIcon?.blockName || "pz-linkcard/linkcard";
	const defaultShortcode = (blockIcon?.shortcode || "blogcard").replace(/[^a-zA-Z0-9]/g, "") || "blogcard";
	const blockTitle = blockIcon?.title || "Pz-LinkCard";
	const urlPlaceholder = blockIcon?.placeholder || "Enter the URL and press Enter";
	const blockDescription = blockIcon?.description || "Create a Pz-LinkCard shortcode.";
	const shortcodes = Array.from(
		new Set(
			(blockIcon?.shortcodes || [defaultShortcode])
				.map((name) => String(name || "").replace(/[^a-zA-Z0-9]/g, ""))
				.filter(Boolean)
		)
	);
	if (!shortcodes.includes(defaultShortcode)) shortcodes.unshift(defaultShortcode);

	const icon = {
		src: () =>
			el(
				"svg",
				{
					viewBox: "0 0 512 512",
					width: "24",
					height: "24",
					role: "img",
					"aria-hidden": "true",
					focusable: "false",
				},
				el("path", {
					fill: "none",
					stroke: "currentColor",
					strokeWidth: "38",
					strokeLinecap: "round",
					strokeLinejoin: "round",
					d: "M 280,385 H 95 C 65,385 40,360 40,330 V 135 C 40,105 65,80 95,80 H 417 C 447,80 472,105 472,135 V 250",
				}),
				el(
					"text",
					{
						x: "76",
						y: "315",
						fill: "currentColor",
						fontFamily: "Georgia, serif",
						fontSize: "245",
						fontWeight: "700",
					},
					"Pz"
				),
				el("path", {
					fill: "none",
					stroke: "currentColor",
					strokeWidth: "38",
					strokeLinecap: "round",
					strokeLinejoin: "round",
					d: "m 350,313 42,-42 a 32,32 0 0 1 45,45 l -22,22",
				}),
				el("path", {
					fill: "none",
					stroke: "currentColor",
					strokeWidth: "38",
					strokeLinecap: "round",
					strokeLinejoin: "round",
					d: "m 360,353 -27,27 a 32,32 0 0 0 45,45 l 42,-42",
				})
			),
	};

	const escapeRegExp = (value) => String(value).replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
	const decodeShortcodeAttribute = (value) =>
		String(value || "")
			.replace(/&quot;/g, '"')
			.replace(/&#039;/g, "'")
			.replace(/&#91;/g, "[")
			.replace(/&#93;/g, "]")
			.replace(/&amp;/g, "&");
	const escapeShortcodeAttribute = (value) =>
		String(value || "")
			.replace(/&/g, "&amp;")
			.replace(/"/g, "&quot;")
			.replace(/\[/g, "&#91;")
			.replace(/\]/g, "&#93;");
	const buildShortcodeText = (url, shortcodeName = defaultShortcode, sourceText = "") => {
		const text = String(sourceText || "");
		const nextUrl = `url="${escapeShortcodeAttribute(url)}"`;
		if (text) {
			const replaced = text.replace(/\burl\s*=\s*(?:"[^"]*(?:"|$)|'[^']*(?:'|$)|[^\s\]]+)/i, nextUrl);
			if (replaced !== text) return replaced;
		}
		return `[${shortcodeName} ${nextUrl}]`;
	};
	const parseShortcode = (text) => {
		const shortcodeNames = shortcodes.map(escapeRegExp).join("|");
		const pattern = new RegExp("^\\s*\\[(" + shortcodeNames + ")\\b([^\\]]*)(?:\\]\\s*)?$", "i");
		const match = String(text || "").match(pattern);
		if (!match) return null;

		const urlMatch = String(match[2] || "").match(/\burl\s*=\s*(?:"([^"]*)"?|'([^']*)'?|([^\s\]]+))/i);
		if (!urlMatch) return null;

		return {
			shortcode: match[1],
			url: decodeShortcodeAttribute(urlMatch[1] ?? urlMatch[2] ?? urlMatch[3] ?? ""),
			text: String(text || ""),
		};
	};
	const isPzShortcodeBlock = (attributes) => parseShortcode(attributes?.text) !== null;

	const PzLinkCardEditor = ({ url, shortcodeName, commitUrl, clientId }) => {
		const { removeBlock } = useDispatch(blockEditorStore);
		const [tempUrl, setTempUrl] = useState(url || "");
		const isSelected = useSelect(
			(select) => select(blockEditorStore).getSelectedBlockClientId() === clientId,
			[clientId]
		);

		useEffect(() => {
			setTempUrl(url || "");
		}, [url]);

		const blockProps = useEditorBlockProps({
			className: "pz-linkcard-block-editor",
			tabIndex: 0,
			onClick: (event) => {
				if (event.target.closest("a")) {
					event.preventDefault();
					event.stopPropagation();
				}
			},
			onKeyDown: (event) => {
				if (
					isSelected &&
					event.target === event.currentTarget &&
					(event.key === "Delete" || event.key === "Backspace")
				) {
					event.preventDefault();
					removeBlock(clientId);
				}
			},
			style: {
				backgroundColor: "rgba(240, 250, 255, 0.2)",
				border: "1px solid #2277bb",
				borderRadius: "4px",
				boxSizing: "border-box",
				padding: "12px",
			},
		});

		return el(
			"div",
			blockProps,
			el(
				"div",
				{
					style: {
						color: "#111827",
						fontSize: "13px",
						fontWeight: "700",
						lineHeight: "1.4",
						marginBottom: "6px",
					},
				},
				blockTitle
			),
			el("input", {
				type: "url",
				value: tempUrl,
				placeholder: urlPlaceholder,
				onChange: (event) => setTempUrl(event.target.value),
				onKeyDown: (event) => {
					if (event.key === "Enter") {
						event.preventDefault();
						commitUrl(tempUrl);
					}
				},
				onBlur: () => commitUrl(tempUrl),
				style: {
					width: "100%",
					padding: "6px",
					fontSize: "14px",
					boxSizing: "border-box",
					marginBottom: "10px",
				},
			}),
			url && ServerSideRender
				? el(ServerSideRender, {
						block: blockName,
						attributes: { url, shortcode: shortcodeName || defaultShortcode },
				  })
				: null
		);
	};

	registerBlockVariation("core/shortcode", {
		name: "pz-linkcard",
		title: blockTitle,
		description: blockDescription,
		icon,
		attributes: {
			text: buildShortcodeText("", defaultShortcode),
		},
		isActive: isPzShortcodeBlock,
	});

	addFilter(
		"editor.BlockEdit",
		"pz-linkcard/shortcode-edit",
		createHigherOrderComponent(
			(BlockEdit) =>
				(props) => {
					if (props.name !== "core/shortcode" || !isPzShortcodeBlock(props.attributes)) {
						return el(BlockEdit, props);
					}

					const parsed = parseShortcode(props.attributes.text);
					return el(PzLinkCardEditor, {
						url: parsed.url,
						shortcodeName: parsed.shortcode,
						clientId: props.clientId,
						commitUrl: (nextUrl) =>
							props.setAttributes({
								text: buildShortcodeText(nextUrl, parsed.shortcode, parsed.text),
							}),
					});
				},
			"withPzLinkCardShortcodeEdit"
		)
	);

	registerBlockType(blockName, {
		title: blockTitle,
		icon,
		category: "widgets",
		supports: {
			inserter: false,
		},
		attributes: {
			url: {
				type: "string",
				default: "",
			},
			shortcode: {
				type: "string",
				default: "",
			},
		},
		edit: ({ attributes, clientId }) => {
			const { replaceBlock } = useDispatch(blockEditorStore);
			const url = attributes.url || "";

			useEffect(() => {
				if (url) {
					replaceBlock(clientId, createBlock("core/shortcode", { text: buildShortcodeText(url) }));
				}
			}, [clientId, replaceBlock, url]);

			return el(PzLinkCardEditor, {
				url,
				shortcodeName: defaultShortcode,
				clientId,
				commitUrl: (nextUrl) =>
					replaceBlock(clientId, createBlock("core/shortcode", { text: buildShortcodeText(nextUrl) })),
			});
		},
		save: () => null,
	});
})();
