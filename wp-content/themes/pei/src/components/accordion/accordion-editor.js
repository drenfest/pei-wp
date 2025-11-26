/* global wp */
(function(){
  const { __ } = wp.i18n;
  const { Fragment } = wp.element;
  const { PanelBody, ToggleControl, TextControl, TextareaControl, Button } = wp.components;
  const { InspectorControls } = wp.blockEditor || wp.editor;
  const ServerSideRender = wp.serverSideRender;

  function ItemEditor({item, index, onChange, onRemove}){
    const set = (patch) => onChange(index, { ...item, ...patch });
    return wp.element.createElement('div', { className: 'pei-acc-item'},
      wp.element.createElement(TextControl, { label: __('Title', 'pei'), value: item.title || '', onChange: (v)=>set({title:v}) }),
      wp.element.createElement(TextareaControl, { label: __('Content', 'pei'), value: item.content || '', onChange: (v)=>set({content:v}) }),
      wp.element.createElement(Button, { isDestructive:true, onClick: ()=>onRemove(index) }, __('Remove Item', 'pei'))
    );
  }

  wp.blocks.registerBlockType('pei/accordion', {
    edit: (props) => {
      const { attributes, setAttributes } = props;
      const { light = true, items = [] } = attributes;
      const setItem = (i, patch) => setAttributes({ items: items.map((it, idx)=> idx===i ? patch : it) });
      const addItem = () => setAttributes({ items: items.concat({ title: '', content: '' }) });
      const removeItem = (i) => setAttributes({ items: items.filter((_, idx)=> idx!==i) });

      return wp.element.createElement(Fragment, null,
        wp.element.createElement(InspectorControls, null,
          wp.element.createElement(PanelBody, { title: __('Accordion Settings','pei'), initialOpen:true },
            wp.element.createElement(ToggleControl, { label: __('Light Style', 'pei'), checked: !!light, onChange: (v)=> setAttributes({ light: !!v }) }),
            wp.element.createElement('div', { className:'pei-acc-list' },
              items.map((it,i)=> wp.element.createElement(ItemEditor, { key:i, item:it, index:i, onChange:setItem, onRemove:removeItem })),
              wp.element.createElement(Button, { isPrimary:true, onClick:addItem }, __('Add Item','pei'))
            )
          )
        ),
        wp.element.createElement(ServerSideRender, { block: 'pei/accordion', attributes })
      );
    },
    save: () => null,
  });
})();
