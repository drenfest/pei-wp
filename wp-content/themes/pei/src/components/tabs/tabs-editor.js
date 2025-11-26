/* global wp */
(function () {
    if (!wp || !wp.blocks || !wp.element || !wp.components || !wp.i18n) {
        return;
    }

    const { __ } = wp.i18n;
    const { Fragment, useState } = wp.element;

    const PanelBody =
        (wp.components && wp.components.PanelBody) || null;
    const Button =
        (wp.components && wp.components.Button) || null;
    const TextControl =
        (wp.components && wp.components.TextControl) || null;
    const TextareaControl =
        (wp.components && wp.components.TextareaControl) || null;
    const MediaUpload =
        (wp.components && wp.components.MediaUpload) || null;
    const MediaUploadCheck =
        (wp.components && wp.components.MediaUploadCheck) || null;
    const Modal =
        (wp.components && wp.components.Modal) || null;

    const blockEditor = wp.blockEditor || wp.editor || {};
    const InspectorControls =
        blockEditor.InspectorControls || null;

    const ServerSideRender =
        (wp.serverSideRender && wp.serverSideRender) ||
        (wp.editor && wp.editor.ServerSideRender) ||
        null;

    // If critical editor pieces are missing, bail gracefully
    if (!InspectorControls || !PanelBody || !Button || !TextControl || !TextareaControl) {
        wp.blocks.registerBlockType('pei/tabs', {
            edit: function () {
                return wp.element.createElement(
                    'p',
                    null,
                    __('PEI Tabs block: editor components not fully available.', 'pei')
                );
            },
            save: function () {
                return null;
            },
        });
        return;
    }

    function TabEditor({ tab = {}, index, onChange, onRemove }) {
        const [isModalOpen, setIsModalOpen] = useState(false);

        const set = (patch) => {
            onChange(index, patch);
        };

        const placeholder =
            'data:image/svg+xml;utf8,' +
            encodeURIComponent(
                '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="450"><rect width="100%" height="100%" fill="#eee"/><text x="50%" y="50%" font-size="24" text-anchor="middle" fill="#888" dy=".3em">Placeholder</text></svg>'
            );

        return wp.element.createElement(
            'div',
            { className: 'pei-tabs-editor__item' },

            wp.element.createElement(TextControl, {
                label: __('Tab Label', 'pei'),
                value: tab.label || '',
                onChange: (v) => set({ label: v }),
            }),

            wp.element.createElement(
                'div',
                { className: 'pei-tabs-editor__content-row' },
                wp.element.createElement(TextareaControl, {
                    label: __('Content (preview)', 'pei'),
                    value: tab.content || '',
                    onChange: (v) => set({ content: v }),
                    help: Modal
                        ? __('Use "Edit Content" for a larger editor. You can type HTML here.', 'pei')
                        : __('You can type HTML; it will render on the front end.', 'pei'),
                }),
                Modal &&
                wp.element.createElement(
                    Button,
                    {
                        isSecondary: true,
                        onClick: () => setIsModalOpen(true),
                        style: { marginTop: '24px' },
                    },
                    __('Edit Content', 'pei')
                )
            ),

            MediaUpload &&
            MediaUploadCheck &&
            wp.element.createElement(
                'div',
                { className: 'pei-tabs-editor__media' },
                wp.element.createElement('img', {
                    src: tab.imageUrl || placeholder,
                    alt: '',
                    style: { maxWidth: '100%', height: 'auto', marginBottom: '8px' },
                }),
                wp.element.createElement(
                    MediaUploadCheck,
                    null,
                    wp.element.createElement(MediaUpload, {
                        onSelect: (media) =>
                            set({
                                imageId: media.id,
                                imageUrl: media.url,
                            }),
                        allowedTypes: ['image'],
                        value: tab.imageId || 0,
                        render: ({ open }) =>
                            wp.element.createElement(
                                Button,
                                { isSecondary: true, onClick: open },
                                __('Select Image', 'pei')
                            ),
                    })
                )
            ),

            wp.element.createElement(
                Button,
                {
                    isDestructive: true,
                    onClick: () => onRemove(index),
                    style: { marginTop: '12px' },
                },
                __('Remove Tab', 'pei')
            ),

            // Modal with big textarea
            Modal &&
            isModalOpen &&
            wp.element.createElement(
                Modal,
                {
                    title: __('Edit Tab Content', 'pei'),
                    onRequestClose: () => setIsModalOpen(false),
                    className: 'pei-tabs-editor__modal',
                },
                wp.element.createElement(TextareaControl, {
                    label: __('Tab Content (HTML allowed)', 'pei'),
                    value: tab.content || '',
                    onChange: (v) => set({ content: v }),
                    help: __(
                        'You can type or paste HTML here (e.g. <h3>Heading</h3>, <p>Paragraph</p>). It will render on the front end.',
                        'pei'
                    ),
                }),
                wp.element.createElement(
                    'div',
                    {
                        style: {
                            marginTop: '16px',
                            textAlign: 'right',
                        },
                    },
                    wp.element.createElement(
                        Button,
                        {
                            isPrimary: true,
                            onClick: () => setIsModalOpen(false),
                        },
                        __('Done', 'pei')
                    )
                )
            )
        );
    }

    wp.blocks.registerBlockType('pei/tabs', {
        edit: function (props) {
            const { attributes, setAttributes } = props;
            const { tabs = [], active = 0 } = attributes;

            const setTab = (i, patch) => {
                const newTabs = (tabs || []).map((t, idx) =>
                    idx === i ? { ...t, ...patch } : t
                );
                setAttributes({ tabs: newTabs });
            };

            const addTab = () => {
                const newIndex = (tabs || []).length + 1;
                const newTabs = (tabs || []).concat({
                    label: __('Tab ', 'pei') + newIndex,
                    content: '',
                    imageId: 0,
                    imageUrl: '',
                });

                setAttributes({
                    tabs: newTabs,
                    active: newTabs.length === 1 ? 0 : active,
                });
            };

            const removeTab = (i) => {
                const newTabs = (tabs || []).filter((_, idx) => idx !== i);
                let newActive = active;

                if (newActive >= newTabs.length) {
                    newActive = Math.max(0, newTabs.length - 1);
                }

                setAttributes({ tabs: newTabs, active: newActive });
            };

            const handleActiveChange = (v) => {
                const raw = parseInt(v || '0', 10);
                const clamped = isNaN(raw)
                    ? 0
                    : Math.max(0, Math.min(raw, Math.max(0, (tabs || []).length - 1)));

                setAttributes({ active: clamped });
            };

            return wp.element.createElement(
                Fragment,
                null,
                wp.element.createElement(
                    InspectorControls,
                    null,
                    wp.element.createElement(
                        PanelBody,
                        { title: __('Tabs', 'pei'), initialOpen: true },
                        wp.element.createElement(TextControl, {
                            label: __('Active Tab Index (0-based)', 'pei'),
                            type: 'number',
                            value: String(active || 0),
                            onChange: handleActiveChange,
                        }),
                        wp.element.createElement(
                            'div',
                            { className: 'pei-tabs-editor__list' },
                            (tabs || []).map((t, i) =>
                                wp.element.createElement(TabEditor, {
                                    key: i,
                                    tab: t,
                                    index: i,
                                    onChange: setTab,
                                    onRemove: removeTab,
                                })
                            ),
                            wp.element.createElement(
                                Button,
                                {
                                    isPrimary: true,
                                    onClick: addTab,
                                    style: { marginTop: '8px' },
                                },
                                __('Add Tab', 'pei')
                            )
                        )
                    )
                ),
                ServerSideRender &&
                wp.element.createElement(ServerSideRender, {
                    block: 'pei/tabs',
                    attributes,
                })
            );
        },

        save: function () {
            return null;
        },
    });
})();
