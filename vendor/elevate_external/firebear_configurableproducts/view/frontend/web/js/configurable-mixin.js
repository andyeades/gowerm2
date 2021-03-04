/**
 * configurable-mixin
 *
 * @copyright Copyright © 2020 Firebear Studio. All rights reserved.
 * @author    Firebear Studio <fbeardev@gmail.com>
 */
define(
    [
        'jquery',
        'priceBox',
        'jqueryHistory',
        'domReady!'
    ], function ($) {
        'use strict';

        /**
         *
         * @type {{_RenderCustomOptionsBySimpleProduct: _RenderCustomOptionsBySimpleProduct, _configureForValues: _configureForValues, _calculatePrice: _calculatePrice, _changeProductImage: _changeProductImage, options: {priceHolderSelector: string, spConfig: {}}, _initializeOptions: (function(): *), _ReplaceData: _ReplaceData}}
         */
        var icpMixin = {
            options: {
                priceHolderSelector: '.price-box',
                spConfig: {},
                mtConfig: {
                enabled: false,
                useOriginalGallery: true,
                currentProductId: null,
                galleryData: [],
                tools: {},
                thumbSwitcherOptions: {},
                mtContainerSelector: 'div.MagicToolboxContainer'
            },
            },
            
            /**
             * Initialize tax configuration, initial settings, and options values.
             * @returns {*}
             * @private
             */
            _initializeOptions: function () {
                     
          //  this._super();

         
                var element;
                element = $(this.options.priceHolderSelector);
                if (!element.data('magePriceBox')) {
                    element.priceBox();
                }
                
                
                   if (typeof(this.options.spConfig.magictoolbox) == 'undefined' || typeof(this.options.spConfig.productId) == 'undefined') {
              
            }else{

            this.options.mtConfig.enabled = true;
            this.options.mtConfig.currentProductId = this.options.spConfig.productId;
            this.options.mtConfig.useOriginalGallery = this.options.spConfig.magictoolbox.useOriginalGallery;
            this.options.mtConfig.galleryData = this.options.spConfig.magictoolbox.galleryData;
            this.options.mtConfig.tools = {
                'Magic360': {
                    'idTemplate': '{tool}-product-{id}',
                    'objName': 'Magic360',
                    'undefined': true
                },
                'MagicSlideshow': {
                    'idTemplate': '{tool}-product-{id}',
                    'objName': 'MagicSlideshow',
                    'undefined': true
                },
                'MagicScroll': {
                    'idTemplate': '{tool}-product-{id}',
                    'objName': 'MagicScroll',
                    'undefined': true
                },
                'MagicZoomPlus': {
                    'idTemplate': '{tool}Image-product-{id}',
                    'objName': 'MagicZoom',
                    'undefined': true
                },
                'MagicZoom': {
                    'idTemplate': '{tool}Image-product-{id}',
                    'objName': 'MagicZoom',
                    'undefined': true
                },
                'MagicThumb': {
                    'idTemplate': '{tool}Image-product-{id}',
                    'objName': 'MagicThumb',
                    'undefined': true
                }
            };
            for (var tool in this.options.mtConfig.tools) {
                this.options.mtConfig.tools[tool].undefined = (typeof(window[tool]) == 'undefined');
            }
            if (!this.options.mtConfig.tools['MagicZoom'].undefined) {
                var suffix = MagicZoom.version.indexOf('Plus') > -1 ? 'Plus' : '';
                this.options.mtConfig.tools['MagicZoom'].undefined = true;
                this.options.mtConfig.tools['MagicZoomPlus'].undefined = true;
                this.options.mtConfig.tools['MagicZoom' + suffix].undefined = false;
            }

            //NOTE: get thumb switcher options
            var container = $(this.options.mtConfig.mtContainerSelector);
            if (container.length && container.magicToolboxThumbSwitcher) {
                this.options.mtConfig.thumbSwitcherOptions = container.magicToolboxThumbSwitcher('getOptions');
            }                  
             }                             
             return  this._super();
            },

            /**
             * Setup for all configurable option settings. Set the value of the option and configure
             * the option, which sets its state, and initializes the option's choices, etc.
             * @private
             */
            _configureForValues: function () {
                this._super();
                if (this.options.values) {
                    var gallery = $(this.options.mediaGallerySelector),
                        $this = this;
                    gallery.on('gallery:loaded', function () {
                        $this._changeProductImage();
                    });
                }
                /*pre-selected configurable options*/
                // if (this.options.values) {
                //     this.options.settings.each($.proxy(function (index, element) {
                //         var attributeId = element.attributeId;
                //         element.value = this.options.values[attributeId] || '';
                //         if(!element.value){
                //             var attributeCode = this.options.spConfig.attributes[attributeId].code;
                //             var defaultValue = this.options.spConfig.defaultValues[attributeCode];
                //             $('#attribute'+attributeId).val(defaultValue).trigger('change');
                //         }
                //         this._configureElement(element);
                //     }, this));
                // }
                // localStorage.setItem('processed', '');
                // //pre-selected product options
                // if(!localStorage.getItem('processed')){
                //     var productId = this.simpleProduct;
                //     var config = this.options.spConfig;
                //     var currentURL = window.location.href;
                //     var simpleProductId = '';
                //     if (typeof config.urls !== 'undefined'){
                //         $.each(config.urls, function (productId, productUrl) {
                //             if(productUrl == currentURL){
                //                 simpleProductId = productId;
                //                 return true;
                //             }
                //         });
                //     }
                //     if(simpleProductId){
                //         $.each(config.attributes, function () {
                //             var item = this;
                //             var allOptions = item.options;
                //             $.each(allOptions, function (key, optionObj){
                //                 var products = optionObj.products;
                //                 for(var i = 0; i < products.length; i++){
                //                     var childProductId = optionObj.products[i];
                //                     if(simpleProductId == childProductId){
                //                        var selectedId = optionObj.id;
                //                         var select = $('#attribute'+item.id);
                //                         select.val(selectedId).trigger('change');
                //                     }
                //                 }
                //             });
                //         });
                //     }
                // }
                // localStorage.setItem('processed',true);
            },

            /**
             * Change displayed product image according to chosen options of configurable product
             *
             * @private
             */
            _changeProductImage: function () {
                this._super();
                var productId = this.simpleProduct;
                var config = this.options.spConfig;

                if (!productId || !$.isNumeric(productId)) {
                    productId = 'parent';
                }

                /**
                 * Change product attributes.
                 */
                this._ReplaceData(productId, config);

                if (typeof config.urls !== 'undefined' && typeof config.urls[productId] !== 'undefined') {
                    var url = config.urls[productId];
                    var title = null;
                    if (typeof config.customAttributes[productId].name !== 'undefined'
                        && typeof config.customAttributes[productId].name.value !== 'undefined'
                    ) {
                        title = config.customAttributes[productId].name.value;
                    }
                    const queryString = window.location.search;
                 var currentUrl = location.protocol + '//' + location.host + location.pathname;

                 //

                   if(currentUrl != url){
                    History.replaceState(null, title, url+queryString);
                    }
                }

            },

            /**
             * Returns prices for configured products
             *
             * @param {*} config - Products configuration
             * @returns {*}
             * @private
             */
            _calculatePrice: function (config) {
             
            
                
            var displayPrices = $(this.options.priceHolderSelector).priceBox('option').prices,
                newPrices = this.options.spConfig.optionPrices[_.first(config.allowedProducts)],
                                customOptionsPrice = 0;

jQuery('.old-price span.price-label').html('RRP');
            jQuery('.normal-price span.price-label').html('NOW');



            var optionPrices = this.options.spConfig.optionPrices,

                optionMinPrice = false, optionFinalPrice;
            console.log("ANDY");
            console.log(optionPrices);
            var hasWas = false;
            _.each(optionPrices, function (allowedProduct) {

                allowedProduct.wasPrice
                if (allowedProduct && allowedProduct.wasPrice){


                optionFinalPrice = parseFloat(allowedProduct.wasPrice.amount);
                    hasWas = true;
                if (!optionMinPrice || optionFinalPrice < optionMinPrice && optionFinalPrice > 0) {
                    optionMinPrice = optionFinalPrice;

                }
                }
            });

          //  newPrices.wasPrice.amount = optionMinPrice;


            displayPrices.wasPrice = {};
           // displayPrices.wasPrice.amount = 0;
//console.log("UI");  

            if (this.options.spConfig.optionPrices[_.first(config.allowedProducts)] && this.options.spConfig.optionPrices[_.first(config.allowedProducts)].stock_message) {


                var stock_message = this.options.spConfig.optionPrices[_.first(config.allowedProducts)].stock_message;

                if(stock_message === ''){
                    jQuery('#stock_message').hide();
                }
                else{

                    jQuery('#stock_message').html(stock_message).show();
                }

                // Elevate - RJ - 27.7.2020 - added this undefined wrapper as it was borking execution of js - not sure how it would work anyway as I can't see childProducts defined anywhere?
                if (typeof (childProducts) !== 'undefined') {
                    var promo_inject = childProducts[childProductId]["promo_inject"];

                    if(promo_inject == 1){
                        jQuery('#promoinject').html('Quick Delivery').show();

                    }
                    else{
                        jQuery('#promoinject').hide();
                    }

                } else {
                    jQuery('#promoinject').hide();
                }




            }
            if (this.options.spConfig.optionPrices[_.first(config.allowedProducts)] && this.options.spConfig.optionPrices[_.first(config.allowedProducts)].wasPrice) {

                var wasamount = this.options.spConfig.optionPrices[_.first(config.allowedProducts)].wasPrice.amount;


                if(wasamount){
                    if(hasWas){
                        jQuery('.was-price').removeClass('hide_was');
                    }else{
                        jQuery('.was-price').addClass('hide_was');
                    }
                jQuery('.evwasprice').html("&pound;"+wasamount.toFixed(2));
                }
                else{
                    jQuery('.was-price').addClass('hide_was');
                }
            }
            else{
                jQuery('.was-price').addClass('hide_was');
            }
         //   displayPrices.wasPrice.amount = wasamount;
//console.log(displayPrices);
            if (typeof (this.customOptionsPrice) !== 'undefined') {
                customOptionsPrice = this.customOptionsPrice;
            }

            _.each(displayPrices, function (price, code) {
                if (newPrices[code]) {
                    displayPrices[code].amount = newPrices[code].amount - displayPrices[code].amount + customOptionsPrice;
                }
            });


            return displayPrices;
          
            },

            /**
             * Change product attributes.
             *
             * @param simpleProductId
             * @param config
             * @private
             */
            _ReplaceData: function (simpleProductId, config) {
                if (typeof config.customAttributes[simpleProductId] !== 'undefined') {
                    $.each(
                        config.customAttributes[simpleProductId], function (attributeCode, data) {
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
                                if ($block.length > 0) {
                                    $block.html(data.value);
                                }
                            }
                        }
                    );
                    if ($.isNumeric(simpleProductId) && this.options.spConfig.useCustomOptionsForVariations == 1) {
                        this._RenderCustomOptionsBySimpleProduct(simpleProductId, this);
                    }
                }
            },

            /**
             *
             * @param productId
             * @param $widget
             * @private
             */
            _RenderCustomOptionsBySimpleProduct: function (productId, $widget) {
                $.ajax(
                    {
                        url: $widget.options.spConfig.loadOptionsUrl,
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
                            $('.product-custom-option').on(
                                'change', function () {
                                    var customOptionsPrice = [];

                                    function getSum(total, num) {
                                        return total + num;
                                    }

                                    $('.product-custom-option').each(
                                        function (key, el) {
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
                                                        $(el).find(":selected").each(
                                                            function (index, selected) {
                                                                customOptionsPrice.push(parseFloat($(selected).attr('price')));
                                                            }
                                                        );
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
                                        }
                                    );
                                    $('.field.date').each(
                                        function () {
                                            var allDateValues = [];
                                            $(this).find("select").each(
                                                function (key, el) {
                                                    allDateValues.push(el.value);
                                                }
                                            );
                                            var elementId = parseInt(/[0-9]+/.exec($(this).find("select")[0]['id']));
                                            var checkOptionValues = allDateValues.every(
                                                function (element, index, array) {
                                                    return element !== "";
                                                }
                                            );
                                            if (!checkOptionValues) {
                                                customOptionsPrice.push(parseFloat(0));
                                            } else {
                                                customOptionsPrice.push(parseFloat(response.optionsData[elementId]['price']));
                                            }
                                        }
                                    );
                                    if (customOptionsPrice.length > 0) {
                                        $widget['customOptionsPrice'] = customOptionsPrice.reduce(getSum);
                                        $widget._reloadPrice();
                                        delete $widget['customOptionsPrice'];
                                    }
                                }
                            );
                        }
                    }
                );
            },
        };

        return function (ConfigurableWidget) {
            $.widget('firebear.config_icp', ConfigurableWidget, icpMixin);
            return $.firebear.config_icp;
        };
    }
);
