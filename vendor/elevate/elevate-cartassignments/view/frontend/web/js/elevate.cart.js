var ELEVATE = ELEVATE || {};

// wrap code in a closure to allow use of $
(function ($) {

    ELEVATE.Assignments = (function () {

        var clickTimeout;

        init = function () {
            attachEventHandlers();
        },
            attachEventHandlers = function () {


                jQuery('.change_addon_qty').off('click').on('click', function () {


                    clearTimeout(clickTimeout);


                    var quote_item_assignment_id = jQuery(this).parent().find("input").attr("data-quote_item_assignment_id");
                    var qty = jQuery(this).parent().find("input").val();
                    var type = jQuery(this).attr("data-type");

                    jQuery('.btn-update').css('display', 'block');


                    if (type == 'increment') {
                        var qty = parseFloat(qty) + 1;
                    } else if (type == 'decrement') {
                        var qty = parseFloat(qty) - 1;
                    } else {
                        return;
                    }

                    //increment the input box value
                    jQuery(this).parent().find("input").val(qty);

                    clickTimeout = setTimeout(function () {

                        //prevent ajax caching
                        var tS = new Date().getTime();
                        var url = '/ev_cartassignments/addon/updateaddonq' + '?ts=' + tS + '&qty=' + qty + '&assignment_id=' + quote_item_assignment_id;
                        var data = ''

                        try {
                            // var searchbuttonwidth = jQuery('#rowprice_'+itemid).outerWidth();

                            //update line totals
                            //  jQuery('#rowprice_'+itemid).css({"min-width": searchbuttonwidth});
                            // jQuery('#rowprice_'+itemid).html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});

                            //update cart totals
                            //     var searchbuttonwidth = jQuery("#ev_cart_totals").outerWidth();
                            //    jQuery("#ev_cart_totals").css({"min-width": searchbuttonwidth});
                            //  jQuery('#ev_cart_totals').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});

                            //update product main row price
                            //   var searchbuttonwidth = jQuery('#rowpriceprod_'+itemid+'_'+prodid).outerWidth();
                            //    jQuery('#rowpriceprod_'+itemid+'_'+prodid).css({"min-width": searchbuttonwidth});
                            //   jQuery('#rowpriceprod_'+itemid+'_'+prodid).html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});


                            jQuery.ajax({
                                url: url,
                                dataType: 'json',
                                type: 'get',
                                success: function (data) {

                                    //instead - lets not update the whole thing
                                    //lets update the addons
                                    //lets update the price
                                    //lets update the cart total
//console.log(data.error);
                                    //  jQuery('#tritemtotal').html(data.grand_total_inc_vat);
                                    //  jQuery('#tritemquantity').html(data.cart_quantity);
                                    //   addItemToCart(1292805, 1000625, 'add', '', false, '1');
                                    //  jQuery('.hdr-mybasket').html(data.sidebar);
                                    //  jQuery('#ev_cart_totals').html(data.cart_totals);

                                    //update Addons for line item
                                    // jQuery('#rowprice_'+data.row_id).html('<span class="price origprice" data-orig-price="'+data.row_total+'">&pound;'+data.row_total+'</span>');
                                    getAddons(data.parent_quote_item_id);
                                }
                            });
                        } catch (e) {
                        }


                    }, 500);


                });

            }

        return {
            init: function () {
                init();
            },
            attachEventHandlers: function () {
                attachEventHandlers();
            }
        };
    }());

}(jQuery.noConflict()));


//Initialise
//Elevate.Assignments.init();


function getTimeRemaining(endtime) {
    var t = Date.parse(endtime) - Date.parse(new Date());
    var seconds = Math.floor((t / 1000) % 60);
    var minutes = Math.floor((t / 1000 / 60) % 60);
    var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
    var days = Math.floor(t / (1000 * 60 * 60 * 24));
    return {
        'total': t,
        'days': days,
        'hours': hours,
        'minutes': minutes,
        'seconds': seconds
    };
}

function initializeClock(id, endtime) {
    var clock = jQuery("." + id);
    var daysSpan = jQuery(".days");
    var hoursSpan = jQuery(".hours");
    var minutesSpan = jQuery(".minutes");
    var secondsSpan = jQuery(".seconds");

    function updateClock() {
        var t = getTimeRemaining(endtime);

        daysSpan.each(function () {
            jQuery(this).html(t.days);
        });
        hoursSpan.each(function () {
            jQuery(this).html(('0' + t.hours).slice(-2));
        });
        minutesSpan.each(function () {
            jQuery(this).html(('0' + t.minutes).slice(-2));
        });
        secondsSpan.each(function () {
            jQuery(this).html(('0' + t.seconds).slice(-2));
        });


        if (t.total <= 0) {
            clearInterval(timeinterval);
        }
    }

    updateClock();
    var timeinterval = setInterval(updateClock, 1000);
}


/* Call the available addon products via ajax */
function getAddons(item_id, product_id) {


    var tS = new Date().getTime();
    var product_id = jQuery('#bitem_' + item_id).attr('data-prodid');


    var url = '/ev_cartassignments/addon/getaddons?itemid=' + item_id + '&product_id=' + product_id + '&ts=' + tS;


    var data;
    //var searchbuttonwidth = jQuery('#rowpriceprod_'+item_id+'_'+prod_id).outerWidth();
    // jQuery('#rowpriceprod_'+item_id+'_'+prod_id).css({"min-width": searchbuttonwidth});
    // jQuery('#rowpriceprod_'+item_id+'_'+prod_id).html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});

    jQuery.ajax({
        url: url,
        dataType: 'json',
        type: 'get',
        data: data,
        success: function (data) {
            jQuery("#mattcontainer_" + item_id).html(data.list);
            jQuery("#leftside_" + item_id).html(data.left_side);

            //update the mobile pricing for the totals
            ELEVATE.Minicart.getCart();

            if (data.mobile_price_update) {
                jQuery.each(data.mobile_price_update, function (index, value) {
                    jQuery("#rowprice_" + index + " .origprice").html(data.price_update);
//if(value){

                    var origprice = jQuery("#rowprice_" + index + " .origprice").attr('data-price');
                    // console.log(index);
                    // console.log(data.mobile_price_update);
                    console.log(parseFloat(value));
                    console.log(parseFloat(origprice));
                    // Umm .cart-price-mobile doesn't exist in m2
                    jQuery("#rowprice_" + index + " .price").html(value);

                    //jQuery("#rowprice_"+index+" .cart-price-mobile").html("&pound;"+(parseFloat(value)).toFixed(2));
//}
                });

            }
            updateTotalsBlock();

        },
        error: function (data) {
            console.log(data);
        }
    });

}

function addMultiItemPopup(item_id, prod_id, type, addon_id) {
    var tS = new Date().getTime();
    var url = '/ev_cartassignments/addon/mattress?itemid=' + item_id + '&addon_id=' + addon_id + '&prodid=' + prod_id + '&type=' + type + '&ts=' + tS;

    var data;

    jQuery.ajax({
        url: url,
        dataType: 'json',
        type: 'get',
        data: data,
        success: function (data) {

            jQuery("#multicontainer_" + item_id).html(data.list);


        },
        error: function (data) {
            console.log(data);
        }
    });

}

/*Add / Remove the addon product */
function addItemToCart(product_id, parent_id, type, remove_id, postcode, addon_id, element_id, checktype, price_id) {
    var tS = new Date().getTime();
    postcode = jQuery('#' + parent_id + '_postcode').val();
    if (checktype == 'postcode') {


        var searchbuttonwidth = jQuery('#' + element_id).outerWidth();

        jQuery('#' + element_id).html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});
    }
    if (checktype == 'check') {
        var searchbuttonwidth = jQuery('#' + price_id).outerWidth();

        jQuery('#' + price_id).html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});


    }
    var alertmessage = jQuery('#' + parent_id + '_alertmessage');
    var url = '/ev_cartassignments/addon/addaddon?product_id=' + product_id + '&addon_id=' + addon_id + '&quote_item_id_parent=' + parent_id + '&type=' + type + '&ts=' + tS + '&pc=' + postcode;

    if (postcode && !isValidPostcode(postcode)) {
        alertmessage.html("Invalid Postcode");
        return;

    }

    var data = ''

    try {
        var searchbuttonwidth = jQuery("#ev_cart_totals").outerWidth();
        //console.log(searchbuttonwidth);
        jQuery("#ev_cart_totals").css({"min-width": searchbuttonwidth});
        //   jQuery('#ev_cart_totals').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});

        console.log(url);

        jQuery.ajax({
            url: url,
            dataType: 'json',
            type: 'get',
            success: function (data) {
                // console.log(data.status);

                if (data.status == 'ERROR') {
                    console.log("OUTPUT_EROR");

                    jQuery('.prod_' + data.lookup_id + ' .col-md-10 .replacewrap').html('This service is not available in your postcode <div id="tryanotherpostcode" onclick="checkPostcodeArea(\'#mattcontainer_' + data.quote_item_id_parent + ' .prod_' + data.lookup_id + ' .col-md-10 .replacewrap\', \'' + product_id + '\', \'' + data.quote_item_id_parent + '\', \'' + data.addon_id + '\');">Try another postcode</div>');

                } else {
                    jQuery('#current_postcode').val(postcode);
                    //jQuery('#tritemtotal').html(data.grand_total_inc_vat);
                    //jQuery('#tritemquantity').html(data.cart_quantity);
                    //update totals
                    // jQuery('#ev_cart_totals').html(data.cart_totals);
                    // jQuery('.hdr-mybasket').html(data.sidebar);

                    getAddons(parent_id);
                    // console.log("HERE");
                    //update Addons for line item
                }
            }
        });
    } catch (e) {
    }

}


/* Donation Addon*/

function addDonationToCart() {
    var tS = new Date().getTime();


    var searchbuttonwidth = jQuery('#donation_button').outerWidth();
    jQuery('#donation_button').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});


    var donation_amount = jQuery('#ev_donation_value').find(':selected').val();
    var donation_type = jQuery('#ev_donation_value').find(':selected').data('id');

    // var alertmessage = jQuery('#'+parent_id+'_alertmessage');
    var url = '/ev_cartassignments/addon/adddonation?amount=' + donation_amount + '&type=' + donation_type;


    var data = ''

    try {
        var searchbuttonwidth = jQuery("#ev_cart_totals").outerWidth();
        //console.log(searchbuttonwidth);
        jQuery("#ev_cart_totals").css({"min-width": searchbuttonwidth});
        //   jQuery('#ev_cart_totals').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});

        console.log(url);

        jQuery.ajax({
            url: url,
            dataType: 'json',
            type: 'get',
            success: function (data) {
                // console.log(data.status);

                if (data.status == 'ERROR') {
                    //  console.log("OUTPUT_EROR");

                    //  jQuery('.prod_'+data.lookup_id + ' .col-md-10 .replacewrap').html('This service is not available in your postcode <div id="tryanotherpostcode" onclick="checkPostcodeArea(\'#mattcontainer_' + data.quote_item_id_parent + ' .prod_'+data.lookup_id + ' .col-md-10 .replacewrap\', \''+product_id+'\', \'' + data.quote_item_id_parent + '\', \'' + data.addon_id + '\');">Try another postcode</div>');

                } else {
                    location.reload(true);
                    //jQuery('#tritemtotal').html(data.grand_total_inc_vat);
                    //jQuery('#tritemquantity').html(data.cart_quantity);
                    //update totals
                    // jQuery('#ev_cart_totals').html(data.cart_totals);
                    // jQuery('.hdr-mybasket').html(data.sidebar);

                    //   getAddons(parent_id);
                    // console.log("HERE");
                    //update Addons for line item
                }
            }
        });
    } catch (e) {
    }

}

/*End Donation Addon*/
function removeAddon(assignment_id, price_id) {
    var tS = new Date().getTime();

    var searchbuttonwidth = jQuery('#' + price_id).outerWidth();
    jQuery('#' + price_id).html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});

    var url = '/ev_cartassignments/addon/removeaddon?assignment_id=' + assignment_id + '&ts=' + tS;
    var data = ''

    try {


        jQuery.ajax({
            url: url,
            dataType: 'json',
            type: 'get',
            success: function (data) {

                //jQuery('#tritemtotal').html(data.grand_total_inc_vat);
                //jQuery('#tritemquantity').html(data.cart_quantity);
                //jQuery('.col-inner').html(data.cart_items);
                //window.location.href = data.redirect_url;
                // jQuery('#ev_cart_totals').html(data.cart_totals);


                // jQuery('.hdr-mybasket').html(data.sidebar);
                getAddons(data.parent_id);
            }
        });
    } catch (e) {
    }

}


function removeProduct(product_id, qty, allow) {


    if (!allow) {

        //are you sure popup
        var htmlOutput = "<p style=\"display: block;text-align: center;font-size: 15px;\">Are you sure you wish to remove this product</p>";

        htmlOutput += '<div class="add-to-cart">';
        htmlOutput += '<div onclick="jQuery("#infoModal").bsmodal(\'hide\');" class="button btn-continueshop" data-dismiss="bs-modal">Continue Shopping</div>';
        htmlOutput += '<div  onclick="removeProduct(\'' + product_id + '\', \'' + qty + '\', true)" type="button" title="Remove Product" class="button btn-rem">Yes - Remove Product</div>';
        htmlOutput += '</div>';

        //   htmlOutput += '<div onclick="removeProduct(\''+product_id+'\', \''+qty+'\', true)">Yes</div><div>No</div>'
        jQuery("#infoModal .bs-modal-title").html("Are you sure?");
        jQuery("#infoModal .bs-modal-body").html(htmlOutput);
        // jQuery("#infoModal").modal('show');

        var body_type, body, title_type, title, footer_type, footer, size, json;

        title = "Are you sure?";
        body = htmlOutput;
        body_type = 'inline';
        ELEVATE.Lightbox.openLightbox(body_type, body, title_type, title, footer_type, footer);

        return;
    }
    jQuery("#elevateLightbox").bsmodal('hide');

    var tS = new Date().getTime();
    ELEVATE.Spinner.getSpinner('.add-to-cart .btn-rem');
    var url = '/ev_cartassignments/addon/removeproduct?productid=' + product_id + '&ts=' + tS;
    var data = ''

    try {


        jQuery.ajax({
            url: url,
            dataType: 'json',
            type: 'get',
            success: function (data) {

                location.reload(true);
            }
        });
    } catch (e) {
    }

}


var clickTimeout;


jQuery(document).ready(function () {


    jQuery('.increment_qty').click(function () {

        clearTimeout(clickTimeout);
        var itemid = jQuery(this).attr("data-itemid");
        var parentid = jQuery(this).attr("data-parentid");
        var type = jQuery(this).attr("data-type");
        var prodid = jQuery(this).attr("data-prodid");

        var max = jQuery(this).attr("data-max");
        jQuery('.btn-update').css('display', 'block');
        var oldVal = jQuery(this).parent().find("input").val();
        var newVal = parseFloat(oldVal) + 1;
        if (parseFloat(oldVal) >= 1) {

            jQuery(this).parent().find("input").val(newVal);

            clickTimeout = setTimeout(function () {

                var tS = new Date().getTime();

                var url = '/ev_cartassignments/addon/updateq' + '?ts=' + tS + '&type=' + type + '&qty=' + newVal + '&parentid=' + parentid + '&itemid=' + itemid + '&ctype=increase';
                var data = ''

                try {
                    var searchbuttonwidth = jQuery('#rowprice_' + itemid + ' .cart-price').outerWidth();
                    //console.log(searchbuttonwidth);
                    jQuery('#rowprice_' + itemid + ' .cart-price').css({"min-width": searchbuttonwidth});
                    jQuery('#rowprice_' + itemid + ' .cart-price').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});

                    var searchbuttonwidth = jQuery("#ev_cart_totals").outerWidth();
                    //console.log(searchbuttonwidth);
                    jQuery("#ev_cart_totals").css({"min-width": searchbuttonwidth});
                    //  jQuery('#ev_cart_totals').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});

                    var searchbuttonwidth = jQuery('#rowpriceprod_' + itemid + '_' + prodid).outerWidth();

                    jQuery('#rowpriceprod_' + itemid + '_' + prodid).css({"min-width": searchbuttonwidth});
                    jQuery('#rowpriceprod_' + itemid + '_' + prodid).html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});


                    jQuery.ajax({
                        url: url,
                        dataType: 'json',
                        type: 'get',
                        success: function (data) {

                            //instead - lets not update the whole thing
                            //lets update the addons
                            //lets update the price
                            //lets update the cart total
                            // jQuery('#ev_cart_totals').html(data.cart_totals);

                            // jQuery('#tritemtotal').html(data.grand_total_inc_vat);
                            // j//Query('#tritemquantity').html(data.cart_quantity);

                            // jQuery('.hdr-mybasket').html(data.sidebar);
                            //update Addons for line item
                            jQuery('#rowprice_' + data.row_id + " .cart-price").html('<span class="price origprice" data-orig-price="' + data.row_total + '">&pound;' + data.row_total + '</span>');
                            //  jQuery('#rowprice_'+data.row_id+" .cart-price-mobile").html('<span class="price">'+data.row_total+'</span>');

                            getAddons(itemid);
                        }
                    });
                } catch (e) {
                }


            }, 500);

        }
    });


    jQuery('.decrement_qty').click(function () {
        clearTimeout(clickTimeout);
        var itemid = jQuery(this).attr("data-itemid");
        var parentid = jQuery(this).attr("data-parentid");
        var type = jQuery(this).attr("data-type");
        var prodid = jQuery(this).attr("data-prodid");
        jQuery('.btn-update').css('display', 'block');
        var oldVal = jQuery(this).parent().find("input").val();
        if (parseFloat(oldVal) >= 2) {
            var newVal = parseFloat(oldVal) - 1;
            jQuery(this).parent().find("input").val(newVal);

            clickTimeout = setTimeout(function () {

                var tS = new Date().getTime();

                var url = '/ev_cartassignments/addon/updateq' + '?ts=' + tS + '&type=' + type + '&qty=' + newVal + '&parentid=' + parentid + '&itemid=' + itemid + '&ctype=decrease';
                var data = ''

                try {
                    var searchbuttonwidth = jQuery('#rowpriceprod_' + itemid + '_' + prodid).outerWidth();

                    jQuery('#rowpriceprod_' + itemid + '_' + prodid).css({"min-width": searchbuttonwidth});
                    jQuery('#rowpriceprod_' + itemid + '_' + prodid).html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});

                    var searchbuttonwidth = jQuery('#rowprice_' + itemid).outerWidth();
                    //console.log(searchbuttonwidth);

                    jQuery('#rowprice_' + itemid).css({"min-width": searchbuttonwidth});
                    jQuery('#rowprice_' + itemid + ' .cart-price').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});

                    var searchbuttonwidth = jQuery("#ev_cart_totals").outerWidth();
                    //console.log(searchbuttonwidth);
                    jQuery("#ev_cart_totals").css({"min-width": searchbuttonwidth});
                    jQuery('#ev_cart_totals').html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>').css({"min-width": searchbuttonwidth});

                    jQuery.ajax({
                        url: url,
                        dataType: 'json',
                        type: 'get',
                        success: function (data) {


                            jQuery('#tritemtotal').html(data.grand_total_inc_vat);
                            jQuery('#tritemquantity').html(data.cart_quantity);
                            jQuery('.hdr-mybasket').html(data.sidebar);
                            jQuery('#ev_cart_totals').html(data.cart_totals);

                            //update Addons for line item
                            jQuery('#rowprice_' + data.row_id + ' .cart-price').html('<span class="price origprice" data-orig-price="' + data.row_total + '">&pound;' + data.row_total + '</span>');
                            getAddons(itemid);
                        }

                    });
                } catch (e) {
                }


            }, 500);


        }
    });
});


function updateTotalsBlock() {

    /* EV-RJ  11-1-2021 - Added this to resolve JS issue on GC after I added block disable option in admin*/

    if (typeof ELEVATE.Donation === 'object') {
        ELEVATE.Donation.getDonation();
    }




    /*Use m2 require js update*/

    require(
        [
            'Magento_Checkout/js/model/quote',
            'Magento_Checkout/js/model/cart/totals-processor/default'
        ],
        function (
            quote,
            totalsDefaultProvider
        ) {
            totalsDefaultProvider.estimateTotals(quote.shippingAddress());
        }
    );

}


function checkPostcodeArea(element, prodid, quote_id, addon_id) {


    var current_postcode = jQuery('#current_postcode').val();

    var html = '<div  id="' + quote_id + '_alertmessage" class="alertmessage"></div>'
    html += '<div class="col-md-12 postmessage">Please enter your postcode below to see if our service is available in your area.</div>';


    html += '<div class="row"><div class="col-md-6" style="padding-left:0px;"><input id="' + quote_id + '_postcode" class="form-control postcode-inputbox" value="' + current_postcode + '" placeholder="Enter Postcode" type="text" /></div>';
    html += '<div class="col-md-6" style="padding-left:0px;">';
    html += '<div id="btn_pc_' + quote_id + addon_id + '" class="btn action primary checkout btn-postcode-click" style="margin-right:5px;height: 20px;line-height: 3px;" onclick="addItemToCart(\'' + prodid + '\', \'' + quote_id + '\', \'add\', \'\', \'\', \'' + addon_id + '\' , this.id, \'postcode\', \'\');"><span style="border-radius: 9px;">Check My Area</span></div>';
    html += '</div></div><div style="clear:both;"></div>';

    jQuery('#postcode_' + quote_id + "_" + addon_id).attr('onclick', 'getAddons(' + quote_id + ');');
    console.log('#postcode_' + quote_id + "_" + addon_id);

    jQuery(element).html(html);

}

/* tests to see if string is in correct UK style postcode: AL1 1AB, BM1 5YZ etc. */
function isValidPostcode(p) {
    var postcodeRegEx = /^(([gG][iI][rR] {0,}0[aA]{2})|((([a-pr-uwyzA-PR-UWYZ][a-hk-yA-HK-Y]?[0-9][0-9]?)|(([a-pr-uwyzA-PR-UWYZ][0-9][a-hjkstuwA-HJKSTUW])|([a-pr-uwyzA-PR-UWYZ][a-hk-yA-HK-Y][0-9][abehmnprv-yABEHMNPRV-Y]))) {0,}[0-9][abd-hjlnp-uw-zABD-HJLNP-UW-Z]{2}))$/i;
    return postcodeRegEx.test(p);
}
