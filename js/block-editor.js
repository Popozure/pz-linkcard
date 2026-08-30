(function(blocks, element, blockEditor, components, data, hooks, i18n) {
	if (!blocks || !element || !components || !data) {
		return;
	}

	var el = element.createElement;
	var __ = i18n && i18n.__ ? i18n.__ : function(text) { return text; };
	var InspectorControls = blockEditor && blockEditor.InspectorControls;
	var useBlockProps = blockEditor && blockEditor.useBlockProps;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;
	var PanelBody = components.PanelBody;
	var shortcode = (window.pzLinkCardBlock && window.pzLinkCardBlock.shortcode) || 'blogcard';
	var placeholderUrl = (window.pzLinkCardBlock && window.pzLinkCardBlock.placeholderUrl) || __('Enter URL here...', 'pz-linkcard');

	function escapeShortcodeAttr(value) {
		return String(value || '').replace(/"/g, '&quot;').replace(/\[/g, '&#91;').replace(/\]/g, '&#93;');
	}

	function makeShortcode(attributes) {
		var parts = ['[' + shortcode];
		if (attributes.url || Object.prototype.hasOwnProperty.call(attributes, 'url')) {
			parts.push('url="' + escapeShortcodeAttr(attributes.url) + '"');
		}
		if (attributes.title) {
			parts.push('title="' + escapeShortcodeAttr(attributes.title) + '"');
		}
		if (attributes.content) {
			parts.push('content="' + escapeShortcodeAttr(attributes.content) + '"');
		}
		return parts.join(' ') + ']';
	}

	function unescapeShortcodeAttr(value) {
		return String(value || '').replace(/&quot;/g, '"').replace(/&#91;/g, '[').replace(/&#93;/g, ']');
	}

	function parseShortcodeAttr(text, name) {
		var pattern = new RegExp("(?:^|\\s)" + name + "\\s*=\\s*(?:\"([^\"]*)\"|'([^']*)'|([^\\s\\]]+))", "i");
		var match = String(text || '').match(pattern);
		return match ? unescapeShortcodeAttr(match[1] || match[2] || match[3] || '') : '';
	}

	function parseLinkCardShortcode(text) {
		var source = String(text || '').trim();
		var pattern = new RegExp('^\\[' + shortcode.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '(?:\\s[^\\]]*)?\\]\\s*$', 'i');
		if (!pattern.test(source)) {
			return null;
		}
		return {
			url: parseShortcodeAttr(source, 'url'),
			title: parseShortcodeAttr(source, 'title'),
			content: parseShortcodeAttr(source, 'content')
		};
	}

	function replaceWithShortcodeBlock(clientId, attributes) {
		var shortcodeText = makeShortcode(attributes);
		var shortcodeBlock = blocks.createBlock('core/shortcode', {
			text: shortcodeText
		});
		data.dispatch('core/block-editor').replaceBlock(clientId, shortcodeBlock);
	}

	function createControls(attributes, onChange) {
		return [
			el(TextControl, {
				label: 'URL',
				value: attributes.url,
				type: 'url',
				onChange: function(value) {
					onChange({ url: value });
				}
			}),
			el(TextControl, {
				label: __('Title', 'pz-linkcard'),
				value: attributes.title,
				onChange: function(value) {
					onChange({ title: value });
				}
			}),
			el(TextareaControl, {
				label: __('Content', 'pz-linkcard'),
				value: attributes.content,
				onChange: function(value) {
					onChange({ content: value });
				}
			})
		];
	}

	function renderLinkCardEditor(props, attributes, onChange) {
		var shortcodeText = makeShortcode(attributes);
		var inspector = InspectorControls ? el(InspectorControls, null, el(PanelBody, { title: 'Pz-LinkCard', initialOpen: true }, createControls(attributes, onChange))) : null;
		var blockProps = useBlockProps ? useBlockProps({ className: 'pz-linkcard-block-editor' }) : { className: 'pz-linkcard-block-editor' };

		return el('div', blockProps,
			inspector,
			el('div', { className: 'pz-linkcard-block-editor__body' },
				el('div', { className: 'pz-linkcard-block-editor__title' }, 'Pz-LinkCard'),
				el(TextControl, {
					value: attributes.url,
					type: 'url',
					placeholder: placeholderUrl,
					onChange: function(value) {
						onChange({ url: value });
					}
				}),
				el('code', null, shortcodeText)
			)
		);
	}

	blocks.registerBlockType('pz-linkcard/linkcard', {
		title: 'Pz-LinkCard',
		description: __('Insert a Pz-LinkCard shortcode.', 'pz-linkcard'),
		icon: 'admin-links',
		category: 'widgets',
		attributes: {
			url: {
				type: 'string',
				default: ''
			},
			title: {
				type: 'string',
				default: ''
			},
			content: {
				type: 'string',
				default: ''
			}
		},
		edit: function(props) {
			if (element.useEffect) {
				element.useEffect(function() {
					replaceWithShortcodeBlock(props.clientId, props.attributes);
				}, []);
			} else {
				window.setTimeout(function() {
					replaceWithShortcodeBlock(props.clientId, props.attributes);
				}, 0);
			}
			return null;
		},
		save: function(props) {
			if (!props.attributes.url) {
				return null;
			}
			if (element.RawHTML) {
				return el(element.RawHTML, null, makeShortcode(props.attributes));
			}
			return makeShortcode(props.attributes);
		}
	});

	if (blocks.registerBlockVariation) {
		blocks.registerBlockVariation('core/shortcode', {
			name: 'pz-linkcard',
			title: 'Pz-LinkCard',
			description: __('Edit a Pz-LinkCard shortcode.', 'pz-linkcard'),
			icon: 'admin-links',
			attributes: {
				text: '[' + shortcode + ' url=""]'
			},
			isActive: function(blockAttributes) {
				return !!parseLinkCardShortcode(blockAttributes && blockAttributes.text);
			},
			scope: ['block', 'transform']
		});
	}

	if (hooks && hooks.addFilter) {
		hooks.addFilter('editor.BlockEdit', 'pz-linkcard/shortcode-block-edit', function(BlockEdit) {
		return function(props) {
			var attributes;
			if (props.name !== 'core/shortcode') {
				return el(BlockEdit, props);
			}

			attributes = parseLinkCardShortcode(props.attributes && props.attributes.text);
			if (!attributes) {
				return el(BlockEdit, props);
			}

			function updateShortcode(values) {
				var nextAttributes = {
					url: Object.prototype.hasOwnProperty.call(values, 'url') ? values.url : attributes.url,
					title: Object.prototype.hasOwnProperty.call(values, 'title') ? values.title : attributes.title,
					content: Object.prototype.hasOwnProperty.call(values, 'content') ? values.content : attributes.content
				};
				props.setAttributes({ text: makeShortcode(nextAttributes) });
			}

			return renderLinkCardEditor(
				props,
				attributes,
				updateShortcode
			);
		};
		});
	}
})(
	window.wp && window.wp.blocks,
	window.wp && window.wp.element,
	window.wp && (window.wp.blockEditor || window.wp.editor),
	window.wp && window.wp.components,
	window.wp && window.wp.data,
	window.wp && window.wp.hooks,
	window.wp && window.wp.i18n
);
