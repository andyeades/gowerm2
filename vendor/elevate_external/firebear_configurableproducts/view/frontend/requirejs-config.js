/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    config: {
        mixins: {
            'Magento_ConfigurableProduct/js/configurable': {
                'Firebear_ConfigurableProducts/js/configurable-mixin': true
            },
            'Magento_Swatches/js/swatch-renderer': {
                'Firebear_ConfigurableProducts/js/swatch-renderer-mixin': true
            }
        }
    },
    map: {
        '*': {
            jqueryHistory: 'Firebear_ConfigurableProducts/js/jquery.history',

            productSummary    : 'Firebear_ConfigurableProducts/js/product-summary',
            priceBundle       : 'Firebear_ConfigurableProducts/js/price-bundle',
            configurableBundle: 'Firebear_ConfigurableProducts/js/configurable_bundle'
        }
    },
    shim: {
        jqueryHistory: ["jquery"]
    }
};
