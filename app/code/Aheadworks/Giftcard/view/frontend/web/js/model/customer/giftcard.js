define([
    'ko'
], function (ko) {
    'use strict';
    return {
        giftcardCodes: ko.observableArray([]),
        isLoading: ko.observable(false)
    };
});
