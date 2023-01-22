define([
    'jquery',
    'mage/url',
    'mage/adminhtml/events'
], function ($, url) {
    'use strict';

    function initialize(editor)
    {
        editor.addCommand('getProductBlock', function () {
            let sku = editor.selection.getContent();
            editor.selection.setContent('');
            let node = editor.selection.getNode();
            $.ajax({
                method: 'GET',
                url: url.build('community/wysiwyg/product'),
                data: {
                    sku: sku
                }
            }).done(function (data) {
                node.remove();
                editor.selection.setContent(data.content);
            });
        });
    }

    varienGlobalEvents.attachEventHandler('wysiwygEditorInitialized', initialize);
});
