/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
/* global $, $H */
define([
    'mage/adminhtml/grid'
], function () {
    'use strict';

    return function (config) {
        var selectedProducts = config.selectedProducts,
            templateProducts = $A(selectedProducts),
            gridJsObject = window[config.gridJsObjectName];

        //console.log(gridJsObject);

        $('in_template_products').value = Object.toJSON(templateProducts);

        /**
         * Register Template Product
         *
         * @param {Object} grid
         * @param {Object} element
         * @param {Boolean} checked
         */
        function registerTemplateProduct(grid, element, checked) {
            if (checked) {
                templateProducts.push(element.value);
            } else {
                templateProducts = templateProducts.without(element.value);
            }

            //console.log(templateProducts);

            $('in_template_products').value = Object.toJSON(templateProducts);
            grid.reloadParams = {
                'selected_products[]': templateProducts //templateProducts.keys()
            };
        }

        /**
         * Click on product row
         *
         * @param {Object} grid
         * @param {String} event
         */
        function templateProductRowClick(grid, event) {
            var trElement = Event.findElement(event, 'tr'),
                isInput = Event.element(event).tagName === 'INPUT',
                checked = false,
                checkbox = null;

            if (trElement) {
                checkbox = Element.getElementsBySelector(trElement, 'input');

                if (checkbox[0]) {
                    checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
                    gridJsObject.setCheckboxChecked(checkbox[0], checked);
                }
            }
        }

        gridJsObject.rowClickCallback = templateProductRowClick;
        gridJsObject.checkboxCheckCallback = registerTemplateProduct;
    };
});
