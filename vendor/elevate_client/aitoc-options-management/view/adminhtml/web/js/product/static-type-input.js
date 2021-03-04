/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
define([
    'uiRegistry',
    'Magento_Catalog/component/static-type-input'
], function (registry, StaticTypeInput) {
    'use strict';

    return StaticTypeInput.extend({
        defaults: {
            templateId: false,
            templateTitle: '',
            imports: {
                templateTitle: '${ $.provider }:${ $.parentScope }.template_title',
                templateId: '${ $.provider }:${ $.parentScope }.template_id'
            }
        },

        /**
         * Cache link to parent component - option holder.
         *
         * @returns {Element}
         */
        initLinkToParent: function () {
            var pathToParent = this.parentName.replace(/(\.[^.]*){2}$/, '');
            this.parentOption = registry.async(pathToParent);

            if (this.value()) {
                this.parentOption(
                    'label',
                    (this.templateTitle ? this.templateTitle + ': ' : '') + this.value()
                );
            }

            return this;
        }
    });
});
