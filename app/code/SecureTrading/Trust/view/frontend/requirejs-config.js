var config = {
    map: {
        '*': {
            'stUK': 'SecureTrading_Trust/js/st/stUK',
            'stUS': 'SecureTrading_Trust/js/st/stUS'
        }
    },
    paths: {
         stUK: 'SecureTrading_Trust/js/st/stUK',
         stUS: 'SecureTrading_Trust/js/st/stUS',
    },
    config: {
        mixins: {
            'Magento_Checkout/js/view/shipping-information': {
                'SecureTrading_Trust/js/view/shipping-information-mixin': true
            },
            'Magento_Checkout/js/view/progress-bar': {
                'SecureTrading_Trust/js/view/progress-bar-mixin': true
            }
        }
    }
};