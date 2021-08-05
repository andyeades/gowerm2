define(
    ['jquery'],
    function($)
    {
        "use strict";

        /**
         * Return the Component
         */
        var inited = 0;
        var hasFirstRun = false;
        var self = this;

        function reloadMobileSlider() {
            var mobileslider = jQuery('.owl-carousel').owlCarousel({
                //loop:true,
                margin:10,
                nav:true,
                navElement:'div',
                navText:["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
                responsive:{
                    0:{
                        items:7
                    },
                    600:{
                        items:7
                    },
                    1000:{
                        items:7
                    }
                }
            });

            this.mobileslider.on('changed.owl.carousel', function(event) {
                // Change Mobile Month Displayed dependent on number of
                changeMobileMonth();
            })
            jQuery('.mobile-date').click(function(){
                jQuery('#deliveryMethodSelected').val('');

                jQuery('.delivery_desc').hide();
                var previouslychecked_item = jQuery('input[name="deliveryMobile"]:checked');
                jQuery(previouslychecked_item).prop('checked',false);
                var dateval = jQuery(this).attr('data-date');
                var methodid = jQuery(this).attr('method');
                if (jQuery(this).hasClass('active')) {
                    jQuery('#delivery-bottom-message-1 .delivery_desc').hide();
                    // Uncheck selected option?

                    // DO IT!
                    jQuery('.mobile-date.active').removeClass('active');
                    jQuery(this).removeClass('active');
                    jQuery('#delivery-date-selected-options').children().detach().appendTo('#delivery-date-other-options');
                    jQuery('#delivery-date-other-options .day-options-'+dateval).detach().appendTo('#delivery-date-selected-options');
                    jQuery('#delivery-date-selected-options').find('input').prop('disabled',false);

                } else {
                    jQuery('.mobile-date.active').removeClass('active');
                    jQuery(this).addClass('active');
                    jQuery('#delivery-date-selected-options').children().detach().appendTo('#delivery-date-other-options');
                    jQuery('#delivery-date-other-options .day-options-'+dateval).appendTo('#delivery-date-selected-options');
                    jQuery('#delivery-date-selected-options').find('input').prop('disabled',false);


                }
            });
            jQuery('input[name="deliveryMobile"]').click(function(){
                var methodid = jQuery(this).attr('method');
                jQuery('#delivery-bottom-message-1 .delivery_desc').hide();
                var delivery_desc = '#delivery_desc_checkout' + methodid;
                jQuery(delivery_desc).show();
            });
            jQuery('.delivery-radio-selector').click(function() {
                jQuery(this).find('input[name="deliveryMobile"]').prop('checked', true);
                var dateval = jQuery(this).val();
                jQuery('#deliveryMethodSelected').val(dateval);
            });
        }

        return {
            init: function () {
                console.log('in init function!');
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

                        this.deliveryinfo = response.methods_available;
                        //quote['deliveryinfo'] = this.deliveryinfo;
                        jQuery('.display-this').show();
                        //console.log(response.mobile_day_options_1);

                        jQuery('#delivery-calendar').html(response.mobile_day_options_1);
                        jQuery('#delivery-date-other-options').html(response.mobile_day_options_2);
                        // set descriptions
                        jQuery('#delivery-description-store').html(response.delivery_descriptions_checkout);
                        //reloadMobileSlider();
                        jQuery('#mobile-month').html(response.first_month_to_display);
                        console.log(this);
                        console.log(parent);
                        this.reloadMobileSlider();

                        jQuery('.loading').hide();
                    })
                    .fail(function () {
                        console.log(error);
                    });
                // So Default Click happens
                return true;
            },

            sliderreloaded: function (method, previous_date_selected) {
            },
            ajaxloadingshow: function () {
            },
            ajaxloadinghide: function () {

            },
            ajaxOverlayPostcodeShow: function () {
                jQuery("#delivery-mask-overlay").show().removeClass('delivery-mask-hidden');
                this.ajaxloadingshow();
                //jQuery("#delivery-enter-postcode").addClass('display_block');
            },
            ajaxOverlayPostcodeHide: function () {
                this.ajaxloadinghide();
                jQuery("#delivery-mask-overlay").addClass('delivery-mask-hidden').promise().done(function () {
                    jQuery('#delivery-mask-overlay').hide();
                })
                jQuery("#delivery-enter-postcode").hide();
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
            addDeliveryDescriptions: function (data) {
                var delivery_descriptions = data.delivery_descriptions;
                var delivery_description_output = '';
                for (var key in delivery_descriptions) {
                    if (delivery_descriptions.hasOwnProperty(key)) {
                        delivery_description_output += '<div id="delivery_desc' + key + '" class="delivery_desc">' + delivery_descriptions[key] + '</div>';
                    }
                }
                jQuery("#delivery-bottom-message-1").html(delivery_description_output);
                jQuery('#delivery-bottom-message-1 .delivery_desc').hide();
            },
            addDeliveryDescriptionsCheckout: function (data) {
                var delivery_descriptions = data.delivery_descriptions_checkout;
                var delivery_description_output = '';
                for (var key in delivery_descriptions) {
                    if (delivery_descriptions.hasOwnProperty(key)) {
                        delivery_description_output += '<div id="delivery_desc_checkout' + key + '" class="delivery_desc">' + delivery_descriptions[key] + '</div>';
                    }
                }
                jQuery("#delivery-bottom-message-1").html(delivery_description_output);
                jQuery('#delivery-bottom-message-1 .delivery_desc').hide();
            },
            showDeliveryDescription: function (value) {
                jQuery("#delivery-bottom-message-1").hide();
                jQuery("#delivery-bottom-message-1 .delivery_desc").hide();
                var delivery_desc_for_checkoutreview = jQuery("#delivery_desc" + value).text();
                jQuery('#checkout-shipping-description').text(delivery_desc_for_checkoutreview);
                jQuery("#delivery_desc" + value).show();
                this.showDeliveryBottomMessage();
            },
            showDeliveryDescriptionCheckout: function (value) {
                jQuery("#delivery-bottom-message-1").hide();
                jQuery("#delivery-bottom-message-1 .delivery_desc").hide();
                var delivery_desc_for_checkoutreview = jQuery("#delivery_desc_checkout" + value).text();
                jQuery('#checkout-shipping-description').text(delivery_desc_for_checkoutreview);
                jQuery("#delivery_desc_checkout" + value).show();
                this.showDeliveryBottomMessage();
            },
            showDeliveryBottomMessage: function () {
                if (jQuery('#delivery-bottom-message-1').is(':hidden')) {
                    jQuery("#delivery-bottom-message-1").show();
                }
            },
            showCartExtendedMessage: function () {
                jQuery('#delivery-options-message-extended').html('<span>One or more of the items in your cart has an extended delivery date. The first date shown above is the first date your order will be available for delivery.</span>');
            },
            hideCartExtendedMessage: function () {
                jQuery('#delivery-options-message-extended').html('');
            },
            hideDeliveryBottomMessage: function () {
                if (jQuery('#delivery-bottom-message-1').is(':visible')) {
                    jQuery("#delivery-bottom-message-1").hide();
                }
            },
            reloadTooltips: function () {
            },
            sortObject: function(obj) {
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
            },
            mobileSliderClick: function () {
            },
            changeMobileMonth: function () {
                console.log('delslider changed mobilemonth');
                // For Changing Month Based on Dates Displayed
                var months = {}

                // Page Items Active on screen
                jQuery('.owl-item.active').each(function(index, element){
                    var data_month = jQuery(this).find('.mobile-date').attr('data-month');
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
            },
        };
    });