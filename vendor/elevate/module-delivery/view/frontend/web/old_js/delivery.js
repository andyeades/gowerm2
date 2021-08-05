


var DELSLIDER = DELSLIDER || {};

(function ($, window) {

    DELSLIDER = (function () {
        var inited = 0;
        var hasFirstRun = false;
        var self = this;

        // Callback map object


        function setHasFirstRun() {

            hasFirstRun = true;
        }

        function getHasFirstRun() {


            return hasFirstRun;
        }
        function init() {
            reloadMobileSlider();
            inited = 1;
        }

        function hasInit() {
            if (inited === 1) {
                return true;
            } else {
                return false;
            }
        }




        function ajaxOverlayPostcodeShow() {

            jQuery("#delivery-mask-overlay").show().removeClass('delivery-mask-hidden');
            //jQuery("#delivery-enter-postcode").addClass('display_block');
        }

        function ajaxOverlayPostcodeHide() {
            jQuery("#delivery-mask-overlay").addClass('delivery-mask-hidden').promise().done(function () {
                jQuery('#delivery-mask-overlay').hide();
            })
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

        function showCartExtendedMessage() {
            jQuery('#delivery-options-message-extended').html('<span>One or more of the items in your cart has an extended delivery date. The first date shown above is the first date your order will be available for delivery.</span>');
        }

        function hideCartExtendedMessage() {
            jQuery('#delivery-options-message-extended').html('');
        }

        function reloadMobileSlider() {
            console.log('reload mobile slider!');
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

            mobileslider.on('changed.owl.carousel', function(event) {
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


        function changeMobileMonth() {
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
            //console.log(months);



            var new_array = sortObject(months);
            //console.log(new_array);

            // Month with highest number of dates visible will be 0 in array.
            var month_to_display = new_array[0].key;

            jQuery('.mobile-month').html(month_to_display);
        }

        // Sorting Object by Property Values (Descending)
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


        function hideDeliveryBottomMessage() {
            if (jQuery('#delivery-bottom-message-1').is(':visible')) {
                jQuery("#delivery-bottom-message-1").hide();
            }
        }

        function showDeliveryBottomMessage() {
            if (jQuery('#delivery-bottom-message-1').is(':hidden')) {
                jQuery("#delivery-bottom-message-1").show();
            }
        }

        function showDeliveryDescription(value) {
            jQuery("#delivery-bottom-message-1").hide();
            jQuery("#delivery-bottom-message-1 .delivery_desc").hide();
            var delivery_desc_for_checkoutreview = jQuery("#delivery_desc" + value).text();
            jQuery('#checkout-shipping-description').text(delivery_desc_for_checkoutreview);
            jQuery("#delivery_desc" + value).show();
            showDeliveryBottomMessage();
        }

        function showDeliveryDescriptionCheckout(value) {
            jQuery("#delivery-bottom-message-1").hide();
            jQuery("#delivery-bottom-message-1 .delivery_desc").hide();
            var delivery_desc_for_checkoutreview = jQuery("#delivery_desc_checkout" + value).text();
            jQuery('#checkout-shipping-description').text(delivery_desc_for_checkoutreview);
            jQuery("#delivery_desc_checkout" + value).show();
            showDeliveryBottomMessage();
        }

        return {
            init: function () {
                init();
            },
            hasInit: function () {
                return hasInit();
            },
            getHasFirstRun: function () {
                return getHasFirstRun();
            },
            setHasFirstRun: function () {
                setHasFirstRun();
            },


            sliderreloaded: function (method, previous_date_selected) {
                sliderreloaded(method, previous_date_selected);
            },
            ajaxloadingshow: function () {
                ajaxloadingshow();
            },
            ajaxloadinghide: function () {
                ajaxloadinghide();
            },
            ajaxOverlayPostcodeShow: function () {
                ajaxOverlayPostcodeShow();
            },
            ajaxOverlayPostcodeHide: function () {
                ajaxOverlayPostcodeHide();
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
                showDeliveryDescription(value);
            },
            showDeliveryDescriptionCheckout: function (value) {
                showDeliveryDescriptionCheckout(value);
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
                reloadTooltips();
            },
            sortObject: function(obj) {
                sortObject()
            },
            reloadMobileSlider: function () {
                reloadMobileSlider();
            },
            mobileSliderClick: function () {
                mobileSliderClick();
            },
            changeMobileMonth: function () {
                changeMobileMonth();
            },
            dosomething: function () {

            }
        };

    }());

}(jQuery.noConflict(), window));

jQuery(document).ready(function(){


});