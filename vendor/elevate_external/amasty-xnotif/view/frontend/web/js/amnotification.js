/**
 *
 * @author    Amasty Team
 * @copyright Copyright (c) 2016 Amasty (http://www.amasty.com)
 * @package   Amasty_Xnotif
 *
 */
/*jshint browser:true jquery:true*/
define([
    "jquery",
    "underscore",
    "mage/template",
    "priceUtils",
    "Magento_ConfigurableProduct/js/configurable",
    "priceBox",
    "jquery/jquery.parsequery",
    "mage/mage",
    "mage/validation",
    "Magento_Swatches/js/swatch-renderer"
], function ($, _, mageTemplate, utils, Component) {

    $.widget('mage.amnotification', {
        configurableStatus: null,
        spanElement: null,
        parent : null,
        options: {},
        priceAlert: null,
        defaultPriceAlert: '',
        selectors: {
            'add_to_cart' : '.box-tocart'
        },

        _create: function () {
            this._initialization();
            this.spanElement = $('.stock.available span')[0];
            this.settings = this.parent.find('.swatch-option');
            this.dropdowns   = this.parent.find('select.super-attribute-select, select.swatch-select');
            this.priceAlert = $('.alert.price').length ?
                $('.alert.price') :
                $('#form-validate-price').parent();
            if (this.priceAlert.length) {
                this.defaultPriceAlert = this.priceAlert.html();
            }
        },

        _reloadDefaultContent: function () {
            if (this.spanElement) {
                this.spanElement.innerHTML = this.configurableStatus;
            }
            this.toggleAvailabilityClasses(this.options.xnotif.is_in_stock);

            if (this.configurableStatus == null) {
                this.parent.find(this.selectors.add_to_cart).show();
            }

            jQuery('.product-add-form').removeClass('product-add-form-empty');
            if (this.options.is_category) {
                this.parent.find('.amxnotif-container').show();
            }

            if (this.priceAlert.length) {
                this.showPriceAlert(this.defaultPriceAlert);
            }
        },

        showStockAlert: function (code) {
            console.log('showstockalert');
            if (jQuery('.product-add-form form .product-options-wrapper').length == 0) {
            //Simple or configurable that's out of stock?
                jQuery('.product-add-form').addClass('product-add-form-empty');
            }
            var wrapper = $('.product-add-form')[0];
            if (this.options.is_category) {
                wrapper = this.parent.find('[class^="swatch-opt-"]').last();
                this.parent.find('.amxnotif-container').hide();
            }

            var div = $('<div>', {
                'class' : 'amstockstatus-stockalert'
            }).html(code).insertAfter(wrapper);

            var form = $('#form-validate-stock');
            if (this.options.is_category) {
                form = this.parent.find('[id^="form-validate-stock-"]');
                var config = $('body').categorySubscribe('option');
                config.parent = div;
                $.mage.categorySubscribe(config);
            }
            form.mage('validation');
        },

        /*
         * configure statuses at product page
         */
        onConfigure: function () {
            var config = $('#product_addtocart_form').data('mage-configurable');
            console.log(config);
            this._hideStockAlert();
            if (null == this.configurableStatus && this.spanElement) {
                this.configurableStatus = $(this.spanElement).html();
            }
            if (typeof config !== 'undefined') {
                var options = config.options.spConfig.attributes[136].options;

            } else {
                return false;
            }

            var selectedKey = this._getSelectedKey();

            var matched_prodid = null;
            jQuery.each(options, function (key, val) {
                if (parseInt(val.id) == parseInt(selectedKey)) {
                  matched_prodid = parseInt(val.products[0]);
                  return false;
                }
            });

            //console.log(this.options.xnotif);
            //get current selected key
            if (matched_prodid != null) {
                var xnotifInfo = 'undefined' != typeof(this.options.xnotif[selectedKey]) ?
                    this.options.xnotif[selectedKey] :
                    null;
            } else {
                var xnotifInfo = {

                    "is_in_stock" : true,
                }
            }


            if (xnotifInfo) {
                this._reloadContent(xnotifInfo);
            } else {
                this._reloadDefaultContent();
            }

            var inputForPrice = $('#form-validate-price input[name="product_id"]');
            if (this.options.xnotif[selectedKey] && inputForPrice.length) {
                inputForPrice.val(this.options.xnotif[selectedKey]['product_id']);
            }

            /*add statuses to dropdown*/
            // RJ Disabled - Do not want!
            //this._addStatusToDropdown(this.settingsForKey, selectedKey);
        },

        _getSelectedKey: function () {
            var selectedKey = [];
            this.settingsForKey
                = this.parent.find('select.super-attribute-select, div.swatch-option.selected:not(.slick-cloned), select.swatch-select');
            if (this.settingsForKey.length) {
                for (var i = 0; i < this.settingsForKey.length; i++) {
                    if (parseInt(this.settingsForKey[i].value) > 0) {
                        selectedKey.push(this.settingsForKey[i].value);
                    }

                    if (parseInt($(this.settingsForKey[i]).attr('option-id')) > 0) {
                        selectedKey.push($(this.settingsForKey[i]).attr('option-id'));
                    }
                }
            }

            return selectedKey.join(',');
        },

        _addStatusToDropdown: function (settings, selectedKey) {
            var countKeys = selectedKey.split(',').length,
                keyCheck = '';
            for (var i = 0; i < settings.length; i++) {
                if (!settings[i].options) {
                    continue;
                }

                for (var x = 0; x < settings[i].options.length; x++) {
                    if (!settings[i].options[x].value || settings[i].options[x].value == '0') {
                        continue;
                    }

                    if (countKeys === i + 1) {
                        var keyCheckParts = selectedKey.split(',');
                        keyCheckParts[keyCheckParts.length - 1] = settings[i].options[x].value;
                        keyCheck = keyCheckParts.join(',');
                    } else {
                        if (countKeys < i + 1) {
                            keyCheck = (selectedKey ? (selectedKey + ',') : '') + settings[i].options[x].value;
                        }
                    }

                    if ('undefined' != typeof(this.options.xnotif[keyCheck])
                        && this.options.xnotif[keyCheck]
                    ) {
                        settings[i].options[x].disabled = false;
                        var status = this.options.xnotif[keyCheck]['custom_status'];
                        if (status) {
                            status = status.replace(/<(?:.|\n)*?>/gm, ''); // replace html tags
                            if (settings[i].options[x].text.indexOf(status) === -1) {
                                settings[i].options[x].text = settings[i].options[x].text + ' (' + status + ')';
                            }
                        } else {
                            var position = settings[i].options[x].text.indexOf('(');
                            if (position > 0) {
                                settings[i].options[x].text = settings[i].options[x].text.substring(0, position);
                            }
                        }
                    }
                }
            }
        },

        /*
         * reload default stock status after select option
         */
        _reloadContent: function (xnotifInfo) {
            if ('undefined' != typeof(this.options.xnotif.changeConfigurableStatus)
                && this.options.xnotif.changeConfigurableStatus
                && this.spanElement
            ) {
                if (xnotifInfo && xnotifInfo['custom_status']) {
                    this.spanElement.innerHTML = xnotifInfo['custom_status'];
                } else {
                    this.spanElement.innerHTML = this.configurableStatus;
                }
                this.toggleAvailabilityClasses(xnotifInfo['is_in_stock']);
            }

            if ('undefined' != typeof(xnotifInfo)
                && xnotifInfo
                && 0 == xnotifInfo['is_in_stock']
            ) {
                this.parent.find(this.selectors.add_to_cart).hide();

                if (xnotifInfo['stockalert']) {
                    this.showStockAlert(xnotifInfo['stockalert']);
                }
            } else {
                this.parent.find(this.selectors.add_to_cart).show();
            }

            if (xnotifInfo['pricealert'] &&
                this.priceAlert.length
            ) {
                this.showPriceAlert(xnotifInfo['pricealert']);
            }
        },

        showPriceAlert: function (code) {
            this.priceAlert.html(code);
        },

        _removeStockStatus: function () {
            $('#amstockstatus-status').remove();
        },

        /**
         * remove stock alert block
         */
        _hideStockAlert: function () {
            this.parent.find('.amstockstatus-stockalert').remove();
            jQuery('.product-add-form').removeClass('product-add-form-empty');
        },

        toggleAvailabilityClasses: function (inStock) {
            var availabilityElement = $(this.spanElement).parent(),
                addedClass = 'unavailable',
                deletedClass = 'available';
            if (inStock) {
                // swap values in variables
                deletedClass = [addedClass, addedClass = deletedClass][0];
            }
            availabilityElement.removeClass(deletedClass);
            availabilityElement.addClass(addedClass);
        },

        _initialization: function () {
            console.log("ARRGGG!");
            var me = this,
                parent = $('body');

            $(document).ready($.proxy(function () {
                setTimeout(function () {
                    me.onConfigure();
                }, 300);
            },this));

            if (this.options.is_category) {
                parent = this.options.element.first().parents('.item');
                this.selectors.add_to_cart = '[data-role="tocart-form"], .tocart';
            }

            this.parent = parent;

            parent.on(
                {
                    'click': function () {
                        setTimeout(
                            function () {
                                me.onConfigure();
                            },
                            300
                        );
                    }
                },
                'div.swatch-option, select.super-attribute-select, select.swatch-select'
            ).on(
                {
                    'change': function () {
                        setTimeout(
                            function () {
                                me.onConfigure();
                            },
                            300
                        );
                    }
                },
                'select.super-attribute-select, select.swatch-select'
            );
        }
    });

    return $.mage.amnotification;
});
