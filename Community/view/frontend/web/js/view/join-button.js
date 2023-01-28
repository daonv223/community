define([
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function (Component, customerData) {
    'use strict';

    return Component.extend({
        defaults: {

        },

        initialize: function () {
            this._super();
            this.member = customerData.get('member');
        },

        isJoined: function () {
            if (this.member().joined_groups) {
                return this.member().joined_groups.includes(this.groupId);
            }
            return false;
        }
    });
});
