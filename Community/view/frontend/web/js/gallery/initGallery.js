define([
    'jquery',
    'DaoNguyen_Community/js/gallery/lib/jquery.timers-1.2',
    'DaoNguyen_Community/js/gallery/lib/jquery.easing.1.3',
    'DaoNguyen_Community/js/gallery/lib/jquery.galleryview-3.0-dev'
], function ($) {
    'use strict';

    return function (config, element) {
        $(element).galleryView();
    }
});
