define(
    [
        'jquery',
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Magento_Checkout/js/action/redirect-on-success'
    ],
    function (
        $,
        Component,
        additionalValidators,
        redirectOnSuccessAction
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'SecureTrading_Trust/payment/secure-trading',
            },
            getCode: function () {
                return 'secure_trading';
            },
            getInstructions: function () {
                var self = this;
                if(window.checkoutConfig.payment[self.getCode()].instruction != null){
                    return window.checkoutConfig.payment[self.getCode()].instruction;
                }
                return '';
            },
            placeOrder: function (data, event) {
                var self = this;

                if (event) {
                    event.preventDefault();
                }

                if (this.validate() && additionalValidators.validate()) {
                    this.isPlaceOrderActionAllowed(false);

                    this.getPlaceOrderDeferredObject()
                        .fail(function () {
                            self.isPlaceOrderActionAllowed(true);
                        }).done(function (orderID) {
                            var isFrame = window.checkoutConfig.payment[self.getCode()].isIframe;
                            if (isFrame == "1") {
                                var $form = $("<form>")
                                    .attr("method", "POST")
                                    .attr("action", window.checkoutConfig.payment[self.getCode()].iframeUrl+'/orderId/'+orderID);
                                                                        
                                $form.append($("<input type='hidden'>").attr("name", "orderId").val(orderID));
                                $('body').append($form);
                                $form.submit();
                            } else {
                                $.ajax({
                                    url: window.checkoutConfig.payment[self.getCode()].startUrl,
                                    dataType: "json",
                                    type: 'POST',
                                    showLoader: true,
                                    data: {
                                        order_id: orderID
                                    }
                                }).done(function (response) {
                                    var info = response.info,
                                        $form = $("<form>")
                                            .attr("method", "POST")
                                            .attr("action", response.url);
                                    $form.append($("<input type='hidden'>").attr("name", "_charset_"));
                                    $.each(info, function (key, value) {
                                        if (key == 'ruleidentifiers') {
                                            $.each(value, function (key, value) {
                                                var $hiddenField = $("<input type='hidden'>")
                                                    .attr("name", "ruleidentifier")
                                                    .val(value);
                                                $form.append($hiddenField);
                                            })
                                        }
                                        else if (key == 'stextraurlnotifyfields') {
                                            $.each(value, function (key, value) {
                                                var $hiddenField = $("<input type='hidden'>")
                                                    .attr("name", "stextraurlnotifyfields")
                                                    .val(value);
                                                $form.append($hiddenField);
                                            })
                                        }
                                        else {
                                            var $hiddenField = $("<input type='hidden'>")
                                                .attr("name", key)
                                                .val(value);
                                            $form.append($hiddenField);
                                        }
                                    });
                                    $('body').append($form);
                                    $form.submit();
                                }).fail(function (err) {
                                    console.log(err);
                                });
                            }
                            self.afterPlaceOrder();
                        }
                    );
                    return true;
                }
                return false;
            },
        });
    }
);