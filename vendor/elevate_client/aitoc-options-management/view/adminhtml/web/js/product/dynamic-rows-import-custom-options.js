/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
define([
    'Magento_Catalog/js/components/dynamic-rows-import-custom-options'
], function (DynamicRowsImportCustomOptions) {
    'use strict';

    return DynamicRowsImportCustomOptions.extend({

        /** @inheritdoc */
        processingAddChild: function (ctx, index, prop) {
            if (!ctx) {
                this.showSpinner(true);
                this.addChild(ctx, index, prop);

                return;
            }

            // add templateId to template data
            _.extend(this.templates.record, {
                templateId: ctx.template_id
            });

            this._super(ctx, index, prop);
        },
        editHandler: function (templateId) {
            window.open(this.templateUrl + 'id/' + templateId + '/', '_blank');
        }
    });
});
