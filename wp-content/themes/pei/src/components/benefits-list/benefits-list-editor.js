/* global wp */
(function() {
  const { __ } = wp.i18n;
  const { Fragment } = wp.element;
  const { PanelBody, TextControl, TextareaControl, Button, CheckboxControl } = wp.components;
  const { InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor || wp.editor;
  const ServerSideRender = wp.serverSideRender;

  wp.blocks.registerBlockType('pei/benefits-list', {
    edit: (props) => {
      const { attributes, setAttributes } = props;
      const {
        heading = '',
        imageId = 0,
        imageUrl = '',
        benefits = '',
        useBadges = false,
        badgeHtml1 = '',
        badgeHtml2 = '',
        badgeHtml3 = ''
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
              { title: __('Benefits List Settings', 'pei'), initialOpen: true },

              wp.element.createElement(TextControl, {
                label: __('Heading (allows HTML)', 'pei'),
                value: heading,
                onChange: (v) => setAttributes({ heading: v || '' })
              }),

              // Image selector
              wp.element.createElement('div', { className: 'pei-control-group' },
                wp.element.createElement('p', null, __('Image', 'pei')),
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
                      wp.element.createElement('img', { src: imageUrl, alt: '', style: { maxWidth: 120, height: 'auto' } }),
                      wp.element.createElement(Button, { variant: 'link', isDestructive: true, onClick: clearImage }, __('Remove', 'pei'))
                    )
                  : null)
              ),

              // Benefits list
              wp.element.createElement(TextareaControl, {
                label: __('Benefits (one per line; pipes \\| or commas also supported)', 'pei'),
                value: benefits,
                onChange: (v) => setAttributes({ benefits: v || '' })
              }),

              // Badges
              wp.element.createElement(CheckboxControl, {
                label: __('Use badges?', 'pei'),
                checked: !!useBadges,
                onChange: (v) => setAttributes({ useBadges: !!v })
              }),

              !!useBadges && wp.element.createElement(Fragment, null,
                wp.element.createElement(TextareaControl, {
                  label: __('Badge 1 HTML', 'pei'),
                  help: __('You can paste HTML. It will render on a pendant-shaped SVG background.', 'pei'),
                  value: badgeHtml1,
                  onChange: (v) => setAttributes({ badgeHtml1: v || '' })
                }),
                wp.element.createElement(TextareaControl, {
                  label: __('Badge 2 HTML', 'pei'),
                  value: badgeHtml2,
                  onChange: (v) => setAttributes({ badgeHtml2: v || '' })
                }),
                wp.element.createElement(TextareaControl, {
                  label: __('Badge 3 HTML', 'pei'),
                  value: badgeHtml3,
                  onChange: (v) => setAttributes({ badgeHtml3: v || '' })
                })
              )
            )
          ),

          // Live server-side preview
          wp.element.createElement(ServerSideRender, {
            block: 'pei/benefits-list',
            attributes
          })
        )
      );
    },
    save: () => null,
  });
})();
