define([
    'jquery',
    'jquery-ui-modules/widget'
], function ($) {
    'use strict';

    $.widget('mage.postListToolbarForm', {
        options: {
            directionControl: '[data-role="direction-switcher"]',
            direction: 'post_list_dir',
            directionDefault: 'asc',
            orderControl: '[data-role="sorter"]',
            oder: 'post_list_order',
            orderDefault: 'most_recent',
            url: ''
        },

        _create: function () {
            $(this.options.orderControl, this.element).on('change', {
                paramName: this.options.order,
                'default': this.options.orderDefault
            }, $.proxy(this.processSelect, this));
            $(this.options.directionControl, this.element).on('click', {
                paramName: this.options.direction,
                'default': this.options.directionDefault
            }, $.proxy(this.processLink, this));
        },

        processSelect: function (event) {
            this.changeUrl(
                event.data.paramName,
                event.currentTarget.options[event.currentTarget.selectedIndex].value,
                event.data.default
            );
        },

        processLink: function (event) {
            event.preventDefault();
            this.changeUrl(
                event.data.paramName,
                $(event.currentTarget).data('value'),
                event.data.default
            );
        },

        changeUrl: function (paramName, paramValue, defaultValue) {
            let paramData = this.getUrlParams(),
                baseUrl = this.options.url.split('?')[0];
            paramData[paramName] = paramValue;
            if (paramValue === defaultValue) {
                delete paramData[paramName];
            }
            paramData = $.param(paramData);
            location.href = baseUrl + (paramData.length ? '?' + paramData : '');
        },

        getUrlParams: function () {
            var decode = window.decodeURIComponent,
                urlPaths = this.options.url.split('?'),
                urlParams = urlPaths[1] ? urlPaths[1].split('&') : [],
                params = {},
                parameters, i;

            for (i = 0; i < urlParams.length; i++) {
                parameters = urlParams[i].split('=');
                params[decode(parameters[0])] = parameters[1] !== undefined ?
                    decode(parameters[1].replace(/\+/g, '%20')) :
                    '';
            }

            return params;
        },
    });

    return $.mage.postListToolbarForm;
});
