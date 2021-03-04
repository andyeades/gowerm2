define([
    'Magento_Ui/js/modal/modal',
    'jquery',
    'mage/validation'
], function (alert, $) {
    'use strict';

    var emailModal;

    var mpEditOrderEmailPopup = function () {
        if (!emailModal) {
            emailModal = $('#mp_edit_order_email').modal({
                title: 'Edit Email',
                content: 'Warning content',
                buttons: []
            });
        }

        emailModal.modal('openModal');
    };

    var evPrintLabels = function ( postUrl ) {

        var postData = {form_key: FORM_KEY};

        //global var configForm
        $('#shipping_form').find('input, select').serializeArray().map(function (field) {
            postData[field.name] = field.value;
        });


        $.ajax({
            url: postUrl,
            type: 'post',
            dataType: 'html',
            data: postData,
            showLoader: true
        }).done(function (response) {
        
                if (response.error == 1) {
                   alert(response.error_details)
                }
                $('#shipping_response').html(response);
           console.log(response);
        
            if (typeof response === 'object') {
         
                if (response.error) {
                    alert({ title: 'Error', content: response.message });
                } else if (response.ajaxExpired) {
                    window.location.href = response.ajaxRedirect;
                }
            } else {
                alert({
                    title:'',
                    content:response,
                    buttons: []
                });
            }

        });
    };



    return function (config) {
        var html = '<button id="mpEditOrderEmailPopup">edit</button>';
        $('table.order-account-information-table tr a[href^="mailto:"]').parent().append(html);

        $('#mpEditOrderEmailPopup').click(function () {
            mpEditOrderEmailPopup();
        });

        $('#evPrintLabels').click(function () {
        console.log("aCTIVE");
          //  if ($.validator.validateElement($("#mp_edit_order_email input[name='email']"))) {
                evPrintLabels(config.postUrl);
          //  }
        });
    }

});