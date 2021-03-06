require(['jquery','tinyslider','evDelivery','Magento_Checkout/js/model/quote', 'Magento_Customer/js/model/customer'], function ($,tinyslider,evDelivery, quote,customer) {




    ELEVATE.DELIVERYSTART = (function () {

        var deliveryLaunched = false;
        var modal;
        var iscustomerloggedin = customer.isLoggedIn();
        var attachonce = false;
        var proceedclicked = false;
        var launchedDelivery_function_once = false;


        function launchDelivery() {

            obs.disconnect(); // Stop Observer!
            launchedDelivery_function_once = true;

            console.log('Launch Delivery Function');
            console.log('deliveryLaunched: ' + deliveryLaunched);


            if (iscustomerloggedin) {
                // Customer Logged in
                console.log('Customer is logged in');
                // Elevate Delivery - Ensures it fires the delivery method switcher
                console.log('shippingaddressitems');
                console.log(jQuery('.shipping-address-item'));
                console.log(jQuery('.shipping-address-items .selected-item').length);
                if (jQuery('.shipping-address-items .selected-item').length == 1) {
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
                jQuery('.action-save-address').on('click', function () {
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
                jQuery('.action-select-shipping-item').on('click', function () {
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
                // Initial Handling
                var postcodefieldval = jQuery('#shipping-new-address-form [name="postcode"]').val();
                if (typeof postcodefieldval === 'undefined') {
                    postcodefieldval = postcode;
                }
                if (postcodefieldval) {
                    if (postcodefieldval.length > 2) {
                        evDelivery.getDeliveryOptions();
                    }
                    console.log("delivery Launched - STOP IT ")
                    deliveryLaunched = true;
                }
            }
            if (attachonce !== true) {

                jQuery('#shipping-new-address-form input[name="postcode"]').on('input', throttle(function (event) {
                    console.log(event);
                    console.log('ON CHANGE EVENT DETECTS - changed Postcode');
                    var postcodefieldval = jQuery('#shipping-new-address-form [name="postcode"]').val();
                    if (postcodefieldval) {
                        if (postcodefieldval.length > 2) {
                            evDelivery.getDeliveryOptions();
                        }
                    }


                    //evDelivery.getDeliveryOptions();
                    ///roperty "EvGiftmessagemessage" does not have accessor method "getEvGiftmessagemessage" in class "Magento\Quote\Api\Data\AddressExtensionInterface"."

                }, 1000));
                jQuery('#shipping-new-address-form select[name=country_id]').on('change', throttle(function (event) {
                    console.log(event);
                    console.log('country Changed');
                    //console.log(this);

                    evDelivery.getDeliveryOptions();
                }, 1000));
                console.log('attachonce = true');
                attachonce = true;
            }


        }

        function throttle(func, interval)
        {
            var lastCall = 0;
            return function () {
                var now = Date.now();
                if (lastCall + interval < now) {
                    lastCall = now;
                    return func.apply(this, arguments);
                }
            };
        }
        function debounce(func, interval)
        {
            var lastCall = -1;
            return function () {
                clearTimeout(lastCall);
                var args = arguments;
                var self = this;
                lastCall = setTimeout(function () {
                    func.apply(self, args);
                }, interval);
            };
        }


        function init()
        {

        }
        return {
            launchDelivery : function () {
                launchDelivery();
            },
            init : function () {
                init();
            }
        };
    }());







// Get container
    const getContainerElement = () =>
        document.getElementById(
            'radio_deliveryoption'
        );

    MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
    var obs = new MutationObserver(function (mutations, observer) {
        if (getContainerElement()) {
            ELEVATE.DELIVERYSTART.launchDelivery();
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


    var obs_postcode = new MutationObserver(function (mutations, observer) {
        if (getContainerElementPostcode()) {
            ELEVATE.DELIVERYSTART.launchDelivery();
        }

    });


    obs.observe(document.body, {
        attributes: true,
        childList: true,
        characterData: false,
        subtree: true
    });

    function cc_m2_c2a() {
        /**
         * wait for form to exist before continuing
         * (needed on sites that load page elements
         * via multiple ajax requests)
         */
        if (jQuery('[name="postcode"]').length == 0 || jQuery('[name="street[0]"]').length == 0) {
            return;
        }

        jQuery('[name="postcode"]').each(function (index, elem) {
            if (jQuery(elem).data('cc_attach') != '1') {
                jQuery(elem).data('cc_attach', '1');
                var form = jQuery(elem).closest('form');

                var custom_id = '';
                if (c2a_config.autocomplete.advanced.search_elem_id !== null) {
                    custom_id = ' id="' + c2a_config.autocomplete.advanced.search_elem_id + '"'
                }

                // null fix for m2_1.1.16
                if (c2a_config.autocomplete.texts.search_label == null) c2a_config.autocomplete.texts.search_label = '';

                var tmp_html = '<div class="field required"' + custom_id + '><label class="label">' +
                    c2a_config.autocomplete.texts.search_label + '</label>' +
                    '<div class="control"><input class="cc_search_input" type="text"/></div></div>';
                if (c2a_config.autocomplete.advanced.hide_fields) {
                    var svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 305.67 179.25">' +
                        '<rect x="-22.85" y="66.4" width="226.32" height="47.53" rx="17.33" ry="17.33" transform="translate(89.52 -37.99) rotate(45)"/>' +
                        '<rect x="103.58" y="66.4" width="226.32" height="47.53" rx="17.33" ry="17.33" transform="translate(433.06 0.12) rotate(135)"/>' +
                        '</svg>';
                    tmp_html += '<div class="field action secondary cc_hide_fields_action"><label>' + c2a_config.autocomplete.texts.manual_entry_toggle + '</label>' + svg + '</div>';
                }
                if (!c2a_config.autocomplete.advanced.use_first_line || c2a_config.autocomplete.advanced.hide_fields) {
                    form.find('[name="street[0]"]').closest('fieldset').before(tmp_html);
                } else {
                    form.find('[name="street[0]"]').addClass('cc_search_input');
                }
                if (c2a_config.autocomplete.advanced.lock_country_to_dropdown) {
                    if (c2a_config.autocomplete.advanced.use_first_line) {
                        form.find('.cc_search_input').closest('fieldset').before(form.find('[name="country_id"]').closest('div.field'));
                    } else {
                        form.find('.cc_search_input').closest('div.field').before(form.find('[name="country_id"]').closest('div.field'));
                    }
                }

                var dom = {
                    search: form.find('.cc_search_input'),
                    company: form.find('[name="company"]'),
                    line_1: form.find('[name="street[0]"]'),
                    line_2: form.find('[name="street[1]"]'),
                    postcode: form.find('[name="postcode"]'),
                    town: form.find('[name="city"]'),
                    county: {
                        input: form.find('[name="region"]'),
                        list: form.find('[name="region_id"]')
                    },
                    country: form.find('[name="country_id"]')
                };

                window.cc_holder.attach({
                    search: dom.search[0],
                    company: dom.company[0],
                    line_1: dom.line_1[0],
                    line_2: dom.line_2[0],
                    postcode: dom.postcode[0],
                    town: dom.town[0],
                    county: {
                        input: dom.county.input,
                        list: dom.county.list
                    },
                    country: dom.country[0]
                });
                form.find('.cc_hide_fields_action').on('click', function () {
                    cc_hide_fields(dom, 'manual-show')
                });

                cc_hide_fields(dom, 'init');
            }
        });
    }

// Postcode Lookup
    function activate_cc_m2_uk() {
        if (c2a_config.postcodelookup.enabled) {
            var cfg = {
                id: "",
                core: {
                    key: c2a_config.main.key,
                    preformat: true,
                    capsformat: {
                        address: true,
                        organization: true,
                        county: true,
                        town: true
                    }
                },
                dom: {},
                sort_fields: {
                    active: true,
                    parent: '.field:not(.additional)'
                },
                hide_fields: c2a_config.postcodelookup.hide_fields,
                txt: c2a_config.postcodelookup.txt,
                error_msg: c2a_config.postcodelookup.error_msg,
                county_data: c2a_config.postcodelookup.advanced.county_data,
                ui: {
                    onResultSelected: function (dataset, id, fields) {
                        fields.postcode.closest('form').find('.cp_manual_entry').hide(200)
                    }
                }
            };
            var dom = {
                company: '[name="company"]',
                address_1: '[name="street[0]"]',
                address_2: '[name="street[1]"]',
                postcode: '[name="postcode"]',
                town: '[name="city"]',
                county: '[name="region"]',
                county_list: '[name="region_id"]',
                country: '[name="country_id"]'
            };
            var postcode_elements = jQuery(dom.postcode);
            postcode_elements.each(function (index) {
                /**
                 * The Magento 2 checkout loads fields
                 * asynchronously so we need to check
                 * for the existence of multiple fields
                 * before continuing. This helps avoid
                 * a race condition scenario on slow
                 * devices/connections.
                 */
                var form = postcode_elements.eq(index).closest('form');
                if (
                    postcode_elements.eq(index).attr('cc_pcl_applied') != '1'
                    && form.find(dom.address_1).length === 1
                    && form.find(dom.country).length === 1
                ) {
                    var active_cfg = {};
                    jQuery.extend(active_cfg, cfg);
                    active_cfg.id = "m2_" + cc_index;

                    cc_index++;
                    active_cfg.dom = {
                        company: form.find(dom.company),
                        address_1: form.find(dom.address_1),
                        address_2: form.find(dom.address_2),
                        postcode: postcode_elements.eq(index),
                        town: form.find(dom.town),
                        county: form.find(dom.county),
                        county_list: form.find(dom.county_list),
                        country: form.find(dom.country)
                    };

                    // modify the Layout
                    var postcode_elem = active_cfg.dom.postcode;
                    postcode_elem.wrap('<div class="search-bar"></div>');
                    postcode_elem.after('<button type="button" class="action primary">' +
                        '<span>' + active_cfg.txt.search_buttontext + '</span></button>');
                    // STANDARD
                    postcode_elem.closest('.search-bar').after('<div class="search-list" style="display: none;"><select></select></div>' +
                        '<div class="mage-error" generated><div class="search-subtext"></div></div>');

                    // input after postcode
                    var new_container = postcode_elem.closest(active_cfg.sort_fields.parent);
                    new_container.addClass('search-container').attr('id', active_cfg.id).addClass('type_3');

                    // add/show manual entry text
                    if (active_cfg.hide_fields) {
                        if (jQuery('#' + active_cfg.id + '_cp_manual_entry').length === 0 && postcode_elem.val() === "") {
                            var svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 305.67 179.25">' +
                                '<rect x="-22.85" y="66.4" width="226.32" height="47.53" rx="17.33" ry="17.33" transform="translate(89.52 -37.99) rotate(45)"/>' +
                                '<rect x="103.58" y="66.4" width="226.32" height="47.53" rx="17.33" ry="17.33" transform="translate(433.06 0.12) rotate(135)"/>' +
                                '</svg>';
                            tmp_manual_html = '<div class="field cp_manual_entry" id="' + active_cfg.id + '_cp_manual_entry" style="margin-top: 15px; margin-bottom: 15px;"><label>' + active_cfg.txt.manual_entry + '</label>' + svg + '</div>';
                            jQuery(postcode_elem).closest('.field').find('button').after(tmp_manual_html)

                            jQuery('#' + active_cfg.id + '_cp_manual_entry').on('click', function () {
                                jQuery(form).find('.crafty_address_field').removeClass('crafty_address_field_hidden');
                                jQuery('#' + active_cfg.id + '_cp_manual_entry').hide(200)
                            })
                        }
                    }

                    active_cfg.dom.postcode.attr('cc_pcl_applied', '1');
                    var cc_generic = new cc_ui_handler(active_cfg);
                    cc_generic.activate();
                }
            });
        }
    }

    var cc_index = 0;

    window.cc_holder = null;

    function cc_hide_fields(dom, action) {
        var action = action || 'show';
        if (!c2a_config.autocomplete.advanced.hide_fields) {
            return;
        }
        switch (action) {
            case 'init':
                var elementsToHide = ['line_1', 'line_2', 'line_3', 'line_4', 'town', 'postcode', 'county'];
                // determine if we can hide by default
                var formEmpty = true;
                for (var i = 0; i < elementsToHide.length - 1; i++) { // -1 is to skip County
                    if (jQuery(dom[elementsToHide[i]]).length && jQuery(dom[elementsToHide[i]]).val() !== '') {
                        formEmpty = false;
                    }
                }
                if (!c2a_config.autocomplete.advanced.lock_country_to_dropdown) {
                    elementsToHide.push('country');
                }
                for (var i = 0; i < elementsToHide.length; i++) {
                    if (jQuery(dom[elementsToHide[i]]).length) {
                        switch (elementsToHide[i]) {
                            case 'county':
                                jQuery(dom[elementsToHide[i]].input).closest('.field').addClass('cc_hide');
                                jQuery(dom[elementsToHide[i]].list).closest('.field').addClass('cc_hide');
                                break;
                            case 'line_1':
                                jQuery(dom[elementsToHide[i]]).closest('fieldset.field').addClass('cc_hide');
                                break;
                            default:
                                jQuery(dom[elementsToHide[i]]).closest('.field').addClass('cc_hide');
                        }
                    }
                }
                var form = jQuery(dom.country).closest('form');
                // store the checking loop in the DOM object
                form.data('cc_hidden', 0);
                if (formEmpty) {
                    cc_hide_fields(dom, 'hide');
                } else {
                    cc_hide_fields(dom, 'show');
                }
                setInterval(function () {
                    cc_reveal_fields_on_error(dom);
                }, 250);
                break;
            case 'hide':
                var form = jQuery(dom.country).closest('form');
                form.find('.cc_hide').each(function (index, item) {
                    jQuery(item).addClass('cc_hidden');
                });
                form.find('.cc_hide_fields_action').removeClass('cc_slider_on');
                form.data('cc_hidden', 1);
                break;
            case 'manual-show':
            case 'show':
                var form = jQuery(dom.country).closest('form');
                // WHY TRIGGER A CHANGE ON AN ELEMENT THAT MAY NOT HAVE CHANGED?!!
                //jQuery(dom.country).trigger('change');
                form.find('.cc_hide').each(function (index, item) {
                    jQuery(item).removeClass('cc_hidden');
                });
                form.find('.cc_hide_fields_action').hide(200);
                form.data('cc_hidden', 0);
                if (action == 'manual-show') {
                    // How about no?
                    //jQuery(dom.country).trigger('change');
                }
                break;
            case 'toggle':
                var form = jQuery(dom.country).closest('form');
                if (form.data('cc_hidden') == 1) {
                    cc_hide_fields(dom, 'show');
                } else {
                    cc_hide_fields(dom, 'hide');
                }
                break;
        }
    }

    function cc_reveal_fields_on_error(dom) {
        var form = jQuery(dom.country).closest('form');
        var errors_present = false;
        form.find('.cc_hide').each(function (index, item) {
            if (jQuery(item).hasClass('_error')) {
                errors_present = true;
            }
        });
        if (errors_present) {
            cc_hide_fields(dom, 'show');
            form.find('.cc_hide_fields_action').hide(); // prevent the user from hiding the fields again
        }
    }

    jQuery(document).ready(function () {
        if(typeof c2a_config !== 'undefined') {
            if (!c2a_config.main.enable_extension) {
                return;
            }
            if (c2a_config.autocomplete.enabled && c2a_config.main.key != null) {
                var config = {
                    accessToken: c2a_config.main.key,
                    onSetCounty: function (c2a, elements, county) {
                        return;
                    },
                    domMode: 'object',
                    gfxMode: c2a_config.autocomplete.gfx_mode,
                    style: {
                        ambient: c2a_config.autocomplete.gfx_ambient,
                        accent: c2a_config.autocomplete.gfx_accent
                    },
                    showLogo: false,
                    texts: c2a_config.autocomplete.texts,
                    onResultSelected: function (c2a, elements, address) {
                        switch (address.country_name) {
                            case 'Jersey':
                                jQuery(elements.country).val('JE')
                                break;
                            case 'Guernsey':
                                jQuery(elements.country).val('GG')
                                break;
                            case 'Isle of Man':
                                jQuery(elements.country).val('IM')
                                break;
                            default:
                                if (jQuery(elements.country).val() == address.country.iso_3166_1_alpha_2) {
                                    console.log("don't trigger change you idiots");
                                } else {
                                    jQuery(elements.country).val(address.country.iso_3166_1_alpha_2);
                                }

                        }
                        // var event = new Event('change')
                        if (typeof elements.country != 'undefined') {
                            if  (jQuery(elements.country).val() == address.country.iso_3166_1_alpha_2) {
                                // Stop Triggering a change when the country doesn't actually change?
                                console.log('no more change triggering please');
                            } else {
                                console.log('trigger event change contry');
                                triggerEvent('change', elements.country);
                                evDelivery.getDeliveryOptions();
                            }


                        }

                        var county = {
                            preferred: address.province,
                            code: address.province_code,
                            name: address.province_name
                        };

                        if (elements.county.list.length == 1) {
                            c2a.setCounty(elements.county.list[0], county);
                        }
                        if (elements.county.input.length == 1) {
                            c2a.setCounty(elements.county.input[0], county);
                        }

                        if (typeof elements.county.input[0] != 'undefined') {
                            triggerEvent('change', elements.county.input[0])
                        }
                        if (typeof elements.county.list[0] != 'undefined') {
                            triggerEvent('change', elements.county.list[0])
                        }
                        if (typeof elements.company != 'undefined') {
                            triggerEvent('change', elements.company)
                        }
                        if (typeof elements.line_1 != 'undefined') {
                            triggerEvent('change', elements.line_1)
                        }
                        if (typeof elements.line_2 != 'undefined') {
                            triggerEvent('change', elements.line_2)
                        }
                        if (typeof elements.postcode != 'undefined') {
                            triggerEvent('change', elements.postcode);
                            console.log('Postcode Not Undefined, trigger Change');
                            // Only want it to trigger if the postcode is different though!!!
                            evDelivery.getDeliveryOptions();
                        }
                        if (typeof elements.town != 'undefined') {
                            triggerEvent('change', elements.town)
                        }

                        cc_hide_fields(elements, 'show');
                    },
                    onError: function () {
                        if (typeof this.activeDom.postcode !== 'undefined') {
                            cc_hide_fields(this.activeDom, 'show');
                        } else {
                            c2a_config.autocomplete.advanced.hide_fields = false;
                        }
                    },
                    transliterate: c2a_config.autocomplete.advanced.transliterate,
                    debug: c2a_config.autocomplete.advanced.debug,
                    cssPath: false,
                    tag: 'Magento 2'
                };
                if (typeof c2a_config.autocomplete.enabled_countries !== 'undefined') {
                    config.countryMatchWith = 'iso_2';
                    config.enabledCountries = c2a_config.autocomplete.enabled_countries;
                }
                if (c2a_config.autocomplete.advanced.lock_country_to_dropdown) {
                    config.countrySelector = false;
                    config.onSearchFocus = function (c2a, dom) {
                        var currentCountry = dom.country.options[dom.country.selectedIndex].value;
                        if (currentCountry !== '') {
                            console.log("Current COuntry My 3393");
                            var countryCode = getCountryCode(c2a, currentCountry, 'iso_2');
                            c2a.selectCountry(countryCode);
                        }
                    };
                }

                window.cc_holder = new clickToAddress(config);
                setInterval(cc_m2_c2a, 200);
            }

            if (c2a_config.autocomplete.enabled && c2a_config.main.key == null) {
                console.warn('ClickToAddress: Incorrect token format supplied');
            }

            if (c2a_config.postcodelookup.enabled) {
                setInterval(activate_cc_m2_uk, 200);
            }


            if (c2a_config.emailvalidation.enabled && c2a_config.main.key != null) {
                if (window.cc_holder == null) {
                    window.cc_holder = new clickToAddress({
                        accessToken: c2a_config.main.key,
                    })
                }
                setInterval(function () {
                    var email_elements = jQuery('input#customer-email');
                    email_elements.each(function (index) {
                        var email_element = email_elements.eq(index);
                        if (email_element.data('cc') != '1') {
                            email_element.data('cc', '1');
                            window.cc_holder.addEmailVerify({
                                email: email_element[0]
                            })
                        }
                    });
                }, 200);
            }
            if (c2a_config.phonevalidation.enabled && c2a_config.main.key != null) {
                if (window.cc_holder == null) {
                    window.cc_holder = new clickToAddress({
                        accessToken: c2a_config.main.key,
                    })
                }
                setInterval(function () {
                    var phone_elements = jQuery('input[name="telephone"]');
                    phone_elements.each(function (index) {
                        var phone_element = phone_elements.eq(index);
                        if (phone_element.data('cc') != '1') {
                            phone_element.data('cc', '1');
                            var country = phone_element.closest('form').find('select[name="country_id"]')
                            window.cc_holder.addPhoneVerify({
                                phone: phone_element[0],
                                country: country[0]
                            })
                        }
                    });
                }, 200);
            }
        }



    });
// utilities
    function triggerEvent(eventName, target){
        var event;
        if (typeof(Event) === 'function') {
            event = new Event(eventName);
        } else {
            event = document.createEvent(eventName);
            event.initEvent(eventName, true, true);
        }
        target.dispatchEvent(event);
    }

});
