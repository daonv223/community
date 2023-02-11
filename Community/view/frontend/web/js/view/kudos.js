define([
    'uiComponent',
    'ko',
    'jquery',
    'mage/url',
    'Magento_Customer/js/customer-data',
    'Magento_Ui/js/modal/alert'
], function (Component, ko, $, url, customerData) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();
            this.isReaction = ko.observable(false);
            this.member = customerData.get('member');
            this.currentStatusClass = ko.pureComputed(function () {
                return this.isReaction() ? 'kudos-button-filled' : 'kudos-button';
            }, this);
            if (this.member().liked_posts && this.member().liked_posts.includes(this.postId)) {
                this.isReaction(true);
            }
        },

        reaction: function () {
            let self = this;
            $.ajax({
                method: 'POST',
                url: url.build('community/member/reaction'),
                data: {
                    postId: self.postId
                }
            }).done(function (data) {
                if (data.error) {
                    alert(data.message);
                } else {
                    self.isReaction(!self.isReaction());
                }
            });
        }
    });
});
