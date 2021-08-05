/*jshint browser:true jquery:true*/
/*global alert*/
define([
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

                        var detailed_delivery_info_dates = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').attr('methodrangedates');
                        var detailed_delivery_teamnumber = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').attr('data-deliveryteamnumber');

                        // What if it isn't set?



                        if (shippingMethod.method_code == "deliveryoption") {
                            var postcode = jQuery('input[name=postcode]').val();
                            var country = jQuery('[name="country_id"]').val();
                            var price = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').attr('data-price');
                            var date = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').val();


                            var methodid = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').attr('method');
                            console.log(methodid);
                            var tS = new Date().getTime();

                            console.log('Validating 1');
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
                                    $('#elevateLightboxModal .bs-modal-title').html('Error');
                                    $('#elevateLightboxModal .bs-modal-body').html(error_message);
                                    $('#elevateLightboxModal').bsmodal('show');
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
                                    var detailed_delivery_info_dates = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').attr('methodrangedates');
                                    var detailed_delivery_teamnumber = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').attr('data-deliveryteamnumber');

                                    var detailed_delivery_start_time = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').attr('data-delivery-start-time');
                                    var detailed_delivery_end_time = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').attr('data-delivery-end-time');
                                    var detailed_delivery_before_time = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]:checked').attr('data-delivery-before-time');

                                    quote['extension_attributes']['detailed_delivery_info_dates'] = detailed_delivery_info_dates;
                                    quote['extension_attributes']['detailed_delivery_info'] = '';
                                    quote['extension_attributes']['detailed_delivery_teamnumber'] = detailed_delivery_teamnumber;
                                    quote['extension_attributes']['detailed_delivery_start_time'] = detailed_delivery_start_time;
                                    quote['extension_attributes']['detailed_delivery_end_time'] = detailed_delivery_end_time;
                                    quote['extension_attributes']['detailed_delivery_before_time'] = detailed_delivery_before_time;
                                    quote['extension_attributes']['delivery_selected_summarytext'] = summarytext;
                                    console.log('hell yes');

                                    shippingAddress['extension_attributes']['detailed_delivery_info_dates'] = detailed_delivery_info_dates;
                                    shippingAddress['extension_attributes']['detailed_delivery_info'] = '';
                                    shippingAddress['extension_attributes']['detailed_delivery_teamnumber'] = detailed_delivery_teamnumber;
                                    shippingAddress['extension_attributes']['detailed_delivery_start_time'] = detailed_delivery_start_time;
                                    shippingAddress['extension_attributes']['detailed_delivery_end_time'] = detailed_delivery_end_time;
                                    shippingAddress['extension_attributes']['detailed_delivery_before_time'] = detailed_delivery_before_time;
                                    setShippingInformationAction().done(
                                        function () {
                                            stepNavigator.next();
                                        }
                                    );

                                    //var methodid = jQuery('.delivery-radio-selector').find('input[name="deliveryMobile"]').attr('method');


                                    //shippingAddress['extension_attributes']['delivery_option_selected'] = "A";

                                }




                            }).fail(function( jqXHR, textStatus ) {
                                alert( "Request failed: " + textStatus );


                            });




                        } else {
                            shippingAddress['extension_attributes']['delivery_date_selected'] = "0000-00-00 00:00:00";
                            shippingAddress['extension_attributes']['delivery_option_selected'] = "";
                            shippingAddress['extension_attributes']['delivery_area_selected'] = "";
                            shippingAddress['extension_attributes']['delivery_selected_summarytext'] = "";
                            shippingAddress['extension_attributes']['detailed_delivery_info_dates'] = "";
                            shippingAddress['extension_attributes']['detailed_delivery_info'] = "";
                            shippingAddress['extension_attributes']['detailed_delivery_teamnumber'] = "";
                            shippingAddress['extension_attributes']['detailed_delivery_start_time'] = "";
                            shippingAddress['extension_attributes']['detailed_delivery_end_time'] = "";
                            shippingAddress['extension_attributes']['detailed_delivery_before_time'] = "";
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
                            $('#elevateLightboxModal .bs-modal-title').html('Error');
                            $('#elevateLightboxModal .bs-modal-body').html(error_message);
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
            }
        };
        return function (target) { // target == Result that Magento_Ui/.../default returns.
            return target.extend(mixin); // new result that all other modules receive
        };
    });
