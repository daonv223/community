define([
    'uiComponent',
    'ko',
    'jquery',
    'mage/url',
    'Magento_Ui/js/modal/alert'
], function (Component, ko, $, url) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this.isReaction = ko.observable(false);
            let self = this;
            this._super();
            this.currentStatusClass = ko.pureComputed(function () {
                return this.isReaction() ? 'kudos-button-filled' : 'kudos-button';
            }, this);
            $.ajax({
                method: 'GET',
                url: url.build('community/member/likedPost'),
                data: {
                    postId: self.postId
                }
            }).done(function (data) {
                self.isReaction(data.isLiked);
            });
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
