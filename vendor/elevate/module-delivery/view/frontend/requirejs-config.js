var config = {
    config: {
        mixins: {
           // 'Magento_Checkout/js/action/select-shipping-method': {
            //    'Elevate_Delivery/js/action/select-shipping-method-mixin': true
            //},
           // 'Magento_Checkout/js/action/set-shipping-information': {
           //     'Elevate_Delivery/js/action/set-shipping-information-mixin': true
           // }
        }, // this is how js mixin is defined
        paths: {}
    },
    map: {
        '*': {
            elevateDelivery: 'Elevate_Delivery/js/elevate',
            evDelivery: 'Elevate_Delivery/js/delivery',
        }
    },
    deps: [
        "Elevate_Delivery/js/main"
    ],
    paths: {
        'Magento_Checkout/js/view/summary/shipping': 'Elevate_Delivery/js/view/summary/shipping',
        'Magento_Checkout/js/view/shipping': 'Elevate_Delivery/js/view/shipping',
        'Magento_Checkout/template/shipping': 'Elevate_Delivery/template/shipping',
        tinyslider: "Elevate_Delivery/js/tiny-slider"
    },
    shim: {
        tinyslider: {
            deps: ['jquery']
        }
    }
};
