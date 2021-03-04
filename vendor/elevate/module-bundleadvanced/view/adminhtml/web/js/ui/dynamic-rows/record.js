/**
 * Copyright 2020 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'Magento_Ui/js/dynamic-rows/record'
], function (Element) {
    'use strict';

    return Element.extend({

        /**
         * {@inheritdoc}
         */
        setVisibilityColumn: function (index, state) {
            var elems = this.elems();

            if (elems.length) {
                this._super(index, state);
            }
        },
    });
});
