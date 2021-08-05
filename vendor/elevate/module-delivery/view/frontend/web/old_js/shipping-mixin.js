/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
        'jquery',
        'underscore',
        'Magento_Ui/js/form/form',
        'ko',
        'Magento_Customer/js/model/customer',
        'Magento_Customer/js/model/address-list',
        'Magento_Checkout/js/model/address-converter',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/action/create-shipping-address',
        'Magento_Checkout/js/action/select-shipping-address',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-address/form-popup-state',
        'Magento_Checkout/js/model/shipping-service',
        'Magento_Checkout/js/action/select-shipping-method',
        'Magento_Checkout/js/model/shipping-rate-registry',
        'Magento_Checkout/js/action/set-shipping-information',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Ui/js/modal/modal',
        'Magento_Checkout/js/model/checkout-data-resolver',
        'Magento_Checkout/js/checkout-data',
        'uiRegistry',
        'mage/translate',
        'Magento_Checkout/js/model/shipping-rate-service'
    ], function (
        $,
        _,
        Component,
        ko,
        customer,
        addressList,
        addressConverter,
        quote,
        createShippingAddress,
        selectShippingAddress,
        shippingRatesValidator,
        formPopUpState,
        shippingService,
        selectShippingMethodAction,
        rateRegistry,
        setShippingInformationAction,
        stepNavigator,
        modal,
        checkoutDataResolver,
        checkoutData,
        registry,
        $t
    ) {
        'use strict';

        var mixin = {
            deliveryinfo: '',
            deliveryOptionPrice: '0.00',
            deliveryOptionTitle1: 'Test1 - DeliveryOption',

            deliveryOptionTitle2: 'Test2 - DeliveryOption',
            selectShippingMethod: function (shippingMethod) {
                console.log('selectShippingmethod fired');
                // if Delivery Method Was deliveryoption, but now isn't we need to clear the title that was set on frontend
                if (shippingMethod.carrier_code != 'deliveryoption') {
                    jQuery('#label_method_deliveryoption_deliveryoption').text("Courier");
                    jQuery('#label_carrier_deliveryoption_deliveryoption').text("Courier");
                } else {

                    console.log('hell yes');
                    console.log('shipping method action wrapper?')
                    var deliveryinfo = quote['deliveryinfo'];
                    console.log(deliveryinfo);
                    var shippingAddress = quote.shippingAddress();

                    if (shippingAddress['extension_attributes'] === undefined) {
                        shippingAddress['extension_attributes'] = {};
                    }

                    console.log(quote);
                    var shippingMethod = quote.shippingMethod();

                    if (shippingMethod.hasOwnProperty('method_code')) {
                        if (shippingMethod.method_code == "deliveryoption") {
                            var dateval = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').val();
                            var summarytext = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').attr('data-methodsummarytext');
                            shippingMethod.method_title = summarytext;

                            if (typeof dateval != "undefined") {
                                var date = dateval.split("_", 3);

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
                            }
                            if (typeof summarytext != 'undefined') {
                                shippingAddress['extension_attributes']['delivery_selected_summarytext'] = summarytext;
                            }



                            console.log(shippingAddress);
                            //var methodid = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]').attr('method');
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
                        }
                    }

                }

                console.log(shippingMethod);

                // Validate Price/etc?
                console.log("validate me");
                var validationcheck = mixin.validateMethod();


                selectShippingMethodAction(shippingMethod);
                console.log(shippingMethod);

                //mixin.updateDeliveryCarrierTitle(shippingMethod,'Courier');
                //console.log(shippingMethod);
                checkoutData.setSelectedShippingRate(shippingMethod['carrier_code'] + '_' + shippingMethod['method_code']);
                console.log('delivery object:')
                console.log(delivery);
                var something = delivery.getDeliveryOptions();

                console.log('something');
                console.log(something);
                //quote.shippingmethod(shippingMethod);
                return true;
            },
            init: function () {
                console.log('shipping mixin init function');
                // Deselect All Options
                jQuery('#checkout-shipping-method-load .radio').prop('checked', false);
                // Reset Delivery Options Value?
                this.updateDeliveryOptionPrice(this.deliveryOptionPrice);
                this.updateDeliveryOptionTitle(this.deliveryOptionTitle1,this.deliveryOptionTitle2);
            },
            setShippingInformation: function () {


                if (this.validateShippingInformation()) {
                    quote.billingAddress(null);
                    checkoutDataResolver.resolveBillingAddress();

                    console.log('shipping method action wrapper?')
                    var deliveryinfo = quote['deliveryinfo'];
                    console.log(deliveryinfo);
                    var shippingAddress = quote.shippingAddress();

                    if (shippingAddress['extension_attributes'] === undefined) {
                        shippingAddress['extension_attributes'] = {};
                    }
                    var shippingMethod = quote.shippingMethod();
                    if (shippingMethod.hasOwnProperty('method_code')) {

                        if (shippingMethod.method_code == "deliveryoption") {
                            var postcode = jQuery('input[name=postcode]').val();
                            var country = jQuery('[name="country_id"]').val();
                            var price = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').attr('data-price');
                            var date = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').val();


                            var methodid = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').attr('method');
                            console.log(methodid);
                            var tS = new Date().getTime();

                            console.log('Validating');
                            var url = '/deliveryoptions/delivery/getprice/postcode/' + postcode + '/country/' + country + '/method/' + methodid + '/date/' + date + '/price/' + price + '/&ts=' + tS;
                            //console.log(this);
                            //console.log(quote);
                            // url = url.replace("https://","http://"); // New Code
                            var data = jQuery('#co-shipping-form').serializeArray();
                            //var myvar = this;

                            var proceed = false;
                            $.ajax({
                                url: url,
                                dataType: 'json',
                                type: 'post',
                                data: data,
                                //context: this,

                                /** @inheritdoc */
                                beforeSend: function () {
                                    //ajaxOverlayPostcodeShow();
                                },

                                /** @inheritdoc */
                                complete: function () {

                                }
                            }).done(function (response) {
                                console.log('done ajax validate');
                                console.log(response);

                                if (response.hasOwnProperty('error_message')) {
                                    // Nope Error

                                    var error_message = response.error_message;
                                    $('#elevateLightboxModal .modal-title').html('Error');
                                    $('#elevateLightboxModal .modal-body').html(error_message);
                                    $('#elevateLightboxModal').bsmodal('show');
                                    return false;
                                } else {
                                    // Ok All Good
                                    console.log("dateval");
                                    console.log(date);
                                    var summarytext = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').attr('data-methodsummarytext');
                                    console.log("summary text: " + summarytext);

                                    shippingMethod.method_title = summarytext;
                                    console.log(shippingMethod);
                                    checkoutData.setSelectedShippingRate(shippingMethod['carrier_code'] + '_' + shippingMethod['method_code']);

                                    // Add Date from Input To Quote Object
                                    // TODO - Should htis be only if confirmed by above

                                    if (typeof date != "undefined") {
                                        var datenew = date.split("_", 3);

                                        // 0 - date value
                                        // 1 - method
                                        // 2 - area?

                                        if (datenew.hasOwnProperty(0)) {
                                            // Date Set
                                            console.log('date set');
                                            shippingAddress['extension_attributes']['delivery_date_selected'] = datenew[0] + " 00:00:00";
                                        } else {
                                            // No Date Set
                                            console.log("no date set");
                                        }

                                        if (datenew.hasOwnProperty(1)) {
                                            // datenew Set
                                            console.log('datenew method');
                                            shippingAddress['extension_attributes']['delivery_option_selected'] = datenew[1];
                                        } else {
                                            // No datenew Set
                                            console.log("no datenew method set");
                                        }

                                        if (datenew.hasOwnProperty(2)) {
                                            // datenew Set
                                            console.log('datenew area');
                                            shippingAddress['extension_attributes']['delivery_area_selected'] = datenew[2];
                                        } else {
                                            // No datenew Set
                                            console.log("no datenew area set");
                                        }
                                    }
                                    shippingAddress['extension_attributes']['delivery_selected_summarytext'] = summarytext;


                                    //var methodid = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]').attr('method');


                                    //shippingAddress['extension_attributes']['delivery_option_selected'] = "A";
                                    // TODO - THIS NEEDS MOVING!
                                    console.log('how is it getting here?!');
                                    setShippingInformationAction().done(
                                        function () {
                                            stepNavigator.next();
                                        }
                                    );
                                }




                            }).fail(function( jqXHR, textStatus ) {
                                alert( "Request failed: " + textStatus );


                            });




                        } else {
                            shippingAddress['extension_attributes']['delivery_date_selected'] = "0000-00-00 00:00:00";
                            shippingAddress['extension_attributes']['delivery_option_selected'] = "";
                            shippingAddress['extension_attributes']['delivery_area_selected'] = "";
                            shippingAddress['extension_attributes']['delivery_selected_summarytext'] = "";
                        }
                    }
                    console.log("shipping Address");
                    console.log(shippingAddress);


                }







            },
            validateMethod: function () {
                var postcode = jQuery('input[name=postcode]').val();
                var country = jQuery('[name="country_id"]').val();

                var tS = new Date().getTime();

                //console.log(parent);
                console.log('well... I clicked the thing! -validating');
                var url = '/deliveryoptions/delivery/getprice/postcode/' + postcode + '/country/' + country + '/&ts=' + tS;
                //console.log(this);
                //console.log(quote);
                // url = url.replace("https://","http://"); // New Code
                var data = jQuery('#co-shipping-form').serializeArray();
                //var myvar = this;

                $.ajax({
                    url: url,
                    dataType: 'json',
                    type: 'post',
                    data: data,
                    //context: this,

                    /** @inheritdoc */
                    beforeSend: function () {
                        //ajaxOverlayPostcodeShow();
                    },

                    /** @inheritdoc */
                    complete: function () {

                    }
                })

                    .done(function (response) {
                        console.log('done ajax validate');
                        if (response.hasOwnProperty("error_message")) {
                            $('#elevateLightboxModal .modal-title').html('Error');
                            $('#elevateLightboxModal .modal-body').html(error_message);
                            $('#elevateLightboxModal').bsmodal('show');
                         console.log(response.error_message);
                         return false;
                        }
                        return true;
                        //his.deliveryinfo = response.methods_available;
                        //quote['deliveryinfo'] = this.deliveryinfo;
                        //jQuery('.display-this').show();
                    })
                    .fail(function( jqXHR, textStatus ) {
                        alert( "Request failed: " + textStatus );
                        return false;
                    });
                // So Default Click happens

            },
            updateDeliveryOptionPrice(value) {

                if (value == "Free") {
                    jQuery('.price_deliveryoption').text('£'+ value);

                } else {
                    jQuery('.price_deliveryoption').text('£'+ value);

                }
            },
            updateDeliveryOptionTitle(partone,parttwo) {
                //console.log('updatedeliveryoptiontitle');
                jQuery('#label_method_deliveryoption_deliveryoption').text(partone);
                jQuery('#label_carrier_deliveryoption_deliveryoption').text(partone + parttwo);
            },
            updateDeliveryCarrierTitle(shippingMethod,newtitle) {
                shippingMethod.carrier_title = newtitle;
            },
            updateDeliveryMethodTitle(shippingMethod,newtitle) {
                shippingMethod.method_title = newtitle;
            },
            hideShowOurMethod: function (shippingMethod){

                if (data_e.carrier_code != 'deliveryoption') {
                    jQuery('.display-this').hide();
                    // Reset Delivery Options Value?
                    this.updateDeliveryOptionPrice(this.deliveryOptionPrice);
                    this.updateDeliveryOptionTitle(this.deliveryOptionTitle1,this.deliveryOptionTitle2);
                    jQuery('#mobile-dates-container').find('input[name="deliveryMobile"]').prop('checked', false);
                }
                // Click Even Prop
                return true;
            },
            changeMobileMonth: function () {
                var mainfunctionlist = this;
                // For Changing Month Based on Dates Displayed
                var months = {}

                // Page Items Active on screen
                jQuery('.owl-item.active').each(function (index, element) {
                    var data_month = jQuery(this).find('.mobile-date').attr('data-month');
                    //console.log(data_month);

                    if (months.hasOwnProperty(data_month)) {
                        var count = parseInt(months[data_month]);
                        count++;
                        months[data_month] = count;
                    } else {
                        months[data_month] = 1;
                    }
                });
                var new_array = mainfunctionlist.sortObject(months);
                //console.log(new_array);

                // Month with highest number of dates visible will be 0 in array.
                var month_to_display = new_array[0].key;

                jQuery('.mobile-month').html(month_to_display);
            },
            sortObject: function (obj) {
                var arr = [];
                for (var prop in obj) {
                    if (obj.hasOwnProperty(prop)) {
                        arr.push({
                            'key': prop,
                            'value': obj[prop]
                        });
                    }
                }
                arr.sort(function (a, b) {
                    return b.value - a.value;
                });
                //arr.sort(function(a, b) { a.value.toLowerCase().localeCompare(b.value.toLowerCase()); }); //use this to sort as strings
                return arr; // returns array
            },
            ajaxloadingshow: function () {
                jQuery("#delivery-ajax-loader").html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>');
            },
            ajaxloadinghide: function () {
                jQuery("#delivery-ajax-loader").html('');
            },
            checkoutIncorrectPostcode: function () {
                jQuery('#delivery-enter-postcode').html("Please check the entered postcode. If your postcode is valid, unfortunately we don't deliver to this postcode.");
                jQuery('.deliveryoptions').hide();
                jQuery('.delivery_desc').hide();
                jQuery('#delivery-outer').addClass('delivery-outer-incorrect');
                if (jQuery('#delivery-mask-overlay').is(":hidden")) {
                    jQuery('#delivery-mask-overlay').show();

                }
                if (jQuery('#delivery-enter-postcode').is(":hidden")) {
                    jQuery("#delivery-enter-postcode").show();
                }
            },
            showCartExtendedMessage: function () {
                jQuery('#delivery-options-message-extended').html('<span>One or more of the items in your cart has an extended delivery date. The first date shown above is the first date your order will be available for delivery.</span>');
            },
            hideCartExtendedMessage: function () {
                jQuery('#delivery-options-message-extended').html('');
            },
            addDeliveryDescriptionsCheckout: function (data) {
                /*
                var delivery_descriptions = data.delivery_descriptions_checkout;
                var delivery_description_output = '';
                for (var key in delivery_descriptions) {
                    if (delivery_descriptions.hasOwnProperty(key)) {
                        delivery_description_output += '<div id="delivery_desc_checkout' + key + '" class="delivery_desc">' + delivery_descriptions[key] + '</div>';
                    }
                }

                 */
                jQuery("#delivery-bottom-message-1").html(delivery_description_output);
                jQuery('#delivery-bottom-message-1 .delivery_desc').hide();
            },
            hideDeliveryBottomMessage: function () {
                if (jQuery('#delivery-bottom-message-1').is(':visible')) {
                    jQuery("#delivery-bottom-message-1").hide();
                }
            },
            showDeliveryBottomMessage: function () {
                if (jQuery('#delivery-bottom-message-1').is(':hidden')) {
                    jQuery("#delivery-bottom-message-1").show();
                }
            },
            showDeliveryDescriptionCheckout: function (value) {
                jQuery("#delivery-bottom-message-1").hide();
                jQuery("#delivery-bottom-message-1 .delivery_desc").hide();
                var delivery_desc_for_checkoutreview = jQuery("#delivery_desc_checkout" + value).text();
                jQuery('#checkout-shipping-description').text(delivery_desc_for_checkoutreview);
                jQuery("#delivery_desc_checkout" + value).show();
                this.showDeliveryBottomMessage();
            }
        };
        return function (target) { // target == Result that Magento_Ui/.../default returns.
            return target.extend(mixin); // new result that all other modules receive
        };
    });