define([
    'Magento_Ui/js/grid/columns/column'
], function (Column) {
    'use strict';

    return Column.extend({
        defaults: {
            bodyTmpl: 'Aheadworks_Giftcard/ui/grid/columns/cells/product/templates'
        },

        /**
         * Retrieve template names
         *
         * @returns {Array}
         */
        getTemplateNames: function(row) {
            return row[this.index + '_names'];
        }
    });
});
