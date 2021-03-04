define(
    ['jquery','Magento_Checkout/js/model/quote','Magento_Customer/js/model/customer'],
    function($, quote, customer)
    {
        "use strict";

        /**
         * Return the Component
         */
        var inited = 0;
        var hasFirstRun = false;
        var self = this;
        var mobileslider;
        var iscustomerloggedin = customer.isLoggedIn();

        function getDeliveryOptions() {
            var country_element = jQuery('select[name=country_id]');

            var country_id = jQuery(country_element).val();
            console.log('internal options ' + country_id);

            var postcode_element = jQuery('input[name=postcode]');
            var postcode = jQuery(postcode_element).val();
            console.log('internal options ' + postcode);

            // Ireland doesn't require Postcode, so we need to be clever!

            if ((postcode && country_id != "UK") || (country_id === "IE")) {
                getDeliveryOptionsActual(country_id, postcode);
            }

            // So Default Click happens
            return true;
        }

        function getDeliveryOptionsActual(country, postcode) {
            var tS = new Date().getTime();

            console.log('getDeliveryOptionsActual Fired');
            var url = '/deliveryoptions/delivery/delivery/postcode/' + postcode + '/country/' + country + '/&ts=' + tS;

            var data = jQuery('#co-shipping-form').serializeArray();
            //var myvar = this;

            if (jQuery('#shipping-new-address-form .street').hasClass('crafty_address_field_hidden') && !iscustomerloggedin) {
                // unless their logged in!
                // Street is hidden, so don't fire until they select the address from dropdown?
            } else {
                $.ajax({
                    url: url,
                    dataType: 'json',
                    type: 'post',
                    data: data,
                    //context: this,

                    /** @inheritdoc */
                    beforeSend: function () {
                        ajaxOverlayPostcodeShow();

                    },

                    /** @inheritdoc */
                    complete: function () {

                    }
                })

                    .done(function (response) {
                        console.log('done');
                        console.log(response);

                        if (response.hasOwnProperty('methods_available')) {
                            // methods available
                            this.deliveryinfo = response.methods_available;

                            quote['deliveryinfo'] = this.deliveryinfo;
                            //jQuery('.display-this').show();


                            jQuery('#delivery-calendar').html(response.mobile_day_options_1);

                            jQuery('#delivery-date-selected-options').html('');
                            jQuery('#delivery-date-other-options').html(response.mobile_day_options_2);
                            // set descriptions
                            jQuery('#delivery-description-store-inner').html(response.delivery_descriptions_checkout)

                            jQuery('#mobile-month').html(response.first_month_to_display);

                            reloadMobileSlider();

                            ajaxOverlayPostcodeHide();
                        } else {
                            // No Methods
                            var error_message = response.error_message;
                            console.log('no methods! do something');
                            $('#elevateLightboxModal .bs-modal-title').html('Error');
                            $('#elevateLightboxModal .bs-modal-body').html(error_message);
                            $('#elevateLightboxModal').bsmodal('show');
                        }

                    })
                    .fail(function( jqXHR, textStatus ) {
                        alert( "Request failed: " + textStatus );
                    });
            }


        }

        function reloadMobileSlider() {
            console.log('Reload Mobile Slider');
            mobileslider = tns({
                container: '.delivery-slider',
                loop: false,
                gutter: 10,
                mouseDrag: true,
                items: 3,
                controlsContainer: "#deliveryslider-nav",
                responsive:{
                    768:{
                        items:5
                    },
                    992:{
                        items:7
                    },
                    1270:{
                        items:7
                    }
                }
            });

            if(!hasInit()) {
                jQuery('#delivery-mobile-outer').removeClass('pre-loadingstyle');
            }

            mobileslider.events.on('indexChanged', function(event) {
                console.log('need to change this to not break it when comeing back from next step!')
                // Change Mobile Month Displayed dependent on number of
                console.log('carousel changed?');


                changeMobileMonth();
            })
            jQuery('.mobile-date').click(function(){
                jQuery('#deliveryMethodSelected').val('');

                jQuery('.delivery-desc').hide();
                var previouslychecked_item = jQuery('input[name="deliveryMobile"]:checked');
                jQuery(previouslychecked_item).prop('checked',false);
                var dateval = jQuery(this).attr('data-date');
                var methodid = jQuery(this).attr('method');
                if (jQuery(this).hasClass('active')) {
                    jQuery('#delivery-description-store .del-desc-store-notice').hide();
                    jQuery('#delivery-description-store-inner .delivery_desc').hide();
                    // Uncheck selected option?

                    // DO IT!
                    jQuery('.mobile-date.active').find('.date-selector i').hide();
                    jQuery('.mobile-date.active').removeClass('active');
                    jQuery(this).removeClass('active');
                    var currentselectedoptions = jQuery('#delivery-date-selected-options').find('ul li');
                    jQuery(currentselectedoptions).removeClass('selected').find('.fauxradio i').hide();
                    jQuery('#delivery-date-selected-options').children().detach().appendTo('#delivery-date-other-options');
                    jQuery('#delivery-date-other-options .day-options-'+dateval).detach().appendTo('#delivery-date-selected-options');
                    jQuery('#delivery-date-selected-options').find('input').prop('disabled',false);

                   var itemscount  = jQuery('#delivery-date-selected-options').find('ul').length;

                   if (itemscount === 1) {
                       // TODO: Make this admin selectable
                       // console.log('just the one!');
                       //jQuery('#delivery-date-selected-options').find('input').prop('checked',true);
                       //clickMobileDateOption(jQuery('#delivery-date-selected-options').find('li'));
                   }

                } else {
                    jQuery('#delivery-description-store .del-desc-store-notice').hide();
                    jQuery('.mobile-date.active').find('.date-selector i').hide();
                    jQuery('.mobile-date.active').removeClass('active');
                    jQuery(this).addClass('active').find('.date-selector i').show();


                    var currentselectedoptions = jQuery('#delivery-date-selected-options').find('ul li');
                    jQuery(currentselectedoptions).removeClass('selected').find('.fauxradio i').hide();
                    jQuery('#delivery-date-selected-options').children().detach().appendTo('#delivery-date-other-options');
                    jQuery('#delivery-date-other-options .day-options-'+dateval).appendTo('#delivery-date-selected-options');
                    jQuery('#delivery-date-selected-options').find('input').prop('disabled',false);

                    var itemscount  = jQuery('#delivery-date-selected-options').find('ul').length;

                    if (itemscount === 1) {
                        //console.log('just the one!');
                        //jQuery('#delivery-date-selected-options').find('input').prop('checked',true);
                        //clickMobileDateOption(jQuery('#delivery-date-selected-options').find('li'));
                    }
                }
            });
            jQuery('input[name="deliveryMobile"]').click(function(){
                clickMobileDateOption(this);
                // TODO: WARNING NEED TO FIX IF RADIO IS SHOWN!!!
            });
            jQuery('.delivery-radio-selector').click(function() {
                clickMobileDateOption(this);
            });

            // Clear Options
            // Select first Available Date

            jQuery('#mobile-dates-container').find('.mobile-date:first').click();
        }
        function clickMobileDateOption(parent) {
            //console.log('clickmobiledateoption');
            //console.log(jQuery(this).find('input[name="deliveryMobile"]'));
            var input = jQuery(parent).find('input[name="deliveryMobile"]').prop('checked', true);
            var dateval = jQuery(input).val();
            var li = jQuery(parent).parent();
            var ul = jQuery(li).parent();
            var otherlis = jQuery(ul).find('li');
            jQuery(otherlis).removeClass('selected');
            jQuery(otherlis).find('.fauxradio i').hide();
            jQuery(otherlis).removeClass('selected');
            if (jQuery(li).hasClass('no-selectables')) {
                // don't do anything
                jQuery('#delivery-description-store .del-desc-store-notice').hide();
            } else {
                jQuery(li).addClass('selected');
                jQuery('#delivery-description-store .del-desc-store-notice').show();
                jQuery(li).find('.fauxradio i').show();
            }



            jQuery('#deliveryMethodSelected').val(dateval);
            //console.log('clicked del mobile');
            var methodid = jQuery(input).attr('method');
            //console.log(methodid);


            jQuery('#delivery-description-store-inner .delivery-desc').hide();
            var delivery_desc = '#delivery-desc-checkout-' + methodid;
            jQuery(delivery_desc).show();
        }
        function hasInit() {
            if (inited === 1) {
                return true;
            } else {
                return false;
            }
        }
        function changeMobileMonth() {
            console.log('delslider changed mobilemonth');
            // For Changing Month Based on Dates Displayed
            var months = {}

            // Page Items Active on screen
            jQuery('#mobile-dates-container .tns-slide-active').each(function(index, element){
                var data_month = jQuery(this).attr('data-month');
                console.log(data_month);

                if (months.hasOwnProperty(data_month)) {
                    var count = parseInt(months[data_month]);

                    count++;

                    months[data_month] = count;
                } else {
                    months[data_month] = 1;
                }

            });

            var new_array = sortObject(months);


            // Month with highest number of dates visible will be 0 in array.
            var month_to_display = new_array[0].key;

            jQuery('.mobile-month').html(month_to_display);
        }
        function sortObject(obj) {
                var arr = [];
                for (var prop in obj) {
                    if (obj.hasOwnProperty(prop)) {
                        arr.push({
                            'key': prop,
                            'value': obj[prop]
                        });
                    }
                }
                arr.sort(function(a, b) { return b.value - a.value; });
                //arr.sort(function(a, b) { a.value.toLowerCase().localeCompare(b.value.toLowerCase()); }); //use this to sort as strings
                return arr; // returns array
        }
        function ajaxOverlayPostcodeShow() {
            jQuery("#delivery-mask-overlay").show().removeClass('delivery-mask-hidden');

        }
        function ajaxOverlayPostcodeHide() {
            console.log('ajax overlay hide');
            jQuery("#delivery-mask-overlay").addClass('delivery-mask-hidden').promise().done(function () {
                jQuery('#delivery-mask-overlay').hide();
            });
            jQuery("#delivery-enter-postcode").hide();
        }
        function checkoutIncorrectPostcode() {

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
            }
        function addDeliveryDescriptions(data) {
                var delivery_descriptions = data.delivery_descriptions;
                var delivery_description_output = '';
                for (var key in delivery_descriptions) {
                    if (delivery_descriptions.hasOwnProperty(key)) {
                        delivery_description_output += '<div id="delivery_desc' + key + '" class="delivery_desc">' + delivery_descriptions[key] + '</div>';
                    }
                }
                jQuery("#delivery-bottom-message-1").html(delivery_description_output);
                jQuery('#delivery-bottom-message-1 .delivery_desc').hide();
            }
        function addDeliveryDescriptionsCheckout(data) {
            var delivery_descriptions = data.delivery_descriptions_checkout;
            var delivery_description_output = '';
            for (var key in delivery_descriptions) {
                if (delivery_descriptions.hasOwnProperty(key)) {
                    delivery_description_output += '<div id="delivery_desc_checkout' + key + '" class="delivery_desc">' + delivery_descriptions[key] + '</div>';
                }
            }
            jQuery("#delivery-bottom-message-1").html(delivery_description_output);
            jQuery('#delivery-bottom-message-1 .delivery_desc').hide();
        }
        function showDeliveryDescription(value,selector) {
            jQuery("#delivery-bottom-message-1").hide();
            jQuery("#delivery-bottom-message-1 .delivery_desc").hide();
            var delivery_desc_for_checkoutreview = jQuery("#" + selector + value).text();
            jQuery('#checkout-shipping-description').text(delivery_desc_for_checkoutreview);
            jQuery("#" + selector + value).show();
            this.showDeliveryBottomMessage();
        }
        function showDeliveryBottomMessage() {
            if (jQuery('#delivery-bottom-message-1').is(':hidden')) {
                jQuery("#delivery-bottom-message-1").show();
            }
        }
        function showCartExtendedMessage() {
            jQuery('#delivery-options-message-extended').html('<span>One or more of the items in your cart has an extended delivery date. The first date shown above is the first date your order will be available for delivery.</span>');
        }
        function hideCartExtendedMessage() {
            jQuery('#delivery-options-message-extended').html('');
        }
        function hideDeliveryBottomMessage() {
            if (jQuery('#delivery-bottom-message-1').is(':visible')) {
                jQuery("#delivery-bottom-message-1").hide();
            }
        }

        return {
            init: function () {
                //console.log('in init function!');
                this.getDeliveryOptions();
                //reloadMobileSlider();
                inited = 1;


            },
            hasInit: function () {
                if (inited === 1) {
                    return true;
                } else {
                    return false;
                }
            },
            getHasFirstRun: function () {
                return hasFirstRun;
            },
            setHasFirstRun: function () {
                hasFirstRun = true;
            },
            getDeliveryOptions: function () {
               getDeliveryOptions();

            },

            sliderreloaded: function (method, previous_date_selected) {
            },
            ajaxOverlayPostcodeShow: function () {
                ajaxOverlayPostcodeShow();
            },
            ajaxOverlayPostcodeHide: function () {
               ajaxOverlayPostcodeHide()
            },
            checkoutIncorrectPostcode: function () {
                checkoutIncorrectPostcode();
            },
            addDeliveryDescriptions: function (data) {
             addDeliveryDescriptions(data);
            },
            addDeliveryDescriptionsCheckout: function (data) {
               addDeliveryDescriptionsCheckout(data);
            },
            showDeliveryDescription: function (value) {
                showDeliveryDescription();
            },
            showDeliveryBottomMessage: function () {
               showDeliveryBottomMessage();
            },
            showCartExtendedMessage: function () {
              showCartExtendedMessage();
            },
            hideCartExtendedMessage: function () {
                hideCartExtendedMessage();
            },
            hideDeliveryBottomMessage: function () {
              hideDeliveryBottomMessage();
            },
            reloadTooltips: function () {
            },
            mobileSliderClick: function () {
            },

        };
    });
