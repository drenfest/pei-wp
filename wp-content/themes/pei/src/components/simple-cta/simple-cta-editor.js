/* global wp */
(function () {
  const { registerBlockType } = wp.blocks;
  const { __ } = wp.i18n;
  const { PanelBody, TextareaControl, TextControl, RadioControl } = wp.components;
  const { InspectorControls } = wp.blockEditor || wp.editor;
  const ServerSideRender = wp.serverSideRender;
  const LinkControl = (wp.blockEditor && wp.blockEditor.__experimentalLinkControl) || (wp.editor && wp.editor.__experimentalLinkControl);

  const blockName = 'pei/simple-cta';

  registerBlockType(blockName, {
    edit: (props) => {
      const { attributes, setAttributes } = props;
      const { message, buttonText, phone, linkType, pageId, customUrl } = attributes;

      const onLinkChange = (value) => {
        if (!value) return;
        // value like { url, kind, type, id } from LinkControl
        if (linkType === 'page') {
          setAttributes({ pageId: value.id || 0 });
        } else {
          setAttributes({ customUrl: value.url || '' });
        }
      };

      const linkControlValue = linkType === 'page'
        ? (pageId ? { id: pageId, url: undefined } : undefined)
        : { url: customUrl || `tel:${(phone || '').replace(/[^0-9+]/g, '')}` };

      return (
        wp.element.createElement(wp.element.Fragment, null,
          wp.element.createElement(InspectorControls, null,
            wp.element.createElement(PanelBody, { title: __('CTA Content', 'pei'), initialOpen: true },
              wp.element.createElement(TextareaControl, {
                label: __('Message', 'pei'),
                help: __('Use a line break to split lines.', 'pei'),
                value: message || '',
                onChange: (v) => setAttributes({ message: v })
              }),
              wp.element.createElement(TextControl, {
                label: __('Button Text', 'pei'),
                value: buttonText || '',
                onChange: (v) => setAttributes({ buttonText: v })
              }),
              wp.element.createElement(TextControl, {
                label: __('Phone (used for tel: when custom link is empty)', 'pei'),
                value: phone || '',
                onChange: (v) => setAttributes({ phone: v })
              })
            ),
            wp.element.createElement(PanelBody, { title: __('Link Settings', 'pei'), initialOpen: true },
              wp.element.createElement(RadioControl, {
                label: __('Link Type', 'pei'),
                selected: linkType || 'custom',
                options: [
                  { label: __('Custom URL (tel: recommended)', 'pei'), value: 'custom' },
                  { label: __('Select a Page', 'pei'), value: 'page' }
                ],
                onChange: (v) => setAttributes({ linkType: v })
              }),
              LinkControl ? wp.element.createElement(LinkControl, {
                value: linkControlValue,
                withCreateSuggestion: false,
                settings: [],
                onChange: onLinkChange
              }) : null,
              linkType === 'custom' && !LinkControl && (
                // Fallback if LinkControl API not present
                wp.element.createElement(TextControl, {
                  label: __('Custom URL', 'pei'),
                  value: customUrl || `tel:${(phone || '').replace(/[^0-9+]/g, '')}`,
                  onChange: (v) => setAttributes({ customUrl: v })
                })
              ),
              linkType === 'page' && !LinkControl && (
                wp.element.createElement(TextControl, {
                  label: __('Page ID', 'pei'),
                  help: __('Enter a Page ID if search UI is unavailable in your editor build.', 'pei'),
                  value: pageId || 0,
                  onChange: (v) => setAttributes({ pageId: parseInt(v || '0', 10) || 0 })
                })
              )
            )
          ),
          wp.element.createElement(ServerSideRender, { block: blockName, attributes })
        )
      );
    },
    save: () => null
  });
})();
