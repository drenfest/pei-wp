/* Wrap everything in an IIFE to avoid re-declaring consts across multiple editor scripts */
(function() {
  const { __ } = wp.i18n;
  const { useState } = wp.element;
  const { PanelBody, Button, TextControl, SelectControl, Placeholder, Spinner, BaseControl } = wp.components;
  const { InspectorControls, MediaUpload } = wp.blockEditor || wp.editor;
  const { useSelect } = wp.data;
  const ServerSideRender = wp.serverSideRender;

  function normalizeItems(items) {
    if (!Array.isArray(items)) return [];
    return items.map((it) => ({
      id: typeof it.id === 'number' ? it.id : (it.id ? Number(it.id) : 0),
      url: typeof it.url === 'string' ? it.url : '',
      title: typeof it.title === 'string' ? it.title : '',
      pageId: typeof it.pageId === 'number' ? it.pageId : (it.pageId ? Number(it.pageId) : 0),
    }));
  }

  wp.blocks.registerBlockType('pei/services', {
    edit: (props) => {
      const { attributes, setAttributes } = props;
      const { heading = 'Services', items = [], images = [], titles = [] } = attributes;

      // Load up to 100 pages for the selector; can be adjusted later
      const pages = useSelect((select) => {
        const core = select('core');
        return core.getEntityRecords('postType', 'page', { per_page: 100, _fields: ['id','title','link'] });
      }, []);

      const isLoadingPages = pages === undefined;
      const pageOptions = [ { label: __('— Select a page —', 'pei'), value: 0 } ];
      if (Array.isArray(pages)) {
        pages.forEach((p) => pageOptions.push({ label: p.title && p.title.rendered ? p.title.rendered : `#${p.id}`, value: p.id }));
      }

      const setItems = (newItems) => setAttributes({ items: normalizeItems(newItems) });

      const onSelectImages = (media) => {
        if (!media || !media.length) return;
        const appended = media.map((m) => ({ id: m.id, url: m.url || (m.sizes && m.sizes.full && m.sizes.full.url) || '', title: m.alt || m.title || '', pageId: 0 }));
        setItems([ ...normalizeItems(items), ...appended ]);
      };

      const importLegacyIfAny = () => {
        if (!items || items.length) return; // nothing to do
        if ((!images || !images.length) && (!titles || !titles.length)) return;
        const max = Math.max(images.length || 0, titles.length || 0);
        const imported = [];
        for (let i = 0; i < max; i++) {
          imported.push({ id: 0, url: images[i] ? images[i] : '', title: titles[i] ? titles[i] : '', pageId: 0 });
        }
        setItems(imported);
      };

      // Auto-offer legacy import once in the session
      const [askedImport, setAskedImport] = useState(false);
      if (!askedImport && (!items || !items.length) && ((images && images.length) || (titles && titles.length))) {
        // Show placeholder with import action instead of the preview until choice
        return (
          wp.element.createElement(Placeholder, { label: __('Services', 'pei'), instructions: __('Import existing CSV data or start by selecting images.', 'pei') },
            wp.element.createElement('div', { style: { display: 'flex', gap: '8px' } },
              wp.element.createElement(Button, { variant: 'primary', onClick: () => { importLegacyIfAny(); setAskedImport(true); } }, __('Import legacy data', 'pei')),
              wp.element.createElement(Button, { variant: 'secondary', onClick: () => setAskedImport(true) }, __('Skip', 'pei'))
            )
          )
        );
      }

      const ItemEditor = ({ item, index }) => {
        const onReplaceImage = (media) => {
          const copy = normalizeItems(items);
          copy[index] = { ...copy[index], id: media.id, url: media.url || (media.sizes && media.sizes.full && media.sizes.full.url) || copy[index].url };
          setItems(copy);
        };
        const onChangeTitle = (v) => {
          const copy = normalizeItems(items);
          copy[index] = { ...copy[index], title: v };
          setItems(copy);
        };
        const onChangePage = (v) => {
          const copy = normalizeItems(items);
          copy[index] = { ...copy[index], pageId: Number(v) || 0 };
          setItems(copy);
        };
        const onRemove = () => {
          const copy = normalizeItems(items);
          copy.splice(index, 1);
          setItems(copy);
        };
        const move = (dir) => {
          const copy = normalizeItems(items);
          const newIndex = index + dir;
          if (newIndex < 0 || newIndex >= copy.length) return;
          const [moved] = copy.splice(index, 1);
          copy.splice(newIndex, 0, moved);
          setItems(copy);
        };

        return wp.element.createElement('div', { className: 'pei-services-item-editor', style: { display: 'flex', flexDirection: 'column', alignItems: 'stretch', gap: '8px', borderBottom: '1px solid #ddd', padding: '10px 0' } },
        wp.element.createElement('img', { src: item.url, alt: item.title || '', style: { width: 96, height: 96, objectFit: 'cover', background: '#eee', alignSelf: 'flex-start' } }),
        wp.element.createElement(MediaUpload, {
          onSelect: onReplaceImage,
          allowedTypes: ['image'],
          render: ({ open }) => wp.element.createElement(Button, { onClick: open, variant: 'secondary' }, __('Replace Image', 'pei'))
        }),
        wp.element.createElement(TextControl, { label: __('Title', 'pei'), value: item.title || '', onChange: onChangeTitle }),
        isLoadingPages ? wp.element.createElement(Spinner, null) : wp.element.createElement(SelectControl, { label: __('Link to Page', 'pei'), value: item.pageId || 0, options: pageOptions, onChange: onChangePage }),
        wp.element.createElement('div', { style: { display: 'flex', gap: '8px' } },
          wp.element.createElement(Button, { onClick: () => move(-1), disabled: index === 0 }, '↑'),
          wp.element.createElement(Button, { onClick: () => move(1), disabled: index === (items.length - 1) }, '↓'),
          wp.element.createElement(Button, { isDestructive: true, onClick: onRemove }, __('Remove', 'pei'))
        )
      );
      };

      return (
        wp.element.createElement(
          wp.element.Fragment,
          null,
          wp.element.createElement(
            InspectorControls,
            null,
            wp.element.createElement(
              PanelBody,
              { title: __('Services Settings', 'pei'), initialOpen: true },
              wp.element.createElement(TextControl, {
                label: __('Heading', 'pei'),
                value: heading,
                onChange: (v) => setAttributes({ heading: v })
              }),
              wp.element.createElement(BaseControl, { label: __('Items', 'pei') },
                wp.element.createElement('div', null,
                  (normalizeItems(items)).map((item, i) => wp.element.createElement(ItemEditor, { key: i, item, index: i }))
                ),
              ),
              wp.element.createElement(MediaUpload, {
                gallery: true,
                multiple: true,
                addToGallery: true,
                onSelect: onSelectImages,
                allowedTypes: ['image'],
                render: ({ open }) => wp.element.createElement(Button, { variant: 'primary', onClick: open }, __('Add Images', 'pei'))
              })
            )
          ),
          wp.element.createElement(ServerSideRender, {
            block: 'pei/services',
            attributes
          })
        )
      );
    },
    save: () => null,
  });
})();
