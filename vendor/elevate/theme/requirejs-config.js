var config = {
    paths: {
        modal: 'js/vendor/bootstrap/modal',
        util: 'js/vendor/bootstrap/util',
        collapse: 'js/vendor/bootstrap/collapse',
        swiper: 'js/posturite/swiper.min.js'
    },
    shim: {
        util: {
            deps: ['jquery']
        },
        modal: {
            deps: ['jquery','util']
        },
        collapse: {
            deps: ['jquery','util']
        },
        swiper: {
            deps: ['jquery']
        }
    },

    map: {
        '*': {
            'Magento_Ui/js/lib/knockout/bindings/i18n': 'js/lib/knockout/bindings/i18n'
        },
    },
};

if (typeof jQuery === 'function') {
    //jQuery already loaded, just use that
    define('jquery', function() { return jQuery; });
}
