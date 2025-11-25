/* global wp */
(function() {
  const { __ } = wp.i18n;
  const { Fragment } = wp.element;
  const { PanelBody, TextControl, Button, Spinner, SelectControl } = wp.components;
  const { InspectorControls, MediaUpload, MediaUploadCheck, InnerBlocks } = wp.blockEditor || wp.editor;
  const ServerSideRender = wp.serverSideRender;

  const OFFSET_OPTIONS = [
    { label: __('Top Left', 'pei'), value: 'offset-top-left' },
    { label: __('Top Right', 'pei'), value: 'offset-top-right' },
    { label: __('Bottom Left', 'pei'), value: 'offset-bottom-left' },
    { label: __('Bottom Right', 'pei'), value: 'offset-bottom-right' },
  ];

  wp.blocks.registerBlockType('pei/hero-badges', {
    edit: (props) => {
      const { attributes, setAttributes } = props;
      const {
        imageId = 0,
        imageUrl = '',
        benefits = '',
        offsetClass = 'offset-top-left',
      } = attributes;

      const onSelectImage = (media) => {
        if (!media) return;
        setAttributes({ imageId: media.id || 0, imageUrl: media.url || '' });
      };

      const clearImage = () => setAttributes({ imageId: 0, imageUrl: '' });

      return (
        wp.element.createElement(
          Fragment,
          null,
          wp.element.createElement(
            InspectorControls,
            null,
            wp.element.createElement(
              PanelBody,
              { title: __('Hero Badges Settings', 'pei'), initialOpen: true },

              // Image selector
              wp.element.createElement('div', { className: 'pei-control-group' },
                wp.element.createElement('p', null, __('Background Image', 'pei')),
                wp.element.createElement(MediaUploadCheck, null,
                  wp.element.createElement(MediaUpload, {
                    onSelect: onSelectImage,
                    allowedTypes: ['image'],
                    value: imageId || undefined,
                    render: ({ open }) => (
                      wp.element.createElement(Button, { variant: 'secondary', onClick: open }, imageId ? __('Change Image', 'pei') : __('Select Image', 'pei'))
                    )
                  })
                ),
                (imageUrl
                  ? wp.element.createElement('div', { style: { marginTop: 8, display: 'flex', alignItems: 'center', gap: 8 } },
                      wp.element.createElement('img', { src: imageUrl, alt: '', style: { maxWidth: 120, height: 'auto', display: 'block' } }),
                      wp.element.createElement(Button, { variant: 'link', isDestructive: true, onClick: clearImage }, __('Remove', 'pei'))
                    )
                  : null)
              ),

              // Benefits
              wp.element.createElement(TextControl, {
                label: __('Benefits (separate with | or ,)', 'pei'),
                value: benefits,
                onChange: (v) => setAttributes({ benefits: v || '' })
              }),

              // Offset selector
              wp.element.createElement(SelectControl, {
                label: __('Badge Row Offset', 'pei'),
                value: offsetClass || 'offset-top-left',
                options: OFFSET_OPTIONS,
                onChange: (v) => setAttributes({ offsetClass: v })
              })
            )
          ),

          // Live server-side preview
          wp.element.createElement(ServerSideRender, {
            block: 'pei/hero-badges',
            attributes
          }),

          // Child badges appender (no wrapper to avoid visual duplication with SSR)
          wp.element.createElement(InnerBlocks, {
            allowedBlocks: ['pei/hero-badge'],
            orientation: 'horizontal',
            renderAppender: InnerBlocks.ButtonBlockAppender
          })
        )
      );
    },
    save: () => null,
  });
})();
