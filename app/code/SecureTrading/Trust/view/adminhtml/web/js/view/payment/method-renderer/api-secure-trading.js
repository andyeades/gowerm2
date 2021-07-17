define(
    [
        'jquery',
        'uiComponent',
        'ko',
        'mage/url',
        'stUK'
    ],
    function (
        $,
        Component,
        ko,
        urlBuilder,
    ) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'SecureTrading_Trust/payment/api-secure-trading',
                checkRender: ko.observable(true),
                CheckValApi: ko.observable(0),
            },
            initialize: function () {
                this._super();
                this.buttonFake();
            },
            initSecuretrading: function (orderId) {
                $("#Continue").hide();
                var self = this;
                var grandTotal = this.orderData.grandTotal;
                var currencyiso3a = this.orderData.currencyiso3a;
                var sitereference = this.orderData.sitereference;
                var accounttypedescription = this.orderData.accounttypedescription;
                var livestatus = (this.orderData.is_test) ? 0 : 1;
                var accountcheck = this.orderData.accountcheck;
                $.ajax({
                    url: self.orderData.generateJwt,
                    dataType: "json",
                    type: 'GET',
                    showLoader: true,
                    data: {
                        grandTotal: grandTotal,
                        currencyiso3a: currencyiso3a,
                        sitereference: sitereference,
                        accounttypedescription: accounttypedescription,
                        orderreference: orderId
                    },
                }).done(function (response) {
                    var st = SecureTrading({
                        jwt: response.jwt,
                        animatedCard: true,
                        livestatus: livestatus,
                    });
                    if (response.requesttypes === "RECURRING"){
                        st.Components({"requestTypes":["THREEDQUERY","AUTH","SUBSCRIPTION"]});
                    }else if (response.requesttypes === "INSTALLMENT"){
                        st.Components({"requestTypes":["THREEDQUERY","ACCOUNTCHECK","SUBSCRIPTION"]});
                    }else{
                        st.Components({"requestTypes":["ACCOUNTCHECK","AUTH"]});
                    }
                    $("#st-form__submit").show();
                }).fail(function (err) {
                    console.log(err);
                });
            },
            getCode: function () {
                return 'api_secure_trading';
            },
            getCardUrl: function () {
                return this.orderData.cardUrl;
            },
            buttonFake: function(){
                var self = this;
                $('body').trigger("processStart");
                setTimeout(function () {
                    self.initSecuretrading(self.orderData.orderId);
                    $('body').trigger("processStop");
                }, 4000);
            },
            checkRenderAnimatedCard: function () {
                var render = this.orderData.animated_card;
                if(render != 1) return false;
                return true;
            },
        });
    }

);
