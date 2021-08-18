define([
    'Magento_Ui/js/form/components/fieldset'
], function (Component) {
    'use strict';

    return Component.extend({
        /**
         * Show element
         *
         * @returns {Fieldset} Chainable
         */
        show: function () {
            this.visible(true);

            return this;
        },

        /**
         * Hide element
         *
         * @returns {Fieldset} Chainable
         */
        hide: function () {
            this.visible(false);

            return this;
        },
    });
});
