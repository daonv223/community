define([
    'mage/adminhtml/events'
], function () {

    function initialize(editor) {
        editor.addCommand('getProductBlock', function (ui, v) {
            let sku = editor.selection.getContent();
            editor.selection.setContent(`<strong>{sku}</strong>`)
        });
    }

    varienGlobalEvents.attachEventHandler('wysiwygEditorInitialized', initialize);
});
