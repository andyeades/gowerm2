/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    config: {
        mixins: {
            'Firebear_ConfigurableProducts/js/configurable': {
                'Firebear_ConfigurableProducts/js/mixin/configurable': true
            }, 'Magento_Catalog/js/price-utils':
                {
                    'Firebear_ConfigurableProducts/js/mixin/price-utils': true
                }
        }
    },
    map: {
        '*': {
            configurable : 'Firebear_ConfigurableProducts/js/configurable',
            jqueryHistory: 'Firebear_ConfigurableProducts/js/jquery.history',
            productSummary    : 'Firebear_ConfigurableProducts/js/product-summary',
            priceBundle       : 'Firebear_ConfigurableProducts/js/price-bundle',
            configurableBundle: 'Firebear_ConfigurableProducts/js/configurable_bundle'
        }
    }
};
