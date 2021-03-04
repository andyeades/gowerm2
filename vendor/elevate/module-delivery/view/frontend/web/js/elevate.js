require(['jquery','tinyslider','evDelivery','Magento_Checkout/js/model/quote', 'Magento_Customer/js/model/customer'], function($,tinyslider,evDelivery, quote,customer) {

    var deliveryLaunched = false;
    var modal;
    var iscustomerloggedin = customer.isLoggedIn();
    var attachonce = false;





    function launchDelivery() {
        console.log('Launch Delivery Function');
        console.log('deliveryLaunched: '+deliveryLaunched);

        if (iscustomerloggedin) {
            // Customer Logged in
                console.log('Customer is logged in');
                // Elevate Delivery - Ensures it fires the delivery method switcher
                console.log('shippingaddressitems');
                console.log(jQuery('.shipping-address-item'));
                console.log(jQuery('.shipping-address-items .selected-item').length);
                if ( jQuery('.shipping-address-items .selected-item').length == 1) {
                    var countryId = jQuery('.shipping-address-items .selected-item .sel-countryid').text();
                    var postcode = jQuery('.shipping-address-items .selected-item .sel-postcode').text();

                    console.log("CountryId = " + countryId);
                    console.log("postcode = " + postcode);

                    if (countryId === "IE") {
                        console.log('country_id is IE');

                        jQuery('input[name=postcode]').val(postcode);
                        jQuery('[name="country_id"]').val(countryId);
                        console.log(jQuery('[name="country_id"]').val());

                    } else {

                        console.log('country_id is NOT IE');
                        jQuery('input[name=postcode]').val(postcode);
                    }
                }

                // Where someone has added new address
        jQuery('.action-save-address').on('click', function(){
            var countryId = jQuery('.shipping-address-items .selected-item .sel-countryid').text();
            var postcode = jQuery('.shipping-address-items .selected-item .sel-postcode').text();
            if (countryId === "IE") {
                console.log('country_id is IE');

                jQuery('input[name=postcode]').val(postcode);
                jQuery('[name="country_id"]').val(countryId);
                console.log(jQuery('[name="country_id"]').val());

            } else {

                console.log('country_id is NOT IE');
                jQuery('input[name=postcode]').val(postcode);
            }

            console.log("CountryId = " + countryId);
            console.log("postcode = " + postcode);
            evDelivery.getDeliveryOptions();
        });
                jQuery('.action-select-shipping-item').on('click', function() {
                    var countryId = jQuery('.shipping-address-items .selected-item .sel-countryid').text();
                    var postcode = jQuery('.shipping-address-items .selected-item .sel-postcode').text();
                    if (countryId === "IE") {
                        console.log('country_id is IE');

                        jQuery('input[name=postcode]').val(postcode);
                        jQuery('[name="country_id"]').val(countryId);
                        console.log(jQuery('[name="country_id"]').val());

                    } else {

                        console.log('country_id is NOT IE');
                        jQuery('input[name=postcode]').val(postcode);
                    }

                    console.log("CountryId = " + countryId);
                    console.log("postcode = " + postcode);
                    evDelivery.getDeliveryOptions();
                });


            // Where Postcode?!
        }
        if (!deliveryLaunched) {
            var postcodefieldval = jQuery('#shipping-new-address-form [name="postcode"]').val();
            if (postcodefieldval) {
                if (postcodefieldval.length > 2) {
                    evDelivery.getDeliveryOptions();
                }
                deliveryLaunched = true;
            }


        }
        if (attachonce !== true) {
            jQuery('#shipping-new-address-form input[name="postcode"]').on('change', throttle(function (event) {
                console.log('changed');
                //jQuery('select[name=country_id]').off();
                evDelivery.getDeliveryOptions();


            }, 1000));
            jQuery('#shipping-new-address-form select[name=country_id]').on('change', throttle(function (event) {
                console.log('country Changed');
                evDelivery.getDeliveryOptions();
            }, 1000));

            attachonce = true;
        }


    }

    function throttle(func, interval) {
        var lastCall = 0;
        return function() {
            var now = Date.now();
            if (lastCall + interval < now) {
                lastCall = now;
                return func.apply(this, arguments);
            }
        };
    }
    function debounce(func, interval) {
        var lastCall = -1;
        return function() {
            clearTimeout(lastCall);
            var args = arguments;
            var self = this;
            lastCall = setTimeout(function() {
                func.apply(self, args);
            }, interval);
        };
    }
// Get item attributes according to label description
    function getAttribute(item, attributeType){
        var attributeValue = '';
        item.options.forEach(function (option) {
            if (option['label'] == attributeType) {
                attributeValue = option['value'];
            }
        });
        return attributeValue;
    }

// Returns email of logged in customer or guest
    function getCustomerEmail() {
        if (isCustomerLoggedIn) {
            return customerData.email;
        } else {
            return jQuery('#customer-email').val();
        }
    }

// Get container
    const getContainerElement = () =>
        document.getElementById(
            'radio_deliveryoption'
        );

    MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
    var obs = new MutationObserver(function(mutations, observer) {
        if (getContainerElement() && !deliveryLaunched) {
            launchDelivery();
            console.log("this del launched: " + deliveryLaunched);
        }

        /*
            if (getContainerElement() && typeof modal != 'undefined' && jQuery('#toshi-app').length === 0){
                jQuery('#label_carrier_toshi_toshi').append('<div id="toshi-app"></div>');
                modal.mount(document.getElementById('toshi-app'));
            }

         */
    });

    const getContainerElementPostcode = () =>
        document.getElementsByName(
            'postcode'
        );





    obs.observe(document.body, {
        attributes: true,
        childList: true,
        characterData: false,
        subtree: true
    });
});
