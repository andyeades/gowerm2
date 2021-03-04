define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-rates-validation-rules',
        '../model/shipping-rates-validator/elevate',
        '../model/shipping-rates-validation-rules/elevate'
    ],
    function (
        Component,
        defaultShippingRatesValidator,
        defaultShippingRatesValidationRules,
        shippingRatesValidator,
        shippingRatesValidationRules
    ) {
        'use strict';
        defaultShippingRatesValidator.registerValidator('elevate', shippingRatesValidator);
        defaultShippingRatesValidationRules.registerRules('elevate', shippingRatesValidationRules);
        return Component;
    }
);