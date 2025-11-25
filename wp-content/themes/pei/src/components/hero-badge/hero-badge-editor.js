/* global wp */
(function() {
  const { __ } = wp.i18n;
  const { Fragment } = wp.element;
  const { PanelBody, TextControl } = wp.components;
  const { InspectorControls, InnerBlocks, RichText } = wp.blockEditor || wp.editor;
  const ServerSideRender = wp.serverSideRender;

  wp.blocks.registerBlockType('pei/hero-badge', {
    edit: (props) => {
      const { attributes, setAttributes } = props;
      const { title = '' } = attributes;

      return (
        wp.element.createElement(
          Fragment,
          null,
          wp.element.createElement(
            InspectorControls,
            null,
            wp.element.createElement(
              PanelBody,
              { title: __('Badge Settings', 'pei'), initialOpen: true },
              wp.element.createElement(TextControl, {
                label: __('Title', 'pei'),
                value: title,
                onChange: (v) => setAttributes({ title: v || '' })
              })
            )
          ),

          // Preview the single badge server-side for fidelity
          wp.element.createElement(ServerSideRender, {
            block: 'pei/hero-badge',
            attributes
          }),

          // Editable body as inner blocks content
          wp.element.createElement(InnerBlocks, {
            allowedBlocks: ['core/paragraph', 'core/list', 'core/heading', 'core/image'],
            renderAppender: InnerBlocks.ButtonBlockAppender
          })
        )
      );
    },
    save: () => null,
  });
})();
