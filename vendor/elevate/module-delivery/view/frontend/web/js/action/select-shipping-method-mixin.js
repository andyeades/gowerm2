/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',

], function ($, wrapper, quote) {
    'use strict';
    return function (selectShippingMethodAction) {

        return wrapper.wrap(selectShippingMethodAction, function (originalAction, MessageContainer, quote) {
            return originalAction(); // it is returning the flow to original action
        });
    }
});
