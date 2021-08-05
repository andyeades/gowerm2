var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-shipping-information': {
                'Elevate_Delivery/js/action/set-shipping-information-mixin': true
            },
            'Magento_Checkout/js/view/shipping': {
                'Elevate_Delivery/js/view/shipping-mixin': true
            },

        } // this is how js mixin is defined
    },
    paths: {
        bootstrapjs: "Elevate_Delivery/js/bootstrap",
        owlcarousel: "Elevate_Delivery/js/owlcarousel"
    },
    shim: {
        bootstrapjs: {
            deps: ['jquery']
        },
        owlcarousel: {
            deps: ['jquery']
        }
    }
};
// I am extending "Magento_Checkout/js/action/set-shipping-information" this js with our custom js "Company_Module/js/action/set-shipping-information-mixin".