/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {

            var deliveryinfo = quote['deliveryinfo'];
            console.log(deliveryinfo);
            var shippingAddress = quote.shippingAddress();

            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }

            console.log(quote);
            var shippingMethod = quote.shippingMethod();

            if (shippingMethod.method_code == "deliveryoption") {
                var dateval = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').val();
                var summarytext = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').attr('data-methodsummarytext');
                shippingMethod.method_title = summarytext;

                var date = dateval.split("_",3);

                // 0 - date value
                // 1 - method
                // 2 - area?

                if (date.hasOwnProperty(0)) {
                    // Date Set
                    console.log('date set');
                    shippingAddress['extension_attributes']['delivery_date_selected'] = date[0] + " 00:00:00";
                } else {
                    // No Date Set
                    console.log("no date set");
                }

                if (date.hasOwnProperty(1)) {
                    // Date Set
                    console.log('date method');
                    shippingAddress['extension_attributes']['delivery_option_selected'] = date[1];
                } else {
                    // No Date Set
                    console.log("no date method set");
                }

                if (date.hasOwnProperty(2)) {
                    // Date Set
                    console.log('date area');
                    shippingAddress['extension_attributes']['delivery_area_selected'] = date[2];
                } else {
                    // No Date Set
                    console.log("no date area set");
                }

                shippingAddress['extension_attributes']['delivery_selected_summarytext'] = summarytext;


                //var methodid = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]').attr('method');


                //shippingAddress['extension_attributes']['delivery_option_selected'] = "A";
            } else {
                shippingAddress['extension_attributes']['delivery_date_selected'] = "0000-00-00 00:00:00";
                shippingAddress['extension_attributes']['delivery_option_selected'] = "";
                shippingAddress['extension_attributes']['delivery_area_selected'] = "";
                shippingAddress['extension_attributes']['delivery_selected_summarytext'] = "";
            }
            console.log(shippingAddress);
            // you can write here your code according to your requirement

            var elementthatwewant = jQuery('#checkout-shipping-method-load .radio:checked').val();

            var value = jQuery('#deliveryMethodSelected').val();
            //console.log(value);
            var whatschecked = jQuery('#mobile-dates-container').find('input[name="deliveryMobile"] :checked').val();

            var compareval = jQuery(whatschecked).val();

            if (elementthatwewant == 'deliveryoption_deliveryoption') {
                var whatschecked = jQuery('input[name="deliveryMobile"]:checked');
                if (whatschecked.length == 0 || whatschecked == false) {
                    // No Date Option Selected -

                    console.log('Value Null, Not going forward!');

                    var options = {
                        type: 'popup',
                        responsive: true,
                        innerScroll: true,
                        modalClass: 'checkout-error-modal',
                        title: '<i class="fa fa-exclamation-triangle"></i> Error',
                        buttons: [{
                            text: $.mage.__('Continue'),
                            class: '',
                            click: function () {
                                this.closeModal();
                            }
                        }]
                    };
                    jQuery('#checkout-modal .content').html('<p>No date option selected. Please select an option and try again.</p>');
                    var popup = modal(options, jQuery('#checkout-modal'));
                    jQuery('#checkout-modal').modal('openModal');

                    jQuery('#modal-container').show();

                }
            }







            return originalAction(); // it is returning the flow to original action
        });
    };
});