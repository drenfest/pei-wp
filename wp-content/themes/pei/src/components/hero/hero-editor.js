/* global wp */
(function() {
  const { __ } = wp.i18n;
  const { useState } = wp.element;
  const { TextControl, PanelBody, SelectControl, CheckboxControl, Spinner } = wp.components;
  const { InspectorControls } = wp.blockEditor || wp.editor;
  const { useSelect } = wp.data;
  const ServerSideRender = wp.serverSideRender;

  wp.blocks.registerBlockType('pei/hero', {
    edit: (props) => {
      const { attributes, setAttributes } = props;
      const {
        title = '',
        subtitle = '',
        text = '',
        link_location = '', // legacy fallback
        link_text = '',
        linkMode = 'custom',
        linkPageId = 0,
        linkCustom = '',
        linkNoFollow = false,
        linkNewTab = false,
      } = attributes;

      // Load a list of pages for selection (up to 100)
      const pages = useSelect((select) => {
        const core = select('core');
        return core.getEntityRecords('postType', 'page', { per_page: 100, _fields: ['id','title'] });
      }, []);

      const isLoadingPages = pages === undefined;
      const pageOptions = [ { label: __('— Select a page —', 'pei'), value: 0 } ];
      if (Array.isArray(pages)) {
        pages.forEach((p) => pageOptions.push({ label: (p.title && p.title.rendered) ? p.title.rendered : `#${p.id}`, value: p.id }));
      }

      return (
        wp.element.createElement(
          wp.element.Fragment,
          null,
          wp.element.createElement(
            InspectorControls,
            null,
            wp.element.createElement(
              PanelBody,
              { title: __('Hero Settings', 'pei'), initialOpen: true },
              wp.element.createElement(TextControl, {
                label: __('Title (H1, allows HTML)', 'pei'),
                value: title,
                onChange: (v) => setAttributes({ title: v })
              }),
              wp.element.createElement(TextControl, {
                label: __('Subtitle', 'pei'),
                value: subtitle,
                onChange: (v) => setAttributes({ subtitle: v })
              }),
              wp.element.createElement(TextControl, {
                label: __('Text', 'pei'),
                value: text,
                onChange: (v) => setAttributes({ text: v })
              }),

              // Link controls
              wp.element.createElement(SelectControl, {
                label: __('CTA Link Type', 'pei'),
                value: linkMode,
                options: [
                  { label: __('Custom URL', 'pei'), value: 'custom' },
                  { label: __('Page', 'pei'), value: 'page' },
                ],
                onChange: (v) => setAttributes({ linkMode: v })
              }),
              linkMode === 'page'
                ? (isLoadingPages
                    ? wp.element.createElement(Spinner, null)
                    : wp.element.createElement(SelectControl, {
                        label: __('Link to Page', 'pei'),
                        value: linkPageId || 0,
                        options: pageOptions,
                        onChange: (v) => setAttributes({ linkPageId: Number(v) || 0 })
                      })
                  )
                : wp.element.createElement(TextControl, {
                    label: __('Custom URL', 'pei'),
                    help: link_location ? __('Legacy CTA URL exists; Custom URL overrides it.', 'pei') : undefined,
                    value: linkCustom,
                    onChange: (v) => setAttributes({ linkCustom: v })
                  }),

              wp.element.createElement(CheckboxControl, {
                label: __('Open in new tab', 'pei'),
                checked: !!linkNewTab,
                onChange: (v) => setAttributes({ linkNewTab: !!v })
              }),
              wp.element.createElement(CheckboxControl, {
                label: __('Add rel="nofollow"', 'pei'),
                checked: !!linkNoFollow,
                onChange: (v) => setAttributes({ linkNoFollow: !!v })
              }),

              wp.element.createElement(TextControl, {
                label: __('CTA Label', 'pei'),
                value: link_text,
                onChange: (v) => setAttributes({ link_text: v })
              })
            )
          ),
          wp.element.createElement(ServerSideRender, {
            block: 'pei/hero',
            attributes
          })
        )
      );
    },
    save: () => null,
  });
})();
