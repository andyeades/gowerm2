/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
define([
    'underscore',
    'uiRegistry',
    'Magento_Catalog/js/custom-options-type'
], function (_, registry, customOptionsType) {
    'use strict';

    return customOptionsType.extend({
        /**
         * Show, hide or clear components based on the current type value.
         *
         * @param {String} currentValue
         * @param {Boolean} isInitialization
         * @returns {Element}
         */
        updateComponents: function (currentValue, isInitialization) {
            var currentGroup = this.valuesMap[currentValue];

            // change containers
            if (currentGroup !== this.previousGroup) {
                _.each(this.indexesMap, function (groups, index) {
                    var template = this.filterPlaceholder + ', index = ' + index,
                        visible = groups.indexOf(currentGroup) !== -1,
                        component;

                    switch (index) {
                        case 'container_type_static':
                        case 'values':
                        case 'container_default_text':
                            template = 'ns=' + this.ns +
                                ', dataScope=' + this.parentScope +
                                ', index=' + index;
                            break;
                    }

                    /*eslint-disable max-depth */
                    if (isInitialization) {
                        registry.async(template)(
                            function (currentComponent) {
                                currentComponent.visible(visible);
                            }
                        );
                    } else {
                        component = registry.get(template);

                        if (component) {
                            component.visible(visible);

                            /*eslint-disable max-depth */
                            if (_.isFunction(component.clear)) {
                                component.clear();
                            }
                        }
                    }
                }, this);

                this.previousGroup = currentGroup;
            }


            // process default value
            if (currentValue != 'radio' && currentValue != 'drop_down') {
                return this;
            }

            var component = registry.get('ns = '+ this.ns +', dataScope = '+ this.parentScope +', index = values');
            if (component) {
                var elems = component.elems();

                var isOneChecked = false;
                _.each(elems, function (elem) {
                    var defaultValueEl = registry.get(elem.name + '.default_value');
                    if (defaultValueEl) {

                        if (defaultValueEl.checked()) {
                            if (!isOneChecked) {
                                isOneChecked = true;
                            } else {
                                defaultValueEl.checked(0);
                            }
                        }
                    }
                }, this);
            }

            return this;
        }
    });
});
