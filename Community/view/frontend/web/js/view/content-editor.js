define([
    'jquery',
    'uiComponent',
    'mage/adminhtml/wysiwyg/tiny_mce/setup'
], function ($, Component) {
    'use strict';

    return Component.extend({
        initialize: function (config) {
            this._super();
            let contentEditor = new wysiwygSetup('content_editor', config.editorConfig);
            contentEditor.setup("exact");
        }
    });
});
