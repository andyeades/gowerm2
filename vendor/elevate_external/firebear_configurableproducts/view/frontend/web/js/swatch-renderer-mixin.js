/**
 * swatch-renderer-mixin
 *
 * @copyright Copyright © 2020 Firebear Studio. All rights reserved.
 * @author    Firebear Studio <fbeardev@gmail.com>
 */
define(
    [
        'jquery',
        'underscore',
        'mage/template',
        'mage/translate',
        'Magento_Catalog/js/price-utils',
        'priceBox',
        'jqueryHistory',
        'domReady!'
    ], function ($,_, mageTemplate, $t, priceUtils) {
        'use strict';

        /* jQuery load event */
        $(document).ready(function () {
            localStorage.setItem('processed', '');
        });

        /**
         *
         */
        var icpSwatchMixin = {

            /**
             * Initialize tax configuration, initial settings, and options values.
             * @returns {*}
             * @private
             */
            _init: function () {
                var element;
                element = $(this.options.selectorProductPrice);
                if (!element.data('magePriceBox')
                    || !element.is(':data(mage-priceBox)')
                ) {
                    element.priceBox();
                }
                return this._super();
            },

            /**
             * Get default options values settings with either URL query parameters
             * @private
             */
            _getSelectedAttributes: function () {

                if (typeof this.options.jsonConfig.defaultValues !== 'undefined') {
                    return this.options.jsonConfig.defaultValues;
                }
                return this._super();
            },

            /**
             * Render controls
             *
             * @private
             */
            _RenderControls: function () {
                $('.description').append('<p class="firebear_custom_block1"></p><p class="firebear_custom_block2"></p><p class="firebear_custom_block3"></p><p class="firebear_custom_block4"></p>');
                this._super();
            },

            /**
             *
             * @param selectedAttributes
             * @param $widget
             * @private
             */
            _EmulateSelected: function (selectedAttributes, $widget) {
                if (typeof $widget === "undefined") {
                    $widget = this;
                }
                var countSelectedAttributes = Object.keys(selectedAttributes).length;
                var attributeNumber = 1;
                $.each(selectedAttributes, $.proxy(function (attributeCode, optionId) {
                    var elem = this.element.find('.' + this.options.classes.attributeClass +
                        '[attribute-code="' + attributeCode + '"] [' + $widget.options.jsonConfig.attribute_prefix + 'option-id="' + optionId + '"]');
                    /*some websites use attribute-id instead of attribute-code*/
                    if (elem.length == 0) {
                        elem = this.element.find('.' + this.options.classes.attributeClass +
                            '[' + $widget.options.jsonConfig.attribute_prefix + 'attribute-id="' + attributeCode + '"] [' + $widget.options.jsonConfig.attribute_prefix + 'option-id="' + optionId + '"]');
                    }
                    var parentInput = elem.parent();

                    if (elem.hasClass('selected')) {
                        if (attributeNumber === countSelectedAttributes) {
                            $widget['update_price'] = true;
                        } else {
                            attributeNumber++;
                        }
                        return;
                    }
                    $widget['update_price'] = !(attributeNumber !== countSelectedAttributes && attributeCode !== "");
                    /*if swatch select option, use trigger change instead of click*/
                    if (parentInput.hasClass(this.options.classes.selectClass)) {
                        parentInput.val(optionId);
                        parentInput.trigger('change');
                    } else {
                        elem.trigger('click');
                    }
                    attributeNumber++;
                }, this));
            },

            /**
             * Change product attributes.
             * @param {Number} simpleProductId
             * @param {Object} $widget
             * @private
             */
            _ReplaceData: function (simpleProductId, $widget) {
                if (typeof $widget.options.jsonConfig.customAttributes[simpleProductId] !== 'undefined') {
                    $.each($widget.options.jsonConfig.customAttributes[simpleProductId], function (attributeCode, data) {
                        var $block = $(data.class);

                        if (typeof data.replace != 'undefined' && data.replace) {
                            if (data.value == '') {
                                $block.remove();
                            }

                            if ($block.length > 0 &&
                                attributeCode !== 'custom_1' &&
                                attributeCode !== 'custom_2' &&
                                attributeCode !== 'custom_3'
                            ) {
                                $block.replaceWith(data.value);
                            } else {
                                $(data.container).html(data.value);
                            }
                        } else {
                            if (attributeCode == 'custom_1' || attributeCode == 'custom_2' || attributeCode == 'custom_3') {
                                if ($('.custom_block_' + attributeCode).length == 0) {
                                    $block.append('<br><span class="custom_block_' + attributeCode + '"></span>');
                                }
                                var customBlock = $('.custom_block_' + attributeCode);
                                customBlock.html(data.value)
                            } else if (attributeCode == 'left_in_stock') {
                                if ($('.left_in_stock').length == 0) {
                                    $block.append('<p class="left_in_stock"> Left in stock: ' + data.value + '</p>');
                                } else {
                                    $('.left_in_stock').html('<p class="left_in_stock"> Left in stock: ' + data.value + '</p>');
                                }
                            } else if ($block.length > 0) {
                                $block.html(data.value);
                            }
                        }
                    });
                }
                var deliveryDateBlock = '.delivery_cpi_custom_block';
                if ($widget.options.jsonConfig.deliveryDate) {
                    var today = new Date();
                    today.setHours(0);
                    today.setMinutes(0);
                    today.setSeconds(0);
                    today.setMilliseconds(0);
                    today.setMicroseconds(0);
                    var todatString = today.toString();
                    today = today.valueOf();
                    if ($(deliveryDateBlock).length == 0) {
                        $($widget.options.jsonConfig.deliveryDate.block).append('<span class="delivery_cpi_custom_block"></span>');
                    }
                    if ($widget.options.jsonConfig.deliveryDate[simpleProductId]) {
                        if (!$widget.options.jsonConfig.deliveryDate[simpleProductId].startdate) {
                            var startdateT = todatString;
                        } else {
                            var startdateT = $widget.options.jsonConfig.deliveryDate[simpleProductId].startdate;
                        }
                        if ($widget.options.jsonConfig.deliveryDate[simpleProductId].enddate) {
                            var startdate = new Date(startdateT.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1')).valueOf(),
                                enddateT = $widget.options.jsonConfig.deliveryDate[simpleProductId].enddate,
                                enddate = new Date(enddateT.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1')).valueOf();
                            if (startdate <= today && enddate >= today) {
                                $(deliveryDateBlock).html('<br><span>' + $widget.options.jsonConfig.deliveryDate[simpleProductId].text + '</span>');
                            }
                        }
                    } else {
                        if ($widget.options.jsonConfig.deliveryDate.parent) {
                            if (!$widget.options.jsonConfig.deliveryDate.parent.startdate) {
                                var startdateT = todatString;
                            } else {
                                var startdateT = $widget.options.jsonConfig.deliveryDate.parent.startdate;
                            }
                            var startdate = new Date(startdateT.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1')).valueOf(),
                                enddateT = $widget.options.jsonConfig.deliveryDate.parent.enddate;
                            if (enddateT) {
                                var enddate = new Date(enddateT.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1')).valueOf();
                                if (startdate <= today && enddate >= today) {
                                    $(deliveryDateBlock).html('<br><span>' + $widget.options.jsonConfig.deliveryDate.parent.text + '</span>');

                                }
                            }
                        } else {
                            if ($(deliveryDateBlock).length != 0) {
                                $(deliveryDateBlock).remove();
                            }
                        }
                    }
                }
            },

            /**
             * Change parent product attributes.
             * @param {Object} $widget
             * @private
             */
            _ReplaceDataParent: function ($widget) {
                if ($widget.options.jsonConfig.parentProductName) {
                    var parentProductName = $widget.options.jsonConfig.parentProductName;
                    History.replaceState(null, parentProductName, $widget.options.jsonConfig.urls['parent']);
                }
                if (typeof $widget.options.jsonConfig.customAttributes.parent !== 'undefined') {
                    $.each($widget.options.jsonConfig.customAttributes.parent, function (attributeCode, data) {
                        var $block = $(data.class);

                        if (typeof data.replace != 'undefined' && data.replace) {
                            if (data.value == '') {
                                $block.remove();
                            }

                            if ($block.length > 0) {
                                $block.replaceWith(data.value);
                            } else {
                                $(data.container).html(data.value);
                            }
                        } else {
                            if (attributeCode == 'custom_1' || attributeCode == 'custom_2' || attributeCode == 'custom_3') {
                                if ($('.custom_block_' + attributeCode).length == 0) {
                                    $block.append('<br><span class="custom_block_' + attributeCode + '"></span>');
                                }
                                var customBlock = $('.custom_block_' + attributeCode);
                                customBlock.html(data.value)
                            } else if ($block.length > 0) {
                                $block.html(data.value);
                            }
                        }
                    });
                }
            },

            /**
             * Update [gallery-placeholder] or [product-image-photo]
             * @param {Array} images
             * @param {jQuery} context
             * @param {Boolean} isInProductView
             */
            updateBaseImage: function (images, context, isInProductView) {
                var justAnImage = images[0],
                    initialImages = this.options.mediaGalleryInitial,
                    imagesToUpdate,
                    imageObj = this,
                    isInitial;

                if (isInProductView) {
                    imagesToUpdate = images.length ? this._setImageType($.extend(true, [], images)) : [];
                    isInitial = _.isEqual(imagesToUpdate, initialImages);
                    var interval = window.setInterval(function () {
                        imagesToUpdate = images.length ? imageObj._setImageType($.extend(true, [], images)) : [];
                        isInitial = _.isEqual(imagesToUpdate, initialImages);
                        var gallery = context.find(imageObj.options.mediaGallerySelector).data('gallery');
                        if (gallery) {
                            gallery.updateData(imagesToUpdate);
                            if (isInitial) {
                                $(imageObj.options.mediaGallerySelector).AddFotoramaVideoEvents();
                            } else {
                                $(imageObj.options.mediaGallerySelector).AddFotoramaVideoEvents({
                                    selectedOption: imageObj.getProduct(),
                                    dataMergeStrategy: imageObj.options.gallerySwitchStrategy
                                });
                            }
                            clearInterval(interval);
                            gallery.first();
                        }
                    }, 200);
                } else if (justAnImage && justAnImage.img) {
                    context.find('.product-image-photo').attr('src', justAnImage.img);
                }
            },

            /**
             * Event for swatch options
             *
             * @param {Object} $this
             * @param {Object} $widget
             * @private
             */
            _OnClick: function ($this, $widget) {
                /* Fix issue cannot add product to cart */
                var $parent = $this.parents('.' + $widget.options.classes.attributeClass),
                    $wrapper = $this.parents('.' + $widget.options.classes.attributeOptionsWrapper),
                    $label = $parent.find('.' + $widget.options.classes.attributeSelectedOptionLabelClass),
                    attributeId = $parent.attr($widget.options.jsonConfig.attribute_prefix + 'attribute-id'),
                    updatePrice = true,
                    $input = $parent.find('.' + $widget.options.classes.attributeInput);
                if ($widget.inProductList) {
                    $input = $widget.productForm.find(
                        '.' + $widget.options.classes.attributeInput + '[name="super_attribute[' + attributeId + ']"]'
                    );
                }

                if ($this.hasClass('disabled')) {
                    return;
                }

                if (typeof ($widget.update_price) !== 'undefined') {
                    updatePrice = $widget.update_price;
                }

                if ($this.hasClass('selected')) {
                    if (typeof $widget.options.jsonConfig.allow_deselect_swatch == 'undefined')
                        return;
                    $parent.removeAttr($widget.options.jsonConfig.attribute_prefix + 'option-selected').find('.selected').removeClass('selected');
                    $input.val('');
                    $label.text('');
                    $this.attr('aria-checked', false);
                } else {
                    $parent.attr($widget.options.jsonConfig.attribute_prefix + 'option-selected', $this.attr($widget.options.jsonConfig.attribute_prefix + 'option-id')).find('.selected').removeClass('selected');
                    $label.text($this.attr('option-label'));
                    $input.val($this.attr($widget.options.jsonConfig.attribute_prefix + 'option-id'));
                    $input.attr('data-attr-name', this._getAttributeCodeById(attributeId));
                    $this.addClass('selected');
                    if (typeof $widget._toggleCheckedAttributes !== "undefined") {
                        $widget._toggleCheckedAttributes($this, $wrapper);
                    }
                }
                /**
                 * Get simpleProduct Id by simple product URL
                 */
                var currentURL = window.location.href;
                var simpleProductId = '';
                if (!localStorage.getItem('processed')) {
                    var selectedOptionId = '', selectedLabel = '';
                    if (typeof this.options.jsonConfig.urls !== 'undefined') {
                        $.each(this.options.jsonConfig.urls, function (productId, productUrl) {
                            if (productUrl == currentURL) {
                                simpleProductId = productId;
                                return true;
                            }
                        });
                    }
                    if (simpleProductId) {
                        $.each(this.options.jsonConfig.attributes, function () {
                            var item = this;
                            var allOptions = item.options;
                            $.each(allOptions, function (key, optionObj) {
                                var products = optionObj.products;
                                for (var i = 0; i < products.length; i++) {
                                    var childProductId = optionObj.products[i];
                                    if (simpleProductId === childProductId) {
                                        selectedOptionId = optionObj.id;
                                        selectedLabel = optionObj.label;
                                        var select = $('div[' + $widget.options.jsonConfig.attribute_prefix + 'attribute-id="' + item.id + '"]').find('select');
                                        if (select.find('option').length > 0) {
                                            select.val(selectedOptionId).trigger('change');
                                        } else {
                                            var parent = $('div[' + $widget.options.jsonConfig.attribute_prefix + 'attribute-id="' + item.id + '"]'),
                                                label = parent.find('.' + $widget.options.classes.attributeSelectedOptionLabelClass),
                                                input = parent.find('.' + $widget.options.classes.attributeInput);
                                            parent.removeAttr($widget.options.jsonConfig.attribute_prefix + 'option-selected').find('.selected').removeClass('selected');
                                            parent.attr($widget.options.jsonConfig.attribute_prefix + 'option-selected', selectedOptionId);
                                            label.text(selectedLabel);
                                            $('input[name="super_attribute[' + item.id + ']"]').val(selectedOptionId);
                                            $('.swatch-option[' + $widget.options.jsonConfig.attribute_prefix + 'option-id=' + selectedOptionId + ']').addClass('selected');
                                        }
                                    }
                                }
                            });
                        });
                    }
                }

                $widget._Rebuild();
                if (typeof ($widget.customOptionsPrice) !== 'undefined') {
                    delete $widget.customOptionsPrice;
                }

                if ($widget.element.parents($widget.options.selectorProduct)
                    .find(this.options.selectorProductPrice).is(':data(mage-priceBox)')
                ) {
                    if (updatePrice) {
                        $widget._UpdatePrice();
                    }
                } else {
                    if ($widget.inProductList) {
                        $widget._UpdatePriceCategory();
                        $widget._updateLink();
                    }
                }


                /**
                 * Update product data & url.
                 * @author Firebear Studio <fbeardev@gmail.com>
                 */
                var products = $widget._CalcProducts();
                if (products.length == 0) {
                    var options = {};
                    $widget.element.find('.' + $widget.options.classes.attributeClass + '[' + $widget.options.jsonConfig.attribute_prefix + 'option-selected]').each(function () {
                        var attributeId = $(this).attr($widget.options.jsonConfig.attribute_prefix + 'attribute-id');
                        options[attributeId] = $(this).attr($widget.options.jsonConfig.attribute_prefix + 'option-selected');
                    });
                    var result = _.findKey($widget.options.jsonConfig.index, options);
                    products = [result];
                }
                this._ChangeFromToPrice(attributeId, $this.val(), $widget);
                var numberOfSelectedOptions = $widget.element.find('.' + $widget.options.classes.attributeClass + '[' + $widget.options.jsonConfig.attribute_prefix + 'option-selected]').length;
                /**
                 * Do not replace data on category view page.
                 * @see \Firebear\ConfigurableProducts\Plugin\Block\ConfigurableProduct\Product\View\Type::afterGetJsonConfig()
                 */
                if (products.length == 1 && !this.options.jsonConfig.doNotReplaceData && numberOfSelectedOptions) {
                    if (!simpleProductId || !$.isNumeric(simpleProductId)) {
                        var simpleProductId = products[0];
                    }
                    try {
                        this._setOpenGraph(simpleProductId, $widget);
                    } catch (e) {
                        console.exception(e);
                    }
                    if ($.isNumeric(simpleProductId) && $widget.options.jsonConfig.useCustomOptionsForVariations == 1) {
                        this._RenderCustomOptionsBySimpleProduct(simpleProductId, $widget);
                    }
                    /**
                     * Change product attributes.
                     */
                    $widget._ReplaceData(simpleProductId, this);
                    /* Update input type hidden - fix base image doesn't change when choose option */
                    // if (simpleProductId && document.getElementsByName('product').length) {
                    // document.getElementsByName('product')[0].value = simpleProductId;
                    // }
                    /**/
                    var config = this.options.jsonConfig;
                    if (typeof config.urls !== 'undefined' && typeof config.urls[simpleProductId] !== 'undefined') {
                        var url = config.urls[simpleProductId];
                        var title = $(document).find('title').text();
                        if (url) {
                            if (typeof config.customAttributes[simpleProductId].name == 'undefined') {
                                if ((config.customAttributes[simpleProductId]['.breadcrumbs .items .product'])) {
                                    title = config.customAttributes[simpleProductId]['.breadcrumbs .items .product'].value;
                                }
                            } else {
                                if (config.customAttributes[simpleProductId].name.value !== 'undefined') {
                                    title = config.customAttributes[simpleProductId].name.value;
                                }
                            }
                            History.replaceState(null, title, url);
                        }
                    }
                } else {
                    var configurableProductId = this.options.jsonConfig.productId;
                    if (configurableProductId) {
                        this._setOpenGraph(configurableProductId, $widget);
                    }
                    if ($widget.element.parents($widget.options.selectorProduct)
                        .find(this.options.selectorProductPrice).is(':data(mage-priceBox)')
                    ) {
                        $widget._ReplaceDataParent(this);
                        // document.getElementsByName('product')[0].value = simpleProductId;
                    }
                    $('.left_in_stock').remove();
                }
                $widget._loadMedia();
                $input.trigger('change');
                localStorage.setItem('processed', true);
            },

            /**
             * Event for select
             *
             * @param {Object} $this
             * @param {Object} $widget
             * @private
             */
            _OnChange: function ($this, $widget) {
                /* Fix issue cannot add product to cart */
                var $parent = $this.parents('.' + $widget.options.classes.attributeClass),
                    attributeId = $parent.attr($widget.options.jsonConfig.attribute_prefix + 'attribute-id'),
                    updatePrice = true,
                    $input = $parent.find('.' + $widget.options.classes.attributeInput);
                if ($widget.productForm.length > 0) {
                    $input = $widget.productForm.find(
                        '.' + $widget.options.classes.attributeInput + '[name="super_attribute[' + attributeId + ']"]'
                    );
                }
                /**/

                if (typeof ($widget.update_price) !== 'undefined') {
                    updatePrice = $widget.update_price;
                }
                if ($this.val() > 0) {
                    $parent.attr($widget.options.jsonConfig.attribute_prefix + 'option-selected', $this.val());
                    $input.val($this.val());
                } else {
                    $parent.removeAttr($widget.options.jsonConfig.attribute_prefix + 'option-selected');
                    $input.val('');
                }
                if (typeof ($widget.customOptionsPrice) !== 'undefined') {
                    delete $widget.customOptionsPrice;
                }

                $widget._Rebuild();
                if (updatePrice) {
                    $widget._UpdatePrice();
                }

                /**
                 * Update product data & url.
                 * @author Firebear Studio <fbeardev@gmail.com>
                 */
                var products = $widget._CalcProducts();
                var options = {};
                if (products.length == 0) {
                    $widget.element.find('.' + $widget.options.classes.attributeClass + '[' + $widget.options.jsonConfig.attribute_prefix + 'option-selected]').each(function () {
                        var attributeId = $(this).attr('data-attribute-id');

                        options[attributeId] = $(this).attr($widget.options.jsonConfig.attribute_prefix + 'option-selected');
                    });

                    var result = _.findKey($widget.options.jsonConfig.index, options);
                    products = [result];
                }
                /**
                 * Change From Price for normal swatch
                 */
                this._ChangeFromToPrice(attributeId, $this.val(), $widget);
                var numberOfSelectedOptions = $widget.element.find('.' + $widget.options.classes.attributeClass + '[' + $widget.options.jsonConfig.attribute_prefix + 'option-selected]').length;
                /**
                 * Do not replace data on category view page.
                 * @see \Firebear\ConfigurableProducts\Plugin\Block\ConfigurableProduct\Product\View\Type::afterGetJsonConfig()
                 */
                if (products.length == 1 && !this.options.jsonConfig.doNotReplaceData && numberOfSelectedOptions) {
                    var simpleProductId = products[0];
                    if ($.isNumeric(simpleProductId) && $widget.options.jsonConfig.useCustomOptionsForVariations == 1) {
                        this._RenderCustomOptionsBySimpleProduct(simpleProductId, $widget);
                    }
                    /**
                     * Change product attributes.
                     */
                    $widget._ReplaceData(simpleProductId, this);
                    /**
                     * Update input type hidden - fix base image doesn't change when choose option
                     */
                        // if (simpleProductId && document.getElementsByName('product').length) {
                        // document.getElementsByName('product')[0].value = simpleProductId;
                        // }
                    var config = this.options.jsonConfig;
                    if (typeof config.urls !== 'undefined' && typeof config.urls[simpleProductId] !== 'undefined') {
                        var url = config.urls[simpleProductId];
                        var title = $(document).find('title').text();
                        if (url) {
                            if (typeof config.customAttributes[simpleProductId].name == 'undefined') {
                                if (config.customAttributes[simpleProductId]['.breadcrumbs .items .product']) {
                                    title = config.customAttributes[simpleProductId]['.breadcrumbs .items .product'].value;
                                }
                            } else {
                                if (config.customAttributes[simpleProductId].name.value !== 'undefined') {
                                    title = config.customAttributes[simpleProductId].name.value;
                                }
                            }
                            History.replaceState(null, title, url);
                        }
                    }
                } else {
                    var configurableProductId = this.options.jsonConfig.productId;
                    if (configurableProductId) {
                        this._setOpenGraph(configurableProductId, $widget);
                    }
                    if (!$widget.element.parents($widget.options.selectorProduct)
                        .find(this.options.selectorProductPrice).is(':data(mage-priceBox)')
                    ) {
                        $widget._ReplaceDataParent(this);
                        document.getElementsByName('product')[0].value = '';
                    }
                }
                $widget._loadMedia();
                $input.trigger('change');
            },

            /**
             * Get human readable attribute code (eg. size, color) by it ID from configuration
             *
             * @param {Number} attributeId
             * @returns {*}
             * @private
             */
            _getAttributeCodeById: function (attributeId) {
                var attribute = this.options.jsonConfig.attributes[attributeId];

                return attribute ? attribute.code : attributeId;
            },

            /**
             * Update total price in category
             *
             * @private
             */
            _UpdatePriceCategory: function () {
                var $widget = this,
                    $product = $widget.element.parents($widget.options.selectorProduct),
                    $productPrice = $product.find(this.options.selectorProductPrice),
                    options = _.object(_.keys($widget.optionsMap), {}),
                    result,
                    formatedPrice;

                if ($widget.options.jsonConfig.hidePrice) {
                    return;
                }
                $widget.element.find('.' + $widget.options.classes.attributeClass + '[' + $widget.options.jsonConfig.attribute_prefix + 'option-selected]').each(function () {
                    var attributeId = $(this).attr('data-attribute-id');

                    options[attributeId] = $(this).attr($widget.options.jsonConfig.attribute_prefix + 'option-selected');
                });

                result = $widget.options.jsonConfig.optionPrices[_.findKey($widget.options.jsonConfig.index, options)];
                if (result) {
                    formatedPrice = priceUtils.formatPrice(result.finalPrice.amount, {
                        "pattern": $widget.options.jsonConfig.currencyFormat,
                        "precision": 2,
                        "requiredPrecision": 2,
                        "decimalSymbol": ".",
                        "groupSymbol": ",",
                        "groupLength": 3,
                        "integerRequired": 1
                    });
                    if ($widget.options.jsonConfig.defaultPriceWithRange && $widget.options.jsonConfig.priceRange) {
                        if (typeof $($product.find('.price-range')).html() !== 'undefined') {
                            if (-1 < $($product.find('.price-range')).html().indexOf('From')) {
                                if ($widget.options.jsonConfig.disaplyingFromToPrice) {
                                    $($product.find('.price')[2]).html(formatedPrice);
                                } else {
                                    $($product.find('.price')[1]).html(formatedPrice);
                                }
                            } else {
                                $($product.find('.price')[0]).html(formatedPrice);
                            }
                        }
                    } else {
                        if (!$widget.options.jsonConfig.priceRange) {
                            $productPrice.html('<span class="price">' + formatedPrice + '</span>');
                        }
                    }
                    $productPrice.trigger(
                        'updatePrice',
                        {
                            'prices': $widget._getPrices(result, $productPrice)
                        }
                    );
                }


                if (typeof result !== 'undefined' && result.oldPrice.amount !== result.finalPrice.amount) {
                    $(this.options.slyOldPriceSelector).show();
                } else {
                    $(this.options.slyOldPriceSelector).hide();
                }
            },

            /**
             *
             * @private
             */
            _updateLink: function () {
                var $widget = this,
                    $product = $widget.element.parents($widget.options.selectorProduct),
                    $imageBlock = $product.parents('.product-item-info'),
                    $productPrice = $product.find(this.options.selectorProductPrice),
                    options = _.object(_.keys($widget.optionsMap), {}),
                    result;
                var deselectAll = true;
                $widget.element.find('.' + $widget.options.classes.attributeClass + '[' + $widget.options.jsonConfig.attribute_prefix + 'option-selected]').each(function () {
                    var attributeId = $(this).attr($widget.options.jsonConfig.attribute_prefix + 'attribute-id');
                    options[attributeId] = $(this).attr($widget.options.jsonConfig.attribute_prefix + 'option-selected');
                    if ($(this).attr($widget.options.jsonConfig.attribute_prefix + 'option-selected'))
                        deselectAll = false;
                });
                var linkSelectedOption = $widget.options.jsonConfig.urls[_.findKey($widget.options.jsonConfig.index, options)];
                if (_.findKey($widget.options.jsonConfig.index, options)) {
                    if (linkSelectedOption) {
                        if (typeof $widget.options.jsonConfig.customAttributes[_.findKey($widget.options.jsonConfig.index, options)].name == 'undefined') {
                            $product.find('.product-item-link').attr('href', linkSelectedOption).html($widget.options.jsonConfig.customAttributes[_.findKey($widget.options.jsonConfig.index, options)]['.breadcrumbs .items .product'].value);
                            $imageBlock.find('.product-item-photo').attr('href', linkSelectedOption);
                        } else {
                            $product.find('.product-item-link').attr('href', linkSelectedOption).html($widget.options.jsonConfig.customAttributes[_.findKey($widget.options.jsonConfig.index, options)].name.value);
                            $imageBlock.find('.product-item-photo').attr('href', linkSelectedOption);
                        }
                    }
                }
                if (deselectAll) {
                    linkSelectedOption = $widget.options.jsonConfig.customAttributes.parent.parent_link;
                    $product.find('.product-item-link').attr('href', linkSelectedOption).html($widget.options.jsonConfig.customAttributes.parent.name.value);
                    $imageBlock.find('.product-item-photo').attr('href', linkSelectedOption);
                }
                $($product.prev('.product-item-photo')).find('a').attr('href', linkSelectedOption);
            },

            /**
             *
             * @param {Number} attributeId
             * @param {Number} optionId
             * @param {Object} $widget
             * @private
             */
            _ChangeFromToPrice: function (attributeId, optionId, $widget) {
                var productsIds = [];
                var childProductIds = [];
                var productOptions = 0;
                var allSelectedOptionPrices = [];
                var minPrice = 0;
                var maxPrice = 0;
                var iteratorForCheckMinPrice = 0;
                var selectedAttributesForProducts = [];
                var iteratorForProductIds = 0;

                if ($widget.options.jsonConfig.hidePrice) {
                    return;
                }
                $.each($widget.options.jsonConfig.attributes, function ($key, $item) {
                    if ($('.' + $item.code + ' option:selected').val() != 0 && $item.id != attributeId && $item.type == 'select') {
                        selectedAttributesForProducts[$item.id] = parseInt($('.' + $item.code + ' option:selected').val());
                    } else if (typeof ($('.' + $item.code).attr($widget.options.jsonConfig.attribute_prefix + 'option-selected')) !== 'undefined') {
                        selectedAttributesForProducts[$item.id] = parseInt($('.' + $item.code).attr($widget.options.jsonConfig.attribute_prefix + 'option-selected'));
                    }
                });
                if (typeof $widget.options.jsonSwatchConfig !== 'undefined') {
                    $.each($widget.options.jsonConfig.mappedAttributes[attributeId].options, function ($key, $item) {
                        if (optionId == 0) {
                            productOptions = 1;
                            // childProductIds = productsIds.concat($item.products);
                            childProductIds.push($item.products);
                        } else {
                            if ($item.id == optionId) {
                                productsIds[iteratorForProductIds] = $item.products;
                                iteratorForProductIds++;
                            }
                        }
                    });
                    if (selectedAttributesForProducts.length != 0) {
                        selectedAttributesForProducts.forEach(function (item, i, selectedAttributesForProducts) {
                            $.each($widget.options.jsonConfig.mappedAttributes[i].options, function ($key, $item) {
                                if ($item.id == item) {
                                    productsIds[iteratorForProductIds] = $item.products;
                                    iteratorForProductIds++;
                                }
                            });
                        });
                        /*$.each(selectedAttributesForProducts, function ($key, $item) {

                        });*/
                    }
                }
                if (productsIds.length != 0) {
                    $.each(productsIds, function ($key, $item) {
                        $.each($item, function ($id, $val) {
                            if ($widget.options.jsonConfig.optionPrices[$val].finalPrice.amount != 0) {
                                allSelectedOptionPrices[iteratorForCheckMinPrice] = $widget.options.jsonConfig.optionPrices[$val].finalPrice.amount;
                                iteratorForCheckMinPrice++;
                            }
                            if (typeof ($widget.options.jsonConfig.considerTierPricesInFromToPrice) !== "undefined" &&
                                $widget.options.jsonConfig.considerTierPricesInFromToPrice == '1') {
                                $.each($widget.options.jsonConfig.optionPrices[$val].tierPrices, function () {
                                    allSelectedOptionPrices[iteratorForCheckMinPrice] = this.price;
                                    iteratorForCheckMinPrice++;
                                });
                            }
                        });
                    });

                    minPrice = Math.min.apply(Math, allSelectedOptionPrices);
                    maxPrice = Math.max.apply(Math, allSelectedOptionPrices);

                    if (minPrice == maxPrice) {
                        if ($widget.inProductList) {
                            $('.firebear_range_price, .firebear_range_price_' + $widget.options.jsonConfig.productId).html(
                                '<span class="price">From ' + $widget.getFormattedPrice(minPrice, $widget) + '</span>'
                            );
                        } else {
                            $('.firebear_range_price, .firebear_range_price_' + $widget.options.jsonConfig.productId).html(
                                'From ' + $widget.getFormattedPrice(minPrice, $widget)
                            );
                        }
                    } else {
                        if ($widget.inProductList) {
                            $('.firebear_range_price, .firebear_range_price_' + $widget.options.jsonConfig.productId).html(
                                '<span class="price">From ' + $widget.getFormattedPrice(minPrice, $widget) +
                                ' - ' + $widget.getFormattedPrice(maxPrice, $widget) + '</span>');
                        } else {
                            $('.firebear_range_price, .firebear_range_price_' + $widget.options.jsonConfig.productId).html(
                                'From ' + $widget.getFormattedPrice(minPrice, $widget) +
                                ' - ' + $widget.getFormattedPrice(maxPrice, $widget)
                            );
                        }
                    }
                } else {
                    $('.firebear_range_price').html($('.price').html);
                }
            },

            /**
             *
             * @param price
             * @param $widget
             * @returns {String}
             */
            getFormattedPrice: function (price, $widget) {
                //todo add format data
                return priceUtils.formatPrice(price, $widget.options.jsonConfig.priceFormat);
            },

            /**
             *
             * @param productId
             * @param $widget
             * @private
             */
            _RenderCustomOptionsBySimpleProduct: function (productId, $widget) {
                $.ajax({
                    url: $widget.options.jsonConfig.loadOptionsUrl,
                    type: 'POST',
                    dataType: 'json',
                    showLoader: true,
                    data: {
                        productId: productId
                    },
                    success: function (response, widget) {

                        if (!$('.product-options-wrapper .product-cpi-custom-options').html()) {
                            $('.product-options-wrapper').append('<div class="product-cpi-custom-options"></div>');
                            $('.product-cpi-custom-options').html('<div class="fieldset" tabindex="0">' + response.optionsHtml + '</div>');
                        } else {
                            $('.product-cpi-custom-options').html('<div class="fieldset" tabindex="0">' + response.optionsHtml + '</div>');
                        }
                        $('.product-custom-option').on('change', function () {
                            var customOptionsPrice = [];

                            function getSum(total, num) {
                                return total + num;
                            }

                            $('.product-custom-option').each(function (key, el) {
                                var elementType = el.nodeName;
                                var elementId = parseInt(/[0-9]+/.exec(el.id));
                                switch (elementType) {
                                    case "INPUT":
                                        var inputType = $(el).attr('type');
                                        if (inputType == 'radio' || inputType == 'checkbox') {
                                            if (el.checked) {
                                                customOptionsPrice.push(parseFloat($(el).attr('price')));
                                            } else {
                                                customOptionsPrice.push(0);
                                            }
                                        } else {
                                            if (inputType == 'text' || inputType == 'file') {
                                                if (el.value) {
                                                    customOptionsPrice.push(parseFloat(response.optionsData[elementId]['price']));
                                                } else {
                                                    customOptionsPrice.push(0);
                                                }
                                            }
                                        }
                                        break;
                                    case "SELECT":
                                        if (el.multiple) {
                                            $(el).find(":selected").each(function (index, selected) {
                                                customOptionsPrice.push(parseFloat($(selected).attr('price')));
                                            });
                                            break;
                                        } else {
                                            var singleSelectPrice = $(el).find(":selected").attr('price');
                                            if (typeof (singleSelectPrice) !== 'undefined') {
                                                customOptionsPrice.push(parseFloat(singleSelectPrice));
                                            }
                                            break;
                                        }
                                    case "TEXTAREA":
                                        if (el.value) {
                                            customOptionsPrice.push(parseFloat(response.optionsData[elementId]['price']));
                                        } else {
                                            customOptionsPrice.push(0);
                                        }
                                }
                            });
                            $('.field.date').each(function () {
                                var allDateValues = [];
                                $(this).find("select").each(function (key, el) {
                                    allDateValues.push(el.value);
                                });
                                var elementId = parseInt(/[0-9]+/.exec($(this).find("select")[0]['id']));
                                var checkOptionValues = allDateValues.every(function (element, index, array) {
                                    return element !== "";
                                });
                                if (!checkOptionValues) {
                                    customOptionsPrice.push(parseFloat(0));
                                } else {
                                    customOptionsPrice.push(parseFloat(response.optionsData[elementId]['price']));
                                }
                            });
                            if (customOptionsPrice.length > 0) {
                                $widget['customOptionsPrice'] = customOptionsPrice.reduce(getSum);
                            }
                            $widget._UpdatePrice();
                        });
                    }
                });
            },

            /**
             *
             * @param productId
             * @param $widget
             * @public
             */
            _setOpenGraph: function (productId, $widget) {
                $.ajax({
                    url: $widget.options.jsonConfig.setOpenGraphUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        productId: productId,
                    },
                    success: function (response) {
                        var property;
                        $.each($(response.openGraphHtml), function () {
                            property = $(this).attr('property');
                            if (property) {
                                $('meta[property="' + property + '"]').remove();
                            }

                        });
                        $('head').append(response.openGraphHtml);
                    }
                });
            },

            /**
             * Update total price in product page
             *
             * @private
             */
            _UpdatePrice: function () {
                var $widget = this,
                    $product = $widget.element.parents($widget.options.selectorProduct),
                    $productPrice = $product.find(this.options.selectorProductPrice),
                    options = _.object(_.keys($widget.optionsMap), {}),
                    result,
                    tierPriceHtml;

                $widget.element.find('.' + $widget.options.classes.attributeClass + '[' + $widget.options.jsonConfig.attribute_prefix + 'option-selected]').each(function () {
                    var attributeId = $(this).attr('data-attribute-id');

                    options[attributeId] = $(this).attr($widget.options.jsonConfig.attribute_prefix + 'option-selected');
                });

                result = $widget.options.jsonConfig.optionPrices[_.findKey($widget.options.jsonConfig.index, options)];

                if (result) {
                    if (typeof ($widget['resultBefore_' + _.findKey($widget.options.jsonConfig.index, options)]) == 'undefined') {
                        $widget['resultBefore_' + _.findKey($widget.options.jsonConfig.index, options)] = JSON.parse(JSON.stringify(result));
                    } else {
                        result = null;
                        result = JSON.parse(JSON.stringify($widget['resultBefore_' + _.findKey($widget.options.jsonConfig.index, options)]));
                    }
                }
                if (typeof ($widget.customOptionsPrice) !== 'undefined') {
                    result.finalPrice.amount = result.finalPrice.amount + $widget.customOptionsPrice;
                }
                $productPrice.trigger(
                    'updatePrice',
                    {
                        'prices': $widget._getPrices(result, $productPrice.priceBox('option').prices)
                    }
                );

                if (typeof result !== 'undefined' && result.oldPrice.amount !== result.finalPrice.amount) {
                    $(this.options.slyOldPriceSelector).show();
                } else {
                    $(this.options.slyOldPriceSelector).hide();
                }
                if (typeof result != 'undefined' && result.tierPrices.length) {
                    if (this.options.tierPriceTemplate) {
                        tierPriceHtml = mageTemplate(
                            this.options.tierPriceTemplate,
                            {
                                'tierPrices': result.tierPrices,
                                '$t': $t,
                                'currencyFormat': this.options.jsonConfig.currencyFormat,
                                'priceUtils': priceUtils
                            }
                        );
                        $(this.options.tierPriceBlockSelector).html(tierPriceHtml).show();
                    }
                } else {
                    $(this.options.tierPriceBlockSelector).hide();
                }

                $(this.options.normalPriceLabelSelector).hide();
                _.each($('.' + this.options.classes.attributeOptionsWrapper), function (attribute) {
                    if ($(attribute).find('.' + this.options.classes.optionClass + '.selected').length === 0) {
                        if ($(attribute).find('.' + this.options.classes.selectClass).length > 0) {
                            _.each($(attribute).find('.' + this.options.classes.selectClass), function (dropdown) {
                                if ($(dropdown).val() === '0') {
                                    $(this.options.normalPriceLabelSelector).show();
                                }
                            }.bind(this));
                        } else {
                            $(this.options.normalPriceLabelSelector).show();
                        }
                    }
                }.bind(this));
            },
        };

        return function (SwatchWidget) {
            $.widget('firebear.swatch_icp', SwatchWidget, icpSwatchMixin);
            return $.firebear.swatch_icp;
        };
    });
