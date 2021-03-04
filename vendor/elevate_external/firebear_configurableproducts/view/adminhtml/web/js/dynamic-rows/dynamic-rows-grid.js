define([
    'jquery',
    'ko',
    'underscore',
    'mage/translate',
    'Magento_Bundle/js/components/bundle-dynamic-rows-grid'
], function ($, ko, _,$t,dynamicRowsGrid) {
    'use strict';
    return dynamicRowsGrid.extend({

        /**
         * Initialize elements from grid
         *
         */
        initElements: function (data) {
            this._super();
            var bundleOptionNumber = parseInt(/[0-9]+/.exec(this.dataScope));
            this.activeOrInactiveMultipleOption(data, bundleOptionNumber);

            return this;
        },

        activeOrInactiveMultipleOption: function(data, bundleOptionNumber) {
            var parentSelect = $('option[value=select]').closest('select');
            $.each(data, function(index, value) {
                if (value.productType == 'configurable') {
                    $('select[name="bundle_options[bundle_options][' + bundleOptionNumber + '][type]"] > option[value=multi]').remove();
                    $(parentSelect).trigger('change');
                    return false;
                } else {
                    if ($('select[name="bundle_options[bundle_options][' + bundleOptionNumber + '][type]"] > option[value=multi]').length == 0) {
                        $('select[name="bundle_options[bundle_options][' + bundleOptionNumber + '][type]"]').append('<option data-title="Multiple Select" value="multi">' + $.mage.__('Multiple Select') + '</option>');
                    }
                }
            });

        }
    });
});