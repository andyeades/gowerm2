/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
define([
    'jquery',
    'underscore',
    'mage/translate',
    'mage/url',
    'Magento_Catalog/js/price-utils',
    'Magento_Swatches/js/swatch-renderer',
    'jqueryHistory'
], function ($, _, $translate, urlBuilder, priceUtils) {
    'use strict';

    /* jQuery load event */
    $(document).ready(function () {
        localStorage.setItem('processed', '');
    });

    $.widget('mage.SwatchRenderer', $.mage.SwatchRenderer, {

        _EventListener: function () {
            var $widget = this;

            $widget.element.on('click', '.' + this.options.classes.optionClass, function () {
                return $widget._OnClick($(this), $widget);
            });

            $widget.element.on('change', '.' + this.options.classes.selectClass, function () {
                return $widget._OnChange($(this), $widget);
            });

            $widget.element.on('click', '.' + this.options.classes.moreButton, function (e) {
                e.preventDefault();

                return $widget._OnMoreClick($(this));
            });

            $widget.element.on('change', '.matrix_qty', function () {
                return $widget._InputQtyValidate($(this), $widget);
            });

            $widget.element.on('click', '.inc_matrix_arrow', function (e) {
                return $widget._IncMatrixInput($(this), $widget);
            });
            $widget.element.on('click', '.load_more_matrix', function (e) {
                return $widget._loadMoreOptions($(this), $widget);
            });
            $widget.element.on('click', '.hide_more_matrix', function (e) {
                return $widget._hideMoreOptions($(this), $widget);
            });
            $widget.element.on('click', '.dec_matrix_arrow', function (e) {
                return $widget._DecMatrixInput($(this), $widget);
            });
        },

        /**
         * Get default options values settings with either URL query parameters
         * @private
         */
        _getSelectedAttributes: function () {

            if (typeof this.options.jsonConfig.defaultValues !== 'undefined') {
                return this.options.jsonConfig.defaultValues;
            }

            var hashIndex = window.location.href.indexOf('#'),
                selectedAttributes = {},
                params;

            if (hashIndex !== -1) {
                params = $.parseQuery(window.location.href.substr(hashIndex + 1));

                selectedAttributes = _.invert(_.mapObject(_.invert(params), function (attributeId) {
                    var attribute = this.options.jsonConfig.attributes[attributeId];

                    return attribute ? attribute.code : attributeId;
                }.bind(this)));
            }

            return selectedAttributes;
        },

        _IncMatrixInput  : function ($this, $widget) {
            var currentValue = parseInt($this.parents()[0].children[1].value);
            $this.parents()[0].children[1].value = currentValue + 1;
        },
        _DecMatrixInput  : function ($this, $widget) {
            var currentValue = $this.parents()[0].children[1].value;
            if (currentValue == 0) {
                currentValue = 0;
            } else {
                currentValue = currentValue - 1;
            }
            $this.parents()[0].children[1].value = currentValue;
        },
        _InputQtyValidate: function ($this, $widget) {
            if ($this.parents()[0].children[1].value < 0) {
                $this.parents()[0].children[1].value = 0;
            }
        },

        /**
         * Emulate mouse click on all swatches that should be selected
         * @param {Object} [selectedAttributes]
         * @private
         */
        _EmulateSelected: function (selectedAttributes, $widget) {
            var countSelectedAttributes = Object.keys(selectedAttributes).length;
            var attributeNumber = 1;
            $.each(selectedAttributes, $.proxy(function (attributeCode, optionId) {
                var el = this.element.find('.' + this.options.classes.attributeClass +
                    '[attribute-code="' + attributeCode + '"] [option-id="' + optionId + '"]');
                /*some websites use attribute-id instead of attribute-code*/
                if (el.length == 0)
                    el = this.element.find('.' + this.options.classes.attributeClass +
                        '[attribute-id="' + attributeCode + '"] [option-id="' + optionId + '"]');
                if (el.hasClass('selected')) {
                    if (attributeNumber == countSelectedAttributes) {
                        $widget['update_price'] = true;
                    } else {
                        attributeNumber++;
                    }
                    $widget._UpdatePrice();
                    return;
                }
                if (attributeNumber !== countSelectedAttributes && attributeCode !== "") {
                    $widget['update_price'] = false;
                } else {
                    $widget['update_price'] = true;
                }
                /*if swatch select option, use trigger change instead of click*/
                if (el.parent('select').hasClass('swatch-select')) {
                    el.parent('select').val(optionId).trigger('change');
                } else {
                    el.trigger('click');
                }
                attributeNumber++;
            }, this));
        },

        /**
         * Change product attributes.
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
                        }
                        else if ($block.length > 0) {
                            if (attributeCode === 'short_description') {
                                $('#maincontent div.product.attribute.overview div').html('');
                                if ($('span.short_description').length == 0) {
                                    if (data.value) {
                                        $block.append('<span class="short_description">' + data.value + '</span>');
                                    }
                                } else {
                                    if (data.value) {
                                        $('span.short_description').html(data.value);
                                    }
                                }
                            } else if(attributeCode === 'description') {
                                $('.product.info.detailed .product.attribute.description div').html('');
                                if ($('span.description').length == 0) {
                                    if (data.value) {
                                        $block.append('<span class="description">' + data.value + '</span>');
                                    }
                                } else {
                                    if (data.value) {
                                        $('span.description').html(data.value);
                                    }
                                }
                            } else {
                                $block.html(data.value);
                            }
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
                    }
                    else {
                        if ($(deliveryDateBlock).length != 0) {
                            $(deliveryDateBlock).remove();
                        }
                    }
                }
            }
        },

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
                        }
                        else if ($block.length > 0) {
                            $block.html(data.value);
                        }
                    }
                });
            }
        },

        updateBaseImage : function (images, context, isInProductView) {
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
                                selectedOption   : imageObj.getProduct(),
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
         * @param $this
         * @param $widget
         * @private
         */
        _OnClick: function ($this, $widget) {
            /* Fix issue cannot add product to cart */
            var $parent = $this.parents('.' + $widget.options.classes.attributeClass),
                $wrapper = $this.parents('.' + $widget.options.classes.attributeOptionsWrapper),
                $label = $parent.find('.' + $widget.options.classes.attributeSelectedOptionLabelClass),
                attributeId = $parent.attr('attribute-id'),
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

            if (typeof($widget.update_price) !== 'undefined') {
                updatePrice = $widget.update_price;
            }

            if ($this.hasClass('selected')) {
                $this.removeClass('selected');
                $parent.removeAttr('option-selected').find('.selected').removeClass('selected');
                $input.val('');
                $label.text('');
                $this.attr('aria-checked', false);
            } else {
                $parent.attr('option-selected', $this.attr('option-id')).find('.selected').removeClass('selected');
                $label.text($this.attr('option-label'));
                $input.val($this.attr('option-id'));
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
            if ($widget.options.jsonConfig.y_matrix_axis.id !== attributeId && typeof (attributeId) !== 'undefined') {
                $widget._ChangeFromToPrice(attributeId, $this.attr('option-id'), $widget);
            }
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
                                    var select = $('div[attribute-id="' + item.id + '"]').find('select');
                                    if (select.find('option').length > 0) {
                                        select.val(selectedOptionId).trigger('change');
                                    } else {
                                        var parent = $('div[attribute-id="' + item.id + '"]'),
                                            label = parent.find('.' + $widget.options.classes.attributeSelectedOptionLabelClass),
                                            input = parent.find('.' + $widget.options.classes.attributeInput);
                                        parent.removeAttr('option-selected').find('.selected').removeClass('selected');
                                        parent.attr('option-selected', selectedOptionId);
                                        label.text(selectedLabel);
                                        $('input[name="super_attribute[' + item.id + ']"]').val(selectedOptionId);
                                        $('.swatch-option[option-id=' + selectedOptionId + ']').addClass('selected');
                                    }
                                }
                            }
                        });
                    });
                }
            }
            if ($widget.options.jsonConfig.attributes.length > 1) {
                $widget._Rebuild();
            }
            if ($widget.element.parents($widget.options.selectorProduct)
                .find(this.options.selectorProductPrice).is(':data(mage-priceBox)')
            ) {
                if (updatePrice) {
                    $widget._UpdatePrice();
                }
            }

            /**
             * Update product data & url.
             * @author Firebear Studio <fbeardev@gmail.com>
             */
            var products = $widget._CalcProducts();
            var numberOfSelectedOptions = $widget.element.find('.' + $widget.options.classes.attributeClass + '[option-selected]').length;

            /**
             * Do not replace data on category view page.
             * @see \Firebear\ConfigurableProducts\Plugin\Block\ConfigurableProduct\Product\View\Type::afterGetJsonConfig()
             */
            if (products.length && !this.options.jsonConfig.doNotReplaceData && numberOfSelectedOptions) {
                if (!simpleProductId || !$.isNumeric(simpleProductId)) {
                    var simpleProductId = products[0];
                }
                $('.product-cpi-custom-options').remove();
                if ($widget.options.jsonConfig.useCustomOptionsForVariations == 1) {
                    $.each(products, function (key, simpleProductId) {
                        if ($.isNumeric(simpleProductId)) {
                            $widget._RenderCustomOptionsBySimpleProduct(simpleProductId, $widget);
                        }
                    });
                }

                /**
                 * Change product attributes.
                 */
                $widget._ReplaceData(simpleProductId, this);
                /* Update input type hidden - fix base image doesn't change when choose option */
                // if (simpleProductId && document.getElementsByName('product').length) {
                //     document.getElementsByName('product')[0].value = simpleProductId;
                // }
                var config = this.options.jsonConfig;
                require(['jqueryHistory'], function () {
                    if (typeof config.urls !== 'undefined' && typeof config.urls[simpleProductId] !== 'undefined') {
                        var url = config.urls[simpleProductId];
                        var title = null;
                        if (url) {
                            if (typeof config.customAttributes[simpleProductId].name == 'undefined') {
                                if (config.customAttributes[simpleProductId]['.breadcrumbs .items .product']) {
                                    title = config.customAttributes[simpleProductId]['.breadcrumbs .items .product'].value;
                                }
                            }
                            else {
                                if (config.customAttributes[simpleProductId].name.value !== 'undefined') {
                                    title = config.customAttributes[simpleProductId].name.value;
                                }
                            }
                            History.replaceState(null, title, url);
                        }
                    }
                });
            } else {
                $widget._ReplaceDataParent(this);
            }
            if ($widget.options.jsonConfig.attributes.length > 1) {
                $widget._updateMatrix(attributeId);
            }
            $widget._loadMedia();
            $input.trigger('change');
            localStorage.setItem('processed', true);
            this._getSelectedAttributes();
        },

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

            $.each($widget.options.jsonConfig.attributes, function ($key, $item) {
                if ($('.'+$item.code+' option:selected').val() != 0 && $item.id != attributeId && $item.type == 'select') {
                    selectedAttributesForProducts[$item.id] = parseInt($('.' + $item.code + ' option:selected').val());
                } else if (typeof ($('.' + $item.code).attr('option-selected')) !== 'undefined') {
                    selectedAttributesForProducts[$item.id] = parseInt($('.' + $item.code).attr('option-selected'));
                }
            });
            $.each($widget.options.jsonConfig.mappedAttributes[attributeId].options, function ($key, $item) {
                if (optionId == 0) {
                    productOptions = 1;
                    // childProductIds = productsIds.concat($item.products);
                    childProductIds.push($item.products);
                } else {
                    // if ($item.id == optionId) {
                    //     productsIds[iteratorForProductIds] = $item.products;
                    //     iteratorForProductIds++;
                    // }
                }
            });
            if (selectedAttributesForProducts.length != 0) {
                selectedAttributesForProducts.forEach(function (item, i, selectedAttributesForProducts) {
                    if (i !== parseInt($widget.options.jsonConfig.y_matrix_axis.id)) {
                        $.each($widget.options.jsonConfig.mappedAttributes[i].options, function ($key, $item) {
                            if ($item.id == item) {
                                productsIds[iteratorForProductIds] = $item.products;
                                iteratorForProductIds++;
                            }
                        });
                    }
                });
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
                            $.each($widget.options.jsonConfig.optionPrices[$val].tierPrices, function() {
                                allSelectedOptionPrices[iteratorForCheckMinPrice] = this.price;
                                iteratorForCheckMinPrice++;
                            });
                        }

                    });
                });
                minPrice = Math.min.apply(Math, allSelectedOptionPrices);
                maxPrice = Math.max.apply(Math, allSelectedOptionPrices);
                if (minPrice == maxPrice) {
                    $('.firebear_range_price').html('From ' + $widget.getFormattedPrice(minPrice, $widget));
                } else {
                    $('.firebear_range_price').html('From ' + $widget.getFormattedPrice(minPrice, $widget) + ' - ' + $widget.getFormattedPrice(maxPrice, $widget));
                }
            } else {
                $('.firebear_range_price').html($('.price').html);
            }
        },

        getFormattedPrice: function (price, $widget) {
            //todo add format data
            return priceUtils.formatPrice(price, $widget.options.jsonConfig.priceFormat);
        },

        /**
         * Event for select
         *
         * @param $this
         * @param $widget
         * @private
         */
        _OnChange: function ($this, $widget) {
            /* Fix issue cannot add product to cart */
            var $parent = $this.parents('.' + $widget.options.classes.attributeClass),
                attributeId = $parent.attr('attribute-id'),
                updatePrice = true,
                $input = $parent.find('.' + $widget.options.classes.attributeInput);
            if ($widget.productForm.length > 0) {
                $input = $widget.productForm.find(
                    '.' + $widget.options.classes.attributeInput + '[name="super_attribute[' + attributeId + ']"]'
                );
            }
            /**/

            if (typeof($widget.update_price) !== 'undefined') {
                updatePrice = $widget.update_price;
            }
            if ($this.val() > 0) {
                $parent.attr('option-selected', $this.val());
                $input.val($this.val());
            } else {
                $parent.removeAttr('option-selected');
                $input.val('');
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

            var numberOfSelectedOptions = $widget.element.find('.' + $widget.options.classes.attributeClass + '[option-selected]').length;
            $widget._ChangeFromToPrice(attributeId, $this.attr('option-id'), $widget);

            /**
             * Do not replace data on category view page.
             * @see \Firebear\ConfigurableProducts\Plugin\Block\ConfigurableProduct\Product\View\Type::afterGetJsonConfig()
             */
            if (products.length == 1 && !this.options.jsonConfig.doNotReplaceData && numberOfSelectedOptions) {
                var simpleProductId = products[0];
                /**
                 * Change product attributes.
                 */
                $widget._ReplaceData(simpleProductId, this);
                /**
                 * Update input type hidden - fix base image doesn't change when choose option
                 */
                // if (simpleProductId && document.getElementsByName('product').length) {
                //     document.getElementsByName('product')[0].value = simpleProductId;
                // }
                var config = this.options.jsonConfig;
                require(['jqueryHistory'], function () {
                    if (typeof config.urls !== 'undefined' && typeof config.urls[simpleProductId] !== 'undefined') {
                        var url = config.urls[simpleProductId];
                        var title = null;
                        if (url) {
                            if (config.customAttributes[simpleProductId].name.value !== 'undefined') {
                                title = config.customAttributes[simpleProductId].name.value;
                            }
                            History.replaceState(null, title, url);
                        }
                    }
                });
            } else {
                $widget._ReplaceDataParent(this);
            }
            if ($widget.options.jsonConfig.attributes.length > 1) {
                $widget._updateMatrix(attributeId);
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
         * Update total price
         *
         * @private
         */
        _UpdatePrice: function () {
            var $widget = this,
                $product = $widget.element.parents($widget.options.selectorProduct),
                $productPrice = $product.find(this.options.selectorProductPrice),
                options = _.object(_.keys($widget.optionsMap), {}),
                result;
            $widget.element.find('.' + $widget.options.classes.attributeClass + '[option-selected]').each(function () {
                var attributeId = $(this).attr('attribute-id');
                var element = $('.price-box');
                    if (!element.data('magePriceBox')) {
                        element.priceBox();
                    }

                options[attributeId] = $(this).attr('option-selected');
            });
            result = $widget.options.jsonConfig.optionPrices[_.findKey($widget.options.jsonConfig.index, options)];
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

        _updateMatrix: function (attributeId) {
            var $widget = this,
                index = $widget.options.jsonConfig.index,
                optionIds = [],
                i = 0,
                attributes = $widget.options.jsonConfig.attributes,
                allSizeOptions = [],
                options = _.object(_.keys($widget.optionsMap), {});
            $.each(attributes, function () {
                optionIds[i] = this.id;
                if (this.id == $widget.options.jsonConfig.y_matrix_axis.id) {
                    allSizeOptions = this.options;
                }
                i++;
            });
            $.each(index, function (key, item) {
                $widget.element.find('.' + $widget.options.classes.attributeClass + '[option-selected]').each(function () {
                    var attributeIdOpt = $(this).attr('attribute-id');
                    options[attributeIdOpt] = $(this).attr('option-selected');
                });
                $.each(allSizeOptions, function() {
                    options[$widget.options.jsonConfig.y_matrix_axis.id] = this.id;
                    if (item[attributeId] == options[attributeId]) {
                        var selectedValue = options[$widget.options.jsonConfig.y_matrix_axis.id];
                        var productId = key;
                        $.each(index, function(itemId, itemOptions) {
                            if (JSON.stringify(options) === JSON.stringify(itemOptions)) {
                                productId = itemId;
                                return;
                            }
                        });
                        var updateMatrixValues = true;
                        $.each (options, function (attributeId, attributeValue) {
                            if (item[attributeId] !== attributeValue) {
                                updateMatrixValues = false;
                                return;
                            }
                        });
                    }
                    if (updateMatrixValues) {
                        if ($widget.options.jsonConfig.hidePrice) {
                            $('.y_axis_price_' + selectedValue).html($translate($widget.options.jsonConfig.priceText));
                            $('.prices-tier.items').hide();
                        } else {
                            $('.y_axis_price_' + selectedValue).html($widget.options.jsonConfig.currencySymbol + $widget.options.jsonConfig.optionPrices[productId].finalPrice.amount.toFixed(2));
                        }
                        $('.y_axis_stock_' + selectedValue).html($widget.options.jsonConfig.stockQty[productId]);
                        if ($widget.options.jsonConfig.tierPrice) {
                            var tierPriceQtyArray = [],
                                iterator = 0;
                            $.each($widget.options.jsonConfig.tierPrice, function (productIdTier, items) {
                                $.each(items.qty, function (qtyId, qty) {
                                    tierPriceQtyArray[iterator] = parseInt(qty);
                                    iterator++;
                                });
                            });
                            var tierPriceQtyArrayUnique = $widget.getUnique(tierPriceQtyArray);
                            tierPriceQtyArrayUnique.sort($widget.compareNumeric);
                            $.each(tierPriceQtyArrayUnique, function (k, v) {
                                var tierValuerPrice = null;
                                if ($widget.options.jsonConfig.hidePrice) {
                                    tierValuerPrice = $widget.options.jsonConfig.priceText;
                                } else if (!$widget.options.jsonConfig.tierPrice2[key]) {
                                    tierValuerPrice = $translate('not set');
                                } else {
                                    if (!$widget.options.jsonConfig.tierPrice2[key].price[v]) {
                                        tierValuerPrice = $translate('not set');
                                    } else {
                                        tierValuerPrice = $widget.getFormattedPrice($widget.options.jsonConfig.tierPrice2[key].price[v], $widget);
                                    }
                                }
                                $('.matrix_qty_val_from_' + v + '_' + selectedValue).html(tierValuerPrice);
                            });
                        }
                    }
                });
            });

        },

        _Rebuild: function () {
            var $widget = this,
                controls = $widget.element.find('.' + $widget.options.classes.attributeClass + '[attribute-id]'),
                selected = controls.filter('[option-selected]');

            // Enable all options
            $widget._Rewind(controls);

            // done if nothing selected
            if (selected.size() <= 0) {
                return;
            }

            // Disable not available options
            controls.each(function () {
                var $this = $(this),
                    id = $this.attr('attribute-id'),
                    products = $widget._CalcProducts(id);

                if (selected.size() === 1 && selected.first().attr('attribute-id') === id) {
                    return;
                }

                $this.find('[option-id]').each(function () {
                    var $element = $(this),
                        option = $element.attr('option-id');

                    if (!$widget.optionsMap.hasOwnProperty(id) || !$widget.optionsMap[id].hasOwnProperty(option) ||
                        $element.hasClass('selected') ||
                        $element.is(':selected')) {
                        return;
                    }
                    if (_.intersection(products, $widget.optionsMap[id][option].products).length <= 0) {
                        $element.attr('disabled', true).addClass('disabled');
                        $('.y_axis_qty_' + option + ' .firebear_qty_block').hide();
                        $('.y_axis_stock_' + option).html(_('Out of stock'));
                        $('.y_axis_available_' + option).html($translate("Out of stock"));
                        $('.y_axis_price_' + option).html($translate(''));

                        if ($widget.options.jsonConfig.tierPrice) {
                            var tierPriceQtyArray = [],
                                iterator = 0;
                            $.each($widget.options.jsonConfig.tierPrice, function (productIdTier, items) {
                                $.each(items.qty, function (qtyId, qty) {
                                    tierPriceQtyArray[iterator] = parseInt(qty);
                                    iterator++;
                                });
                            });
                            var tierPriceQtyArrayUnique = $widget.getUnique(tierPriceQtyArray);
                            tierPriceQtyArrayUnique.sort($widget.compareNumeric);
                            $.each(tierPriceQtyArrayUnique, function (k, v) {
                                $('.matrix_qty_val_from_' + v + '_' + option).html('');
                            });

                        }
                    } else {
                        $('.y_axis_available_' + option).html($translate("In stock"));
                        $('.y_axis_qty_' + option + ' .firebear_qty_block').show();
                    }
                });
            });
        },

        _CalcProducts: function ($skipAttributeId) {
            var $widget = this,
                products = [];

            // Generate intersection of products
            $widget.element.find('.' + $widget.options.classes.attributeClass + '[option-selected]').each(function () {
                var id = $(this).attr('attribute-id'),
                    option = $(this).attr('option-selected');

                if ($skipAttributeId !== undefined && $skipAttributeId === id) {
                    return;
                }

                if (!$widget.optionsMap.hasOwnProperty(id) || !$widget.optionsMap[id].hasOwnProperty(option)) {
                    return;
                }

                if (products.length === 0) {
                    products = $widget.optionsMap[id][option].products;
                } else {
                    products = _.intersection(products, $widget.optionsMap[id][option].products);
                }
            });

            return products;
        },

        _RenderFormInput: function (config, $widget) {
            var require = '';
            if (config.code == $widget.options.jsonConfig.x_matrix_axis.code) {
                require = 'data-validate="{required:true}" ';
            }
            return '<input class="' + this.options.classes.attributeInput + ' super-attribute-select" ' +
                'name="super_attribute[' + config.id + ']" ' +
                'type="text" ' +
                'value="" ' +
                'data-selector="super_attribute[' + config.id + ']" ' +
                require +
                'aria-required="true" ' +
                'aria-invalid="true" ' +
                'style="visibility: hidden; position:absolute; left:-1000px">';
        },

        /**
         * Input for submit form.
         * This control shouldn't have "type=hidden", "display: none" for validation work :(
         *
         * @param {Object} config
         * @private
         */
        _RenderFormInputDefault: function (config) {
            return '<input class="' + this.options.classes.attributeInput + ' super-attribute-select" ' +
                'name="super_attribute[' + config.id + ']" ' +
                'type="text" ' +
                'value="" ' +
                'data-selector="super_attribute[' + config.id + ']" ' +
                'data-validate="{required: true}" ' +
                'aria-required="true" ' +
                'aria-invalid="false">';
        },

        /**
         * Render controls
         *
         * @private
         */
        _RenderControls: function () {
            $('.description').append('<p class="firebear_custom_block1"></p><p class="firebear_custom_block2"></p><p class="firebear_custom_block3"></p><p class="firebear_custom_block4"></p>');
            var $widget = this,
                container = this.element,
                classes = this.options.classes,
                chooseText = this.options.jsonConfig.chooseText,
                containerHtml = '<div class="table matrix-icp-block"><table class="data table matrxix-table">',
                dataTableHead = '<thead><tr class="matrix_thead">',
                dataTableBody = '<tbody>',
                labelAttribute = '',
                options_x_axis_attribute = '',
                xAxisContainer = '',
                yAxisContainer = '';

            $('.product-info-stock-sku .stock').remove();
            $widget.optionsMap = {};
            $.each(this.options.jsonConfig.attributes, function () {
                var item = this;
                $widget.optionsMap[item.id] = {};
                // Aggregate options array to hash (key => value)
                $.each(item.options, function () {
                    var tierPriceValue = null;
                    if (this.products.length > 0) {
                        if ($widget.options.jsonConfig.tierPrice2) {
                            tierPriceValue = $widget.options.jsonConfig.tierPrice2[this.products[0]];
                        }
                        $widget.optionsMap[item.id][this.id] = {
                            price : parseFloat(
                                $widget.options.jsonConfig.optionPrices[this.products[0]].finalPrice.amount,
                                10
                            ),
                            qty : parseInt(
                                $widget.options.jsonConfig.stockQty[this.products[0]],
                                10
                            ),
                            tierPrice : tierPriceValue,
                            products : this.products
                        };
                    }
                });
            });
            var countSwatchConfig = 0;
            var newJsonSwatchConfig = {};
            $.each(this.options.jsonSwatchConfig, function (configId, item) {
                newJsonSwatchConfig[configId] = item;
                countSwatchConfig++;
            });
            if (countSwatchConfig == 1 || countSwatchConfig == 3 || countSwatchConfig == 0) {
                $.each(this.options.jsonConfig.attributes, function () {
                    var item = this;
                    var newOptionsArray = [];
                    if (!$widget.options.jsonSwatchConfig.hasOwnProperty(item.id)) {
                        $.each(item.options, function () {
                            var itemOptions = this;
                            newOptionsArray[itemOptions.id] = {
                                type : "0",
                                label: itemOptions.label,
                                value: itemOptions.label
                            };
                        });
                        $widget.options.jsonSwatchConfig[item.id] = newOptionsArray;
                    }
                });
            }
            $.each(this.options.jsonConfig.attributes, function () {
                var item = this;

                if ($widget.options.jsonConfig.x_matrix_axis.code == item.code || $widget.options.jsonConfig.y_matrix_axis.code == item.code) {
                    var select = $widget._RenderSwatchSelect(item, chooseText),
                        input = $widget._RenderFormInput(item, $widget),
                        options = $widget._RenderSwatchOptions(item, false),
                        label = '';
                    options_x_axis_attribute += $widget._RenderSwatchOptions(item, true);
                } else {
                    var select = $widget._RenderSwatchSelectDefault(item, chooseText),
                        controlLabelId = 'option-label-' + item.code + '-' + item.id,
                        input = $widget._RenderFormInputDefault(item, $widget),
                        options = $widget._RenderSwatchOptionsDefault(item, controlLabelId),
                        label = '',
                        listLabel = '';
                }

                if ($widget.options.jsonConfig.x_matrix_axis.code == item.code || $widget.options.jsonConfig.y_matrix_axis.code == item.code) {
                    // Show only swatch controls
                    if ($widget.options.onlySwatches && !$widget.options.jsonSwatchConfig.hasOwnProperty(item.id)) {
                        return;
                    }
                    if ($widget.options.enableControlLabel) {
                        if (item.label != $widget.options.jsonConfig.x_matrix_axis.label || $widget.options.jsonConfig.attributes.length == 1) {
                            label += '<th class="' + classes.attributeLabelClass + '">' + item.label + '</th>';
                        }
                        labelAttribute += '<span class="' + classes.attributeSelectedOptionLabelClass + '"></span>';
                    }
                    if ($widget.inProductList) {
                        $widget.productForm.append(input);
                        input = '';
                    }
                    dataTableHead += label;
                    dataTableBody += options;
                    if ($widget.options.jsonConfig.attributes.length > 1) {
                        if (item.code == $widget.options.jsonConfig.x_matrix_axis.code) {
                            xAxisContainer += '<div class="' + classes.attributeClass + ' ' + item.code +
                                '" attribute-code="' + item.code +
                                '" attribute-id="' + item.id + '">' +
                                label +
                                '<div class="' + classes.attributeOptionsWrapper + ' clearfix">' +
                                options_x_axis_attribute + select +
                                '</div>' + input +
                                '</div>';
                        }
                        else if (item.code == $widget.options.jsonConfig.y_matrix_axis.code) {
                            yAxisContainer += '<div class="' + classes.attributeClass + ' ' + item.code +
                                '" attribute-code="' + item.code +
                                '" attribute-id="' + item.id + '">' +
                                '<div class="' + classes.attributeOptionsWrapper + ' clearfix">' + input;
                        }
                        else if (item.code != $widget.options.jsonConfig.x_matrix_axis.code && item.code != $widget.options.jsonConfig.y_matrix_axis.code) {
                            xAxisContainer += '<div class="' + classes.attributeClass + ' ' + item.code +
                                '" attribute-code="' + item.code +
                                '" attribute-id="' + item.id + '">' +
                                label +
                                '<div class="' + classes.attributeOptionsWrapper + ' clearfix">' +
                                options_x_axis_attribute + select +
                                '</div>' + input +
                                '</div>';
                        }
                    } else {
                        if (item.code == $widget.options.jsonConfig.x_matrix_axis.code) {
                            xAxisContainer += '<div class="' + classes.attributeClass + ' ' + item.code +
                                '" attribute-code="' + item.code +
                                '" attribute-id="' + item.id + '">' +
                                label +
                                '<div class="' + classes.attributeOptionsWrapper + ' clearfix">' +
                                options_x_axis_attribute + select +
                                '</div>' + input +
                                '</div>';
                        } else if (item.code == $widget.options.jsonConfig.y_matrix_axis.code) {
                            yAxisContainer += '<div class="' + classes.attributeClass + ' ' + item.code +
                                '" attribute-code="' + item.code +
                                '" attribute-id="' + item.id + '">' +
                                '<div class="' + classes.attributeOptionsWrapper + ' clearfix">' + input;
                        } else if (item.code != $widget.options.jsonConfig.x_matrix_axis.code && item.code != $widget.options.jsonConfig.y_matrix_axis.code) {
                            xAxisContainer += '<div class="' + classes.attributeClass + ' ' + item.code +
                                '" attribute-code="' + item.code +
                                '" attribute-id="' + item.id + '">' +
                                label +
                                '<div class="' + classes.attributeOptionsWrapper + ' clearfix">' +
                                options_x_axis_attribute + select +
                                '</div>' + input +
                                '</div>';
                        }
                    }
                } else {
                    // Show only swatch controls
                    if ($widget.options.onlySwatches && !$widget.options.jsonSwatchConfig.hasOwnProperty(item.id)) {
                        return;
                    }
                    if ($widget.options.enableControlLabel) {
                        label +=
                            '<span id="' + controlLabelId + '" class="' + classes.attributeLabelClass + '">' +
                            item.label +
                            '</span>' +
                            '<span class="' + classes.attributeSelectedOptionLabelClass + '"></span>';
                    }
                    if ($widget.inProductList) {
                        $widget.productForm.append(input);
                        input = '';
                        listLabel = 'aria-label="' + item.label + '"';
                    } else {
                        listLabel = 'aria-labelledby="' + controlLabelId + '"';
                    }
                    // Create new control
                    container.append(
                        '<div class="' + classes.attributeClass + ' ' + item.code + '" ' +
                        'attribute-code="' + item.code + '" ' +
                        'attribute-id="' + item.id + '">' +
                        label +
                        '<div aria-activedescendant="" ' +
                        'tabindex="0" ' +
                        'aria-invalid="false" ' +
                        'aria-required="true" ' +
                        'role="listbox" ' + listLabel +
                        'class="' + classes.attributeOptionsWrapper + ' clearfix">' +
                        options + select +
                        '</div>' + input +
                        '</div>'
                    );
                    $widget.optionsMap[item.id] = {};
                    // Aggregate options array to hash (key => value)
                    $.each(item.options, function () {
                        if (this.products.length > 0) {
                            $widget.optionsMap[item.id][this.id] = {
                                price   : parseFloat(
                                    $widget.options.jsonConfig.optionPrices[this.products[0]].finalPrice.amount,
                                    10
                                ),
                                products: this.products
                            };
                        }
                    });
                }
            });

            if ($widget.options.jsonConfig.configManageStock) {
                dataTableHead += '<th>' + $translate("Available Qty") + '</th>';
            }
            dataTableHead += '<th>' + $translate("Stock Status") + '</th>';
            dataTableHead += '<th>' + $translate("Price") + '</th>';
            /**
             * ############### RENDERER TIER PRICE HEADERS #####################
             */
            if (this.options.jsonConfig.tierPrice) {
                var tierPriceQtyArray = [],
                    iterator = 0;
                $.each(this.options.jsonConfig.tierPrice, function (productId, items) {
                    $.each(items.qty, function (qtyId, qty) {
                        tierPriceQtyArray[iterator] = parseInt(qty);
                        iterator++;
                    });
                });
                var tierPriceQtyArrayUnique = $widget.getUnique(tierPriceQtyArray);
                tierPriceQtyArrayUnique.sort($widget.compareNumeric);

                $.each(tierPriceQtyArrayUnique, function (key, from) {
                    dataTableHead += '<th style="white-space: nowrap;">' + $translate('from') + ' ' + from + '</th>';
                });
            }
            /**
             * ############### RENDERER TIER PRICE HEADERS #####################
             */
            dataTableHead += '<th>' + $translate("Qty") + '</th>';
            dataTableHead += '</tr>';
            containerHtml += dataTableHead + dataTableBody;
            containerHtml += '</table></div></div></div>';
            if ($widget.options.jsonConfig.attributes.length == 1 && typeof ($widget.options.jsonConfig.x_matrix_axis.id) !== 'undefined') {
                containerHtml = '<div class="' + classes.attributeClass + ' ' + $widget.options.jsonConfig.x_matrix_axis.code +
                    '" attribute-code="' + $widget.options.jsonConfig.x_matrix_axis.code +
                    '" attribute-id="' + $widget.options.jsonConfig.x_matrix_axis.id + '">' + containerHtml + '</div>';
            }
            if (yAxisContainer != '') {
                $('.box-tocart .qty').remove();
                container.append(xAxisContainer + labelAttribute + yAxisContainer + containerHtml + labelAttribute);
            } else {
                container.append(xAxisContainer + labelAttribute + labelAttribute);
            }
            // Connect Tooltip
            container
                .find('[option-type="1"], [option-type="2"], [option-type="0"], [option-type="3"]')
                .SwatchRendererTooltip();
            // Hide all elements below more button
            $('.' + classes.moreButton).nextAll().hide();
            // Handle events like click or change
            $widget._EventListener();
            // Rewind options
            $widget._Rewind(container);
            //Emulate click on all swatches from Request
            $widget._EmulateSelected($.parseQuery(), $widget);
            $widget._EmulateSelected($widget._getSelectedAttributes(), $widget);
            /** todo this **/
            var table = document.getElementsByClassName('matrxix-table')[0];
            if (typeof(table) !== 'undefined') {
                for (var i = 6; i < table.rows.length; i++) {
                    table.rows[i].style.display = 'none';
                }
                if (table.rows.length > 6) {
                    var tableBlock = $('.matrix-icp-block');
                    tableBlock.append('<span><a class="load_more_matrix" style="cursor: pointer" onclick="return false">Load More</a></span>');
                }
            }
        },
        compareNumeric : function (a, b) {
            if (a > b) return 1;
            if (a < b) return -1;
        },
        /**
         * Delete duplicate elements from array
         *
         * @param arr
         * @returns {Array}
         */
        getUnique : function (arr) {
            var i = 0,
                current,
                length = arr.length,
                unique = [];
            for (; i < length; i++) {
                current = arr[i];
                if (!~unique.indexOf(current)) {
                    unique.push(current);
                }
            }
            return unique;
        },

        _RenderCustomOptionsBySimpleProduct: function (productId, $widget) {
            $.ajax({
                url       : $widget.options.jsonConfig.loadOptionsUrl,
                type      : 'POST',
                dataType  : 'json',
                showLoader: true,
                data      : {
                    productId: productId
                },
                success : function (response) {
                    if (response.optionsHtml && !$('.product-cpi-custom-options' + productId).html()) {
                        $('.product-options-wrapper').append('<div class="product-cpi-custom-options product-cpi-custom-options' + productId + '" product-id=[' + productId + ']></div>');
                        $('.product-cpi-custom-options' + productId).html(response.optionsHtml);
                    }
                }
            });
        },

        _loadMoreOptions: function ($this, $widget) {
            var table = document.getElementsByClassName('matrxix-table')[0];
            for (var i = 6; i < table.rows.length; i++) {
                table.rows[i].style.display = 'table-row';
            }
            jQuery('.load_more_matrix').addClass('hide_more_matrix');
            jQuery('.load_more_matrix').html('Hide More');
            jQuery('.load_more_matrix').removeClass('load_more_matrix');
        },

        _hideMoreOptions: function ($this, $widget) {
            var table = document.getElementsByClassName('matrxix-table')[0];
            for (var i = 6; i < table.rows.length; i++) {
                table.rows[i].style.display = 'none';
            }
            jQuery('.hide_more_matrix').addClass('load_more_matrix');
            jQuery('.hide_more_matrix').html('Load More');
            jQuery('.hide_more_matrix').removeClass('hide_more_matrix');
        },

        /**
         * Render select by part of config
         *
         * @param {Object} config
         * @param {String} chooseText
         * @returns {String}
         * @private
         */
        _RenderSwatchSelect: function (config, chooseText) {
            var html = '';
            if (this.options.jsonSwatchConfig.hasOwnProperty(config.id)) {
                return '';
            }
            $.each(config.options, function () {
                var label = this.label,
                    attr = ' value="' + this.id + '" option-id="' + this.id + '"';
                if (!this.hasOwnProperty('products') || this.products.length <= 0) {
                    attr += ' option-empty="true"';
                }
                html += '<td><div class="swatch-option text" ' + attr + '>' + label + '</div></td>';
            });

            return html;
        },

        _RenderSwatchSelectDefault: function (config, chooseText) {
            var html;

            if (this.options.jsonSwatchConfig.hasOwnProperty(config.id)) {
                return '';
            }
            html =
                '<select class="' + this.options.classes.selectClass + ' ' + config.code + '">' +
                '<option value="0" option-id="0">' + chooseText + '</option>';

            $.each(config.options, function () {
                var label = this.label,
                    attr = ' value="' + this.id + '" option-id="' + this.id + '"';

                if (!this.hasOwnProperty('products') || this.products.length <= 0) {
                    attr += ' option-empty="true"';
                }
                html += '<option ' + attr + '>' + label + '</option>';
            });
            html += '</select>';

            return html;
        },

        /**
         * Render swatch options by part of config
         *
         * @param {Object} config
         * @param only_color
         * @returns {String}
         * @private
         */
        _RenderSwatchOptions : function (config, only_color) {
            var optionConfig = this.options.jsonSwatchConfig[config.id],
                optionsMap = this.optionsMap[config.id],
                optionClass = this.options.classes.optionClass,
                moreLimit = parseInt(this.options.numberToShow, 10),
                moreClass = this.options.classes.moreButton,
                moreText = this.options.moreButtonText,
                countAttributes = 0,
                html = '',
                currencySymbol = this.options.jsonConfig.currencySymbol,
                color = '',
                $widget = this,
                optionId = config.id;
            if (!this.options.jsonSwatchConfig.hasOwnProperty(config.id)) {
                return '';
            }

            $.each(config.options, function () {
                var id,
                    type,
                    value,
                    thumb,
                    label,
                    attr;
                if (!optionConfig.hasOwnProperty(this.id)) {
                    return '';
                }
                // Add more button
                if (moreLimit === countAttributes++) {
                    html += '<a href="#" class="' + moreClass + '">' + moreText + '</a>';
                }
                id = this.id;
                type = parseInt(optionConfig[id].type, 10);
                value = optionConfig[id].hasOwnProperty('value') ? optionConfig[id].value : '';
                thumb = optionConfig[id].hasOwnProperty('thumb') ? optionConfig[id].thumb : '';
                label = this.label ? this.label : '';
                attr =
                    ' option-type="' + type + '"' +
                    ' option-id="' + id + '"' +
                    ' option-label="' + label + '"' +
                    ' option-tooltip-thumb="' + thumb + '"' +
                    ' option-tooltip-value="' + value + '"';

                if (!this.hasOwnProperty('products') || this.products.length <= 0) {
                    attr += ' option-empty="true"';
                }
                if ($widget.options.jsonConfig.attributes.length == 1) {
                    if (!only_color  || config.code == $widget.options.jsonConfig.y_matrix_axis.code) {
                        html += '<tr>';
                    }
                }
                else if ($widget.options.jsonConfig.attributes.length > 1) {
                    if (!only_color) {
                        if ($widget.options.jsonConfig.x_matrix_axis.code != config.code) {
                            html += '<tr>';
                        }
                    }
                }

                if (type === 0) {
                    // Text
                    if ($widget.options.jsonConfig.y_matrix_axis.code !== config.code &&
                        ($widget.options.jsonConfig.x_matrix_axis.type && $widget.options.jsonConfig.x_matrix_axis.type == 'select')) {
                        color =
                            '<div class="field configurable required" >' +
                            '<label class="label" for="attribute' + this.config + '">' +
                            '<span class="swatch-attribute-label">' + config.label + '</span></label>' +
                            '<select class="' + $widget.options.classes.selectClass + ' ' + config.code + '">' +
                            '<option value="0" option-id="0">' + $widget.options.jsonConfig.chooseText + '</option>';
                        $.each(config.options, function () {
                            var label = this.label,
                                attr = ' value="' + this.id + '" option-id="' + this.id + '"';

                            if (this.products.length <= 0) {
                                attr += ' option-empty="true"';
                            }
                            color += '<option ' + attr + attr + '>' + label + '</option>';
                        });
                        color += '</select></div>';
                    } else if ($widget.options.jsonConfig.x_matrix_axis.code == config.code) {
                        color += '<div class="' + optionClass + ' text" ' + attr + '>' + (value ? value : label) +
                            '</div>';
                    } else {
                        html += '<td><div class="' + optionClass + ' text" ' + attr + '>' + (value ? value : label) +
                            '</div></td>';
                    }
                } else if (type === 1) {
                    // Color
                    if ($widget.options.jsonConfig.attributes.length == 1 || $widget.options.jsonConfig.y_matrix_axis.code == config.code) {
                        html += '<td><div class="' + optionClass + ' color" ' + attr +
                            '" style="background: ' + value +
                            ' no-repeat center; background-size: initial;">' + '' +
                            '</div></td>';
                    } else {
                        color += '<div class="' + optionClass + ' color" ' + attr +
                            '" style="background: ' + value +
                            ' no-repeat center; background-size: initial;">' + '' +
                            '</div>';
                    }
                } else if (type === 2) {
                    // Image
                    if ($widget.options.jsonConfig.attributes.length == 1 || $widget.options.jsonConfig.y_matrix_axis.code == config.code) {
                        html += '<td><div class="' + optionClass + ' image" ' + attr +
                            '" style="background: url(' + value + ') no-repeat center; background-size: initial;">' + '' +
                            '</div></td>';
                    } else {
                        color += '<div class="' + optionClass + ' image" ' + attr +
                            '" style="background: url(' + value + ') no-repeat center; background-size: initial;">' + '' +
                            '</div>';
                    }
                } else if (type === 3) {
                    // Clear
                    html += '<td><div class="' + optionClass + '" ' + attr + '></div></td>';
                } else {
                    // Defaualt
                    html += '<td><div class="' + optionClass + '" ' + attr + '>' + label + '</div></td>';
                }
                if ($widget.options.jsonConfig.attributes.length == 1) {
                    if (!only_color || config.code == $widget.options.jsonConfig.y_matrix_axis.code) {
                        var selected_attributes = $widget._getSelectedAttributes(),
                            selectedAttributes = {},
                            name_id = 0;
                        if (selected_attributes.length != 0) {
                            $.each($widget.options.jsonConfig.attributes, function () {
                                var attributeId = this.id,
                                    attributeCode = this.code;
                                selectedAttributes[attributeId] = selected_attributes[attributeCode];
                                if (attributeCode == config.code) {
                                    selectedAttributes[attributeId] = id;
                                }
                            });
                            $.each($widget.options.jsonConfig.index, function (key, item) {
                                if (JSON.stringify(item) == JSON.stringify(selectedAttributes)) {
                                    name_id = key;
                                    return false;
                                }
                            });
                        } else {
                            if (optionsMap[id]) {
                                name_id = optionsMap[id].products[0];
                            } else {
                                return;
                            }
                        }
                        if ($widget.options.jsonConfig.hidePrice) {
                            var priceInMatrixRow = $translate($widget.options.jsonConfig.priceText);
                        } else {
                            if (optionsMap[id]) {
                                var priceInMatrixRow = currencySymbol + optionsMap[id].price.toFixed(2);
                            } else {
                                return;
                            }
                        }
                        if ($widget.options.jsonConfig.configManageStock && optionsMap[id]) {
                            html += '<td class="y_axis_stock_' + id + '">' + optionsMap[id].qty + '</td>';
                        } else {
                            return;
                        }
                        html += '<td class="y_axis_available_' + id + '">' + $translate("In stock") + '</td><td class="y_axis_price_' + id + '">' + priceInMatrixRow + '</td>';

                        /**
                         * ############### RENDERER TIER PRICE CONTENT #####################
                         */
                        if ($widget.options.jsonConfig.tierPrice) {

                            var tierPriceQtyArray = [],
                                iterator = 0;
                            $.each($widget.options.jsonConfig.tierPrice, function (productId, items) {
                                $.each(items.qty, function (qtyId, qty) {
                                    tierPriceQtyArray[iterator] = parseInt(qty);
                                    iterator++;
                                });
                            });
                            var tierPriceQtyArrayUnique = $widget.getUnique(tierPriceQtyArray);
                            tierPriceQtyArrayUnique.sort($widget.compareNumeric);
                            $.each(tierPriceQtyArrayUnique, function (key, from) {
                                if (optionsMap[id].tierPrice && optionsMap[id].tierPrice.price[from]) {
                                    html += '<td class="matrix_qty_val_from_' + from + '_' + id + ' tier_price_td">' + $widget.getFormattedPrice(optionsMap[id].tierPrice.price[from], $widget) + '</td>';
                                } else {
                                    html += '<td class="matrix_qty_val_from_' + from + '_' + id + ' tier_price_td">' + $translate('not set') + '</td>';
                                }
                            });

                        }
                        /**
                         * ############### RENDERER TIER PRICE CONTENT #####################
                         */

                        html += '<td class="matrix_qty_td y_axis_qty_' + id + '"><div class="firebear_qty_block"><span class="arrow-matrix inc_matrix_arrow inc_qty_' + id + '">&uarr;</span><input type="number" name="qty_matrix_product[' + config.id + '][' + id + ']" maxlength="12" value="0" title="Qty" class="qty input-text matrix_qty qty' + id + '" data-validate="{&quot;required-number&quot;:true}"><span class="arrow-matrix dec_matrix_arrow inc_qty_' + id + '">&darr;</span></div></td>';
                        html += '</tr>';
                    }
                } else if ($widget.options.jsonConfig.attributes.length > 1) {
                    if (!only_color) {
                        if ($widget.options.jsonConfig.x_matrix_axis.code != config.code) {
                            var selected_attributes = $widget._getSelectedAttributes(),
                                selectedAttributes = {},
                                name_id = 0,
                                options = [];
                            if (selected_attributes.length != 0) {
                                $.each($widget.options.jsonConfig.attributes, function () {
                                    var attributeId = this.id,
                                        attributeCode = this.code;
                                    selectedAttributes[attributeId] = selected_attributes[attributeCode];
                                    if (attributeCode == config.code) {
                                        selectedAttributes[attributeId] = id;
                                    }
                                });
                                $.each($widget.options.jsonConfig.index, function (key, item) {
                                    if (JSON.stringify(item) == JSON.stringify(selectedAttributes)) {
                                        name_id = key;
                                        return false;
                                    }
                                });
                            } else {
                                if (optionsMap[id]) {
                                    name_id = optionsMap[id].products[0];
                                } else {
                                    return;
                                }
                            }
                            if ($widget.options.jsonConfig.configManageStock && optionsMap[id]) {
                                html += '<td class="y_axis_stock_' + id + '">' + optionsMap[id].qty + '</td>';
                            }
                            if ($widget.options.jsonConfig.hidePrice) {
                                var priceInMatrixRow = $translate($widget.options.jsonConfig.priceText);
                            } else {
                                if (optionsMap[id]) {
                                    var priceInMatrixRow = currencySymbol + optionsMap[id].price.toFixed(2);
                                } else {
                                    html += '<td class="y_axis_stock_' + id + '"></td>';
                                    html += '<td class="y_axis_available_' + id + '">' + $translate("Out of stock") + '</td>';
                                    return;
                                }
                            }
                            html += '<td class="y_axis_available_' + id + '">' + $translate("In stock") + '</td><td class="y_axis_price_' + id + '">' + priceInMatrixRow + '</td>';

                            /**
                             * ############### RENDERER TIER PRICE CONTENT #####################
                             */
                            if ($widget.options.jsonConfig.tierPrice) {

                                var tierPriceQtyArray = [],
                                    iterator = 0;
                                $.each($widget.options.jsonConfig.tierPrice, function (productId, items) {
                                    $.each(items.qty, function (qtyId, qty) {
                                        tierPriceQtyArray[iterator] = parseInt(qty);
                                        iterator++;
                                    });
                                });
                                var tierPriceQtyArrayUnique = $widget.getUnique(tierPriceQtyArray);
                                tierPriceQtyArrayUnique.sort($widget.compareNumeric);

                                $.each(tierPriceQtyArrayUnique, function (key, from) {
                                    if (optionsMap[id].tierPrice && optionsMap[id].tierPrice.price[from]) {
                                        if (!$widget.options.jsonConfig.hidePrice) {
                                            html += '<td class="matrix_qty_val_from_' + from + '_' + id + ' tier_price_td">' + $widget.getFormattedPrice(optionsMap[id].tierPrice.price[from], $widget) + '</td>';
                                        } else {
                                            html += '<td class="matrix_qty_val_from_' + from + '_' + id + ' tier_price_td">' + $translate($widget.options.jsonConfig.priceText) + '</td>';
                                        }
                                    } else {
                                        if (!$widget.options.jsonConfig.hidePrice) {
                                            html += '<td class="matrix_qty_val_from_' + from + '_' + id + ' tier_price_td">' + $translate('not set') + '</td>';
                                        } else {
                                            html += '<td class="matrix_qty_val_from_' + from + '_' + id + ' tier_price_td">' + $translate($widget.options.jsonConfig.priceText) + '</td>';
                                        }
                                    }
                                });
                            }
                            /**
                             * ############### RENDERER TIER PRICE CONTENT #####################
                             */

                            html += '<td class="matrix_qty_td y_axis_qty_' + id + '"><div class="firebear_qty_block"><span class="arrow-matrix inc_matrix_arrow inc_qty_' + id + '">&uarr;</span><input type="number" name="qty_matrix_product[' + config.id + '][' + id + ']" maxlength="12" value="0" title="Qty" class="qty input-text matrix_qty qty' + id + '" data-validate="{&quot;required-number&quot;:true}"><span class="arrow-matrix dec_matrix_arrow inc_qty_' + id + '">&darr;</span></div></td>';
                            html += '</tr>';
                        }
                    }
                }
            });
            if (only_color) {
                return color;
            }
            else {
                return html;
            }
        },

        _RenderSwatchOptionsDefault: function (config, controlId) {
            var optionConfig = this.options.jsonSwatchConfig[config.id],
                $widget = this,
                optionClass = this.options.classes.optionClass,
                moreLimit = parseInt(this.options.numberToShow, 10),
                moreClass = this.options.classes.moreButton,
                moreText = this.options.moreButtonText,
                countAttributes = 0,
                html = '';

            if (!this.options.jsonSwatchConfig.hasOwnProperty(config.id)) {
                return '';
            }

            $.each(config.options, function () {
                var id,
                    type,
                    value,
                    thumb,
                    label,
                    attr;

                if (!optionConfig.hasOwnProperty(this.id)) {
                    return '';
                }
                // Add more button
                if (moreLimit === countAttributes++) {
                    html += '<a href="#" class="' + moreClass + '">' + moreText + '</a>';
                }
                id = this.id;
                type = parseInt(optionConfig[id].type, 10);
                value = optionConfig[id].hasOwnProperty('value') ? optionConfig[id].value : '';
                thumb = optionConfig[id].hasOwnProperty('thumb') ? optionConfig[id].thumb : '';
                label = this.label ? this.label : '';
                attr =
                    ' id="' + controlId + '-item-' + id + '"' +
                    ' aria-checked="false"' +
                    ' aria-describedby="' + controlId + '"' +
                    ' tabindex="0"' +
                    ' option-type="' + type + '"' +
                    ' option-id="' + id + '"' +
                    ' option-label="' + label + '"' +
                    ' aria-label="' + label + '"' +
                    ' option-tooltip-thumb="' + thumb + '"' +
                    ' option-tooltip-value="' + value + '"' +
                    ' role="option"';

                if (!this.hasOwnProperty('products') || this.products.length <= 0) {
                    attr += ' option-empty="true"';
                }
                if (type === 0) {
                    // Text
                    if (config.code && config.type == 'select') {
                        html =
                            '<select class="' + $widget.options.classes.selectClass + ' ' + config.code + '">' +
                            '<option value="0" option-id="0">' + $widget.options.jsonConfig.chooseText + '</option>';
                        $.each(config.options, function () {
                            var label = this.label,
                                attr = ' value="' + this.id + '" option-id="' + this.id + '"';

                            if (this.products.length <= 0) {
                                attr += ' option-empty="true"';
                            }
                            html += '<option ' + attr + 'class="' + optionClass + ' color" ' + attr + '>' + label + '</option>';
                        });
                        html += '</select>';
                    } else {
                        html += '<div class="' + optionClass + ' text" ' + attr + '>' + (value ? value : label) +
                            '</div>';
                    }
                } else if (type === 1) {
                    // Color
                    html += '<div class="' + optionClass + ' color" ' + attr +
                        ' style="background: ' + value +
                        ' no-repeat center; background-size: initial;">' + '' +
                        '</div>';
                } else if (type === 2) {
                    // Image
                    html += '<div class="' + optionClass + ' image" ' + attr +
                        ' style="background: url(' + value + ') no-repeat center; background-size: initial;">' + '' +
                        '</div>';
                } else if (type === 3) {
                    // Clear
                    html += '<div class="' + optionClass + '" ' + attr + '></div>';
                } else {
                    // Default
                    html += '<div class="' + optionClass + '" ' + attr + '>' + label + '</div>';
                }
            });
            return html;
        },

    });
    return $.mage.SwatchRenderer;
});
