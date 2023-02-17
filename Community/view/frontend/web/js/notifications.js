define([
    'uiComponent',
    'ko',
    'jquery',
    'mage/url',
    'DaoNguyen_Community/js/message'
], function (Component, ko, $, url) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();
            this.msgData = [];
            this.msgUnReadData = 0;
        },

        initPlugin: function () {
            let self = this;
            $.ajax({
                method: 'GET',
                url: url.build('community/member/getNotifications')
            }).done(function (data) {
                if (data.top_notifications) {
                    data.top_notifications.forEach(function (element) {
                        self.msgData.push({
                            text: element.message,
                            id: element.id,
                            readStatus: element.status,
                            href: element.href
                        });
                    });
                }
                if (data.msgUnReadData) {
                    self.msgUnReadData = data.msgUnReadData;
                }
                MessagePlugin.init({
                    elem: "#message",
                    msgData: self.msgData,
                    width: 350,
                    msgUnReadData: self.msgUnReadData,
                    msgClick: function(obj) {
                        $.ajax({
                            method: 'POST',
                            url: url.build('community/member/readNotification'),
                            data: {
                                id: $(obj).find("p").data('id')
                            }
                        }).done(function (data) {
                            if (data.error) {
                                alert(data.message);
                            } else {
                                window.location.replace($(obj).find("p").data('href'));
                            }
                        });
                    },
                    allRead: function(obj) {
                        $.ajax({
                            method: 'POST',
                            url: url.build('community/member/readNotification')
                        }).done(function (data) {
                            if (data.error) {
                                alert(data.message);
                            }
                            location.reload();
                        });
                    },
                    getNodeHtml: function(obj, node) {
                        if (obj.readStatus == 1) {
                            node.isRead = true;
                        } else {
                            node.isRead = false;
                        }
                        var html = `<p data-href='${obj.href}' data-id='${obj.id}'>${obj.text}</p>`;

                        node.html = html;

                        return node;
                    }
                });
            });
        }
    });
});
