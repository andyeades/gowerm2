define([
    'jquery',
    'underscore',
    'mage/template',
    'mage/translate',
    'priceUtils',
    'priceBox',
    'jquery-ui-modules/widget',
    'jquery/jquery.parsequery'
], function ($, _, mageTemplate, $t, priceUtils) {
        'use strict';
    
            return function (widget) {
                $.widget('mage.configurable', widget, {
                        /**
                          * Initialize tax configuration, initial settings, and options values.
                          * @private
                          */
                        _initializeOptions: function () {
                            var element;
            
                                element = $(this.options.priceHolderSelector);
                            if (!element.data('magePriceBox')) {
                                    element.priceBox();

                                var options = this.options,
                                    gallery = $(options.mediaGallerySelector),
                                    priceBoxOptions = $(this.options.priceHolderSelector).priceBox('option').priceConfig || null;

                                if (priceBoxOptions && priceBoxOptions.optionTemplate) {
                                    options.optionTemplate = priceBoxOptions.optionTemplate;
                                }

                                if (priceBoxOptions && priceBoxOptions.priceFormat) {

                                }

                                options.priceFormat = '{"pattern":"\u00a3%s","precision":2,"requiredPrecision":2,"decimalSymbol":".","groupSymbol":",","groupLength":3,"integerRequired":false}';
                              //  options.optionTemplate = mageTemplate(options.optionTemplate);
                                options.tierPriceTemplate = $(this.options.tierPriceTemplateSelector).html();

                                options.settings = options.spConfig.containerId ?
                                    $(options.spConfig.containerId).find(options.superSelector) :
                                    $(options.superSelector);

                                options.values = options.spConfig.defaultValues || {};
                                options.parentImage = $('[data-role=base-image-container] img').attr('src');

                                this.inputSimpleProduct = this.element.find(options.selectSimpleProduct);

                                gallery.data('gallery') ?
                                    this._onGalleryLoaded(gallery) :
                                    gallery.on('gallery:loaded', this._onGalleryLoaded.bind(this, gallery));


                                }
            
                                return this._super();
                        }
                });
        
                    return $.mage.configurable;
            };
    });