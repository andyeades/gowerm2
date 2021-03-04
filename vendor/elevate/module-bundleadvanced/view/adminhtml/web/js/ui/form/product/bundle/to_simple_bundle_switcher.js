/**
 * Copyright 2020 aheadWorks. All rights reserved.\nSee LICENSE.txt for license details.
 */

define([
    'jquery',
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/modal/confirm',
    'Magento_Ui/js/form/element/single-checkbox',
    'mage/translate'
], function ($, _, registry, confirm, Element, $t) {
    'use strict';

    return Element.extend({
        defaults: {
            toSimpleBundleSwitcherConfig: {},
            confirmText: '',
            isEditable: true,
            modules: {
                bundleOptions: 'product_form.product_form.bundle-items.bundle_options',
            },
        },
        isNeedToDisplayConfirm: true,

        /**
         * {@inheritdoc}
         */
        initialize: function () {
            this._super();
            this.invertedChecked(!this.checked());
        },

        /**
         * @inheritdoc
         */
        setInitialValue: function () {
            this.isNeedToDisplayConfirm = false;
            this._super();
            this.switchView();
            return this;
        },

        /**
         * {@inheritdoc}
         */
        initObservable: function () {
            this._super()
                .observe({
                    invertedChecked: false
                });

            return this;
        },

        /**
         * {@inheritdoc}
         */
        onCheckedChanged: function (newChecked) {
            this._super(newChecked);

            this.invertedChecked(!this.checked());
            if (this.isEditable) {
                this.displayConfirmMessage();
            }
        },

        /**
         * Display confirm message
         */
        displayConfirmMessage: function () {
            var self = this;

            if (this.isNeedToDisplayConfirm) {
                confirm({
                    content: this.confirmText,
                    actions: {
                        cancel: function () {
                            self.isNeedToDisplayConfirm = false;
                            self.checked(!self.checked());
                        },
                        confirm: function () {
                            self.switchView();
                        }
                    },
                    buttons: [
                        {
                            text: $t('Cancel'),
                            class: 'action-secondary action-dismiss',
                            click: function (event) {
                                this.closeModal(event);
                            }
                        },
                        {
                            text: $t('Continue'),
                            class: 'action-primary action-accept',
                            click: function (event) {
                                this.closeModal(event, true);
                            }
                        }
                    ]
                });
            } else {
                this.isNeedToDisplayConfirm = true;
            }
        },

        /**
         * Switch view to simple bundle or to bundle product
         */
        switchView: function () {
            if (this.bundleOptions()) {
                if (this.isEditable) {
                    this.bundleOptions().recordData([]);
                    this.bundleOptions().reload();
                }

                if (this.checked()) {
                    if (this.isEditable) {
                        this.bundleOptions().processingAddChild();
                    }

                    this
                        ._hideDeleteButtonOnFirstOption()
                        .applyRules();
                }
            }
        },

        /**
         * Apply rules
         *
         * @returns {ToSimpleBundleSwitcher} Chainable
         */
        applyRules: function () {
            var self = this;

            _.each(this.toSimpleBundleSwitcherConfig.rules, function (rule) {
                registry.get(rule.target, function (component) {
                    _.each(rule.actions, function (action) {
                        self._applyAction(component, action);
                    });
                });
            });
            return this;
        },

        /**
         * Apply rule action
         *
         * @param {Object} component
         * @param {Object} action
         * @private
         */
        _applyAction: function (component, action) {
            var callback = component[action.callback];

            callback.apply(component, action.params || []);
        },

        /**
         * Hide delete button for first bundle product option
         *
         * @returns {ToSimpleBundleSwitcher} Chainable
         */
        _hideDeleteButtonOnFirstOption: function () {
            var firstOption = this.bundleOptions().name + '.0';

            $.async('.fieldset-wrapper-title .action-delete', firstOption, function (node) {
                $(node).hide();
            });
            return this;
        }
    });
});
