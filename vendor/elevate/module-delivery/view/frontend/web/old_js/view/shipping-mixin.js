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
        'owlcarousel'
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
        $t,
        owlcarousel
    ) {
        var mixin = {
            deliveryinfo: '',
            deliveryOptionPrice: '0.00',
            deliveryOptionTitle1: 'DeliveryOption',
            deliveryOptionTitle2: 'DeliveryOption',
            selectShippingMethod: function (shippingMethod) {
                console.log('dingdong 65685');
                shippingMethod.method_title = '';
                shippingMethod.carrier_title = '';
                selectShippingMethodAction(shippingMethod);

                checkoutData.setSelectedShippingRate(shippingMethod['carrier_code'] + '_' + shippingMethod['method_code']);
                this.callAjax();
                return true;
            },
            init: function () {
                console.log('init function');
                // Deselect All Options
                jQuery('#checkout-shipping-method-load .radio').prop('checked', false);
                // Reset Delivery Options Value?
                this.updateDeliveryOptionPrice(this.deliveryOptionPrice);
                this.updateDeliveryOptionTitle(this.deliveryOptionTitle1,this.deliveryOptionTitle2);
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
            hideShowOurMethod: function (parent, data_e, event){

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
            callAjax: function (parent,data_e, event) {

                var postcode = jQuery('input [name=postcode]').val();



                jQuery('.loading').show();
                //console.log(parent);
                console.log('well... I clicked the thing!');
                var url = '/deliveryoptions/delivery/delivery';
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

                    },

                    /** @inheritdoc */
                    complete: function () {

                    }
                })
                    .done(function (response) {
                        console.log(response);
                        console.log("PARENT");
                        console.log(parent);
                        this.deliveryinfo = response.methods_available;
                        quote['deliveryinfo'] = this.deliveryinfo;
                        jQuery('.display-this').show();
                        //console.log(response.mobile_day_options_1);
                        jQuery('#delivery-calendar').html(response.mobile_day_options_1);
                        jQuery('#delivery-date-other-options').html(response.mobile_day_options_2);
                        // set descriptions
                        jQuery('#delivery-description-store').html(response.delivery_descriptions_checkout);
                        //target.reloadMobileSlider();
                        jQuery('#mobile-month').html(response.first_month_to_display);
                        //console.log(this.myvar);
                        parent.reloadMobileSlider(parent,response);
                        jQuery('.loading').hide();
                    })
                    .fail(function () {
                        console.log(error);
                    });
                // So Default Click happens
                return true;
            },
            reloadMobileSlider: function (parent, response) {
                jQuery('#deliveryMethodSelected').val('');
                jQuery('#mobile-dates-container').find('input[name="deliveryMobile"]').prop('checked', false);
                var mobileslider = jQuery('.owl-carousel').owlCarousel({
                    //loop:true,
                    margin: 10,
                    nav: true,
                    navElement: 'div',
                    navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
                    responsive: {
                        0: {
                            items: 7
                        },
                        600: {
                            items: 7
                        },
                        1000: {
                            items: 7
                        }
                    }
                });
                var mainfunctionlist = this;



                mobileslider.on('changed.owl.carousel', function (event) {
                    // Change Mobile Month Displayed dependent on number of
                    mainfunctionlist.changeMobileMonth();
                })
                jQuery('.mobile-date').click(function () {
                    jQuery('#deliveryMethodSelected').val('');
                    jQuery('#mobile-dates-container').find('input[name="deliveryMobile"]').prop('checked', false);
                    console.log('method deselected');
                    jQuery('#delivery-bottom-message-1 .delivery-desc').detach().appendTo(jQuery('#delivery-description-store'));
                    var previouslychecked_item = jQuery('input[name="deliveryMobile"]:checked');
                    jQuery(previouslychecked_item).prop('checked', false);
                    var dateval = jQuery(this).attr('data-date');
                    var methodid = jQuery(this).attr('method');
                    if (jQuery(this).hasClass('active')) {
                        jQuery('#delivery-bottom-message-1 .delivery-desc').hide();

                        // DO IT!
                        jQuery('.mobile-date.active').removeClass('active');
                        jQuery(this).removeClass('active');
                        jQuery('#delivery-date-selected-options').children().detach().appendTo('#delivery-date-other-options');
                        jQuery('#delivery-date-other-options .day-options-' + dateval).detach().appendTo('#delivery-date-selected-options');
                        jQuery('#delivery-date-selected-options').find('input').prop('disabled', false);

                    } else {
                        jQuery('.mobile-date.active').removeClass('active');
                        jQuery(this).addClass('active');
                        jQuery('#delivery-date-selected-options').children().detach().appendTo('#delivery-date-other-options');
                        jQuery('#delivery-date-other-options .day-options-' + dateval).appendTo('#delivery-date-selected-options');
                        jQuery('#delivery-date-selected-options').find('input').prop('disabled', false);


                    }
                });

                jQuery('.delivery-radio-selector').click(function () {
                    jQuery(this).find('input[name="deliveryMobile"]').prop('checked', true);
                    var dateval = jQuery(this).find('input[name="deliveryMobile"]').attr('value');
                    var methodid = jQuery(this).find('input[name="deliveryMobile"]').attr('method');

                    var shippingMethod = quote.shippingMethod();
                    var date = dateval.split("_",3);
                    // 0 - date value
                    // 1 - method
                    // 2 - id of method?
                    var date_to_check = new Date(date[0]);

                    // JS sunday = 0, Saturday = 6. We store as Monday = 1, Sunday = 7.

                    var day_val = date_to_check.getDay();


                    var date_output_string = date_to_check.getDate() + " "
                    if (day_val === 0) {
                        day_val = 7;
                    }


                    var method_data = response.methods_available[methodid];
                    var price = Math.round(method_data.delivery_fees[day_val].fee,2);


                    var partone = method_data.delivery_team_ability_text;
                    var parttwo = method_data.delivery_team_ability;
                    var partthree =  jQuery(this).find('input[name="deliveryMobile"]').attr('data-methodsummarytext');


                    // Change this to get the data from the db, not from input!
                    //var price = jQuery(this).find('input[name="deliveryMobile"]').attr('data-price');

                    jQuery('#deliveryMethodSelected').val(methodid);
                    console.log('method now: '+methodid);

                    parent.updateDeliveryOptionPrice(price);
                    //shippingMethod.carrier_code = 'deliveryoption';

                    parent.updateDeliveryCarrierTitle(shippingMethod,partone);
                    parent.updateDeliveryMethodTitle(shippingMethod,partone + " " + parttwo - + " " + partthree);
                    shippingMethod.amount = price;
                    //selectShippingMethod(shippingMethod);
                    selectShippingMethodAction(shippingMethod);

                    parent.updateDeliveryOptionTitle(partone,parttwo);
                    jQuery('#delivery-bottom-message-1 .delivery-desc').detach().appendTo(jQuery('#delivery-description-store'));
                    jQuery('#delivery-desc-checkout-'+methodid).detach().appendTo(jQuery('#delivery-bottom-message-1'));
                });

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
        return function (target) {
            return target.extend(mixin);
        };
    });