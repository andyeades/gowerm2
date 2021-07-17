define(
    [
        'jquery',
        'SecureTrading_Trust/js/model/secure-trading-order',
        'Magento_Ui/js/modal/confirm',
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/model/totals',
        'ko',
        'mage/url',
        'mage/translate',
        'Magento_Checkout/js/action/redirect-on-success',
        'Magento_Customer/js/model/customer',
        'Magento_Customer/js/customer-data',
        'Magento_Checkout/js/action/select-payment-method',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/quote',
        'stUK',
        'mage/cookies',
    ],
    function (
        $,
        trustOrder,
        confirmation,
        Component,
        totals,
        ko,
        urlBuilder,
        $t,
        redirectOnSuccessAction,
        customer,
        customerData,
        selectPaymentMethodAction,
        checkoutData,
        quote
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'SecureTrading_Trust/payment/api-secure-trading',
                checkRender: ko.observable(true),
                CheckValApi: ko.observable(0),
                orderId: ko.observable(),
                st: ko.observable()
            },
            getLogoUrl: function () {
                if (!parseInt(window.checkoutConfig.payment[this.getCode()].enable_api_secure_trading_logo)) {
                    return false;
                }
                return window.checkoutConfig.payment[this.getCode()].api_secure_trading_logo;
            },
            initSecuretrading: function (orderId) {
                var self = this;
                var config = this.getConfig();
                var livestatus = (config.is_test) ? 0 : 1;
                $.ajax({
                    url: window.checkoutConfig.payment.api_secure_trading.generateJwt,
                    dataType: "json",
                    type: 'GET',
                    showLoader: true,
                    data: {
                        grandTotal: config.grandTotal,
                        currencyiso3a: config.currencyiso3a,
                        sitereference: config.sitereference,
                        accounttypedescription: config.accounttypedescription,
                        orderreference: orderId
                    },
                }).done(function (response) {
                    var st = SecureTrading({
                        jwt: response.jwt,
                        animatedCard: true,
                        livestatus: livestatus,
                        buttonId: 'pay',
                    });
                    if ((response.requesttypes === "RECURRING" || response.requesttypes === "INSTALLMENT") && !response.skip){
                        st.Components({"requestTypes":["THREEDQUERY","AUTH","SUBSCRIPTION"]});
                    }else if ((response.requesttypes === "RECURRING" || response.requesttypes === "INSTALLMENT") && response.skip){
                        st.Components({"requestTypes":["THREEDQUERY","ACCOUNTCHECK","SUBSCRIPTION"]});
                    }else{
                        (config.accountcheck === "1") ? st.Components({"requestTypes":["ACCOUNTCHECK","THREEDQUERY","AUTH"]}) : st.Components();
                    }

                    if (!$('.separator').length) {
                        if(config.activevisacheckout === "1" || config.activeapplepay === "1" || self.enablePayMentPayPal()){
                            $("div[id=separator]").append('<div class="separator">OR</div>');
                        }

                        if(self.enablePayMentPayPal()){
                            $('.payment-method-paypal').show();
                        }

                        if(config.activevisacheckout === "1"){
                            $('#st-visa-checkout').show();
                            st.VisaCheckout({
                                buttonSettings: {
                                    size: '154',
                                    color: 'neutral'
                                },
                                merchantId: config.merchantid,
                                paymentRequest: {
                                    "currencyCode": config.currencyiso3a,
                                    "subtotal": response.mainamount,
                                    "total":  response.mainamount,
                                },
                                placement: 'st-visa-checkout',
                                settings: {
                                    displayName: config.namesite
                                }
                            });
                        }
                        if(config.activeapplepay === "1") {
                            $('#st-apple-pay').show();
                            st.ApplePay({
                                buttonStyle: 'white-outline',
                                buttonText: 'buy',
                                merchantId: config.applemerchantid,
                                paymentRequest: {
                                    countryCode: 'US',
                                    currencyCode: config.currencyiso3a,
                                    merchantCapabilities: ['supports3DS', 'supportsCredit', 'supportsDebit'],
                                    supportedNetworks: ["visa", "masterCard"],
                                    requiredBillingContactFields: ["postalAddress"],
                                    requiredShippingContactFields: ["postalAddress", "name", "phone", "email"],
                                    total: {
                                        label: 'Trust Payments Merchant',
                                        amount: response.mainamount
                                    }
                                },
                                placement: 'st-apple-pay'
                            });
                        }
                        self.st(st);
                    }
                }).fail(function (err) {
                    console.log(err);
                });
            },
            getConfig: function(){
                return {
                    "grandTotal" : parseFloat(totals.totals()['grand_total']),
                    "currencyiso3a": window.checkoutConfig.payment.api_secure_trading.currencyiso3a,
                    "sitereference": window.checkoutConfig.payment.api_secure_trading.sitereference,
                    "accounttypedescription": window.checkoutConfig.payment.api_secure_trading.accounttypedescription,
                    "accountcheck": window.checkoutConfig.payment.api_secure_trading.accountcheck,
                    "activevisacheckout": window.checkoutConfig.payment.api_secure_trading.active_visa_checkout,
                    "merchantid": window.checkoutConfig.payment.api_secure_trading.merchant_id,
                    "namesite": window.checkoutConfig.payment.api_secure_trading.name_site,
                    "activeapplepay": window.checkoutConfig.payment.api_secure_trading.active_apple_pay,
                    "applemerchantid": window.checkoutConfig.payment.api_secure_trading.apple_merchant_id,
                    "is_test": window.checkoutConfig.payment.api_secure_trading.is_test
                    };
            },
            getCode: function () {
                return 'api_secure_trading';
            },

            getCardUrl: function () {
                return urlBuilder.build('securetrading/apisecuretrading/cardurl');
            },

            getData: function () {
                var dataAlePay = $("#placeOrder").val();
                var data = {
                    'method': this.item.method,
                    'additional_data': {
                        'payment_action': window.checkoutConfig.payment.api_secure_trading.payment_action,
                        'api_secure_trading_data': dataAlePay,
                        'save_card_info_api': this.CheckValApi()
                    }
                };

                data['additional_data'] = _.extend(data['additional_data'], this.additionalData);

                return data;
            },

            saveCardInfoTitle: function() {
                var self = this;

                var title = window.checkoutConfig.payment[self.getCode()].saveTitleQuestion;

                if (!title)
                    title = "Save Card Information";

                return title
            },

            checkIsSaveCardInfo: function() {
                var self = this;

                if (window.checkoutConfig.payment[self.getCode()].isSaveCardInfo != 1)
                    return false;

                return customer.isLoggedIn();
            },

            onCheckedChange: function () {
                var self = this;
                if(this.CheckValApi() === 0)
                    this.CheckValApi(1);
                else
                    this.CheckValApi(0);
                $.ajax({
                    url: urlBuilder.build('securetrading/apisecuretrading/saveCardInfo'),
                    dataType: "json",
                    type: 'POST',
                    showLoader: true,
                    data: {
                        order_id: trustOrder.getOrderId(),
                        is_save: this.CheckValApi(),
                    },
                }).done(function (response) {
                    var jwt = response.jwt;
                    var st = self.st();
                    st.updateJWT(jwt);
                });
            },
            placeOrder: function () {
                var self = this;
                this.getPlaceOrderDeferredObject()
                    .done(function (orderID) {
                        trustOrder.setOrderID(orderID);
                        self.orderId(orderID);
                        var id = self.getCode();
                        self.disableRadioPayment(id);
                        if(!$("form[class="+id+"] #st-security-code").length){
                            $("<div id='st-notification-frame'></div>").insertBefore($(".wallet-button"));
                            $("form[class="+id+"]").attr('id','st-form');
                            $("form[class="+id+"]").append(
                                "<div class='st-wallet-button'></div>"+
                                "<div id='st-card-number' class='st-card-number'></div>" +
                                "<div id='security_expiry_container'>\n" +
                                "                <div id='st-expiration-date' class='half-width float-left padding-right'></div>\n" +
                                "                <div id='st-security-code' class='half-width float-left'></div>\n" +
                                "            </div>"+
                                "<div class='clear-both'></div>\n" +
                                " <button type='submit' id='pay' class='st-form__submit'>Pay securely</button>\n" +
                                "</form>");
                            var check = self.checkRenderAnimatedCard();
                            if(check){
                                $(".separator").show();
                                $("div[class=clear-both]").append(
                                    "<div id='st-animated-card' class='st-animated-card-wrapper' style='overflow-y: hidden;' ></div>");
                            }
                        };
                        $("#restore-quote").show();
                        $("#save-card-info-api").show();
                        if($("#st-animated-card").length){
                            $("#st-animated-card").css("height", '330px');
                        }
                        self.initSecuretrading(orderID);
                    });
            },
            restoreQuote: function() {
                $.ajax({
                    url: urlBuilder.build('securetrading/apisecuretrading/restorequote'),
                    dataType: "json",
                    type: 'POST',
                    showLoader: true,
                    data: {
                        orderId: this.orderId()
                    },
                }).done(function (response) {
                    window.location.reload();
                });
            },
            getUrlToPayPal: function () {
                var self = this;
                if (self.orderId()) {
                    var url = urlBuilder.build('securetrading/apisecuretrading/GetUrlRedirectPayPal');
                    $('body').trigger("processStart");
                    $.ajax({
                        url: url,
                        dataType: "json",
                        type: 'GET',
                        showLoader: true,
                        data: {
                            orderId: self.orderId()
                        },
                    }).done(function (response) {
                        window.location.href = response.redirecturl;
                    });
                    setTimeout(function () {
                        $('body').trigger("processStop");
                    }, 4000);
                } else {
                    this.getPlaceOrderDeferredObject()
                        .done(function (orderID) {
                            var url = urlBuilder.build('securetrading/apisecuretrading/GetUrlRedirectPayPal');
                            $('body').trigger("processStart");
                            $.ajax({
                                url: url,
                                dataType: "json",
                                type: 'GET',
                                showLoader: true,
                                data: {
                                    orderId: orderID
                                },
                            }).done(function (response) {
                                window.location.href = response.redirecturl;
                            });
                            setTimeout(function () {
                                $('body').trigger("processStop");
                            }, 4000);
                        });
                }
            },
            selectPaymentMethod: function () {
                var self = this;
                selectPaymentMethodAction(this.getData());
                checkoutData.setSelectedPaymentMethod(this.item.method);
                if($(".vault #st-security-code").length){
                    $(".vault #st-form").empty();
                }
                setTimeout(function () {
                    self.placeOrder();
                }, 2000);

                // this.checkSaveCard();
                return true;
            },
            checkRenderAnimatedCard: function () {
                var render = window.checkoutConfig.payment.api_secure_trading.animated_card;
                if(render != 1) return false;
                return true;
            },
            disableRadioPayment: function (id) {
                // loop through list of radio buttons
                $('input[name="payment[method]"]').each(function () {
                    if (this.value !== id) {
                        this.checked = false; // unchecked
                        this.disabled = true; // disable
                    }
                });
            },
            enablePayMentPayPal: function () {
                var check = window.checkoutConfig.payment.api_secure_trading.active_paypal_payment;
                (check === "0") ? check = false : check = true;
                var data = window.checkoutConfig.quoteItemData[0];
                var options = data.options;
                if (options.length) {
                    $.each(options, function (key, value) {
                        if (check === false || value.label === "Transaction Details") {
                            check = false;
                        } else check = true;
                    });
                }
                return check;
            },
            checkSaveCard: function () {
                var self = this;
                if (this.checkIsSaveCardInfo()) {
                    confirmation({
                        title: 'Accept save your card',
                        content: 'Do you want to save your card?',
                        actions: {
                            confirm: function () {
                                self.CheckValApi(1);
                                self.placeOrder()
                            },
                            cancel: function () {
                                self.CheckValApi(0);
                                self.placeOrder()
                            }
                        }
                    });
                } else {
                    this.placeOrder()
                }
            }
        });
    }
);
