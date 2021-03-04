var config = {
    deps: [
        'Magento_Theme/js/responsive',
        'Magento_Theme/js/theme'
    ]
};

if (typeof jQuery === 'function') {
    //jQuery already loaded, just use that
    define('jquery', function() { return jQuery; });
}