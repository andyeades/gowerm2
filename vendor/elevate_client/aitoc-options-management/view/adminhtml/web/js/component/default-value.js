/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
define([
    'Magento_Ui/js/form/element/single-checkbox',
    'jquery',
    'uiRegistry'
], function (Checkbox, $, registry) {
    'use strict';

    return Checkbox.extend({
        defaults: {
            inputClass : ''
        },
        optionTypeUpdated: function (currentValue) {

            if (currentValue == 'radio' || currentValue == 'drop_down') {
                var removeClass = 'admin__control-checkbox';
                var addClass = 'admin__control-radio';
            } else {
                var removeClass = 'admin__control-radio';
                var addClass = 'admin__control-checkbox';
            }

            this.inputClass = addClass;

            var uiEl = $('#' + this.uid);
            uiEl.removeClass(removeClass);
            uiEl.addClass(addClass);

        },

        onCheckedChanged: function (newChecked) {
            this._super(newChecked);

            if (newChecked && this.inputClass == 'admin__control-radio') {
                var parts = this.parentName.split('.');
                parts.splice(-1);
                var valuesName = parts.join('.');
                var component = registry.get(valuesName);
                if (component) {
                    var elems = component.elems();
                    _.each(elems, function (elem) {
                        var defaultValueEl = registry.get(elem.name + '.default_value');
                        if (defaultValueEl && defaultValueEl.name != this.name) {
                            if (defaultValueEl.checked()) {
                                defaultValueEl.checked(0);
                            }
                        }
                    }, this);
                }
            }
        }
    });
});
