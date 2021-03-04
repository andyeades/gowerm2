

define([
    'uiComponent',
    'ko',
    'jquery',
    'mage/translate',
    'Magento_Ui/js/modal/modal',
    'Magento_Checkout/js/model/quote',
    'mageclass/map-loader',
    'mageclass/map'
], function (Component, ko, $, $t, modal, quote, MapLoader, map) {
    'use strict';

    var popUp = null;

    return Component.extend({
        defaults: {
            template: 'MageClass_ClickAndCollect/checkout/shipping/select-store'
        },
        isClickAndCollect: ko.observable(false),
        isSelectStoreVisible: ko.observable(false),
        isMapVisible: ko.observable(false),

toshiPopup: function(){

   //var e = null;
      // Start of our new ajax code
      var url = '/toshi/cart/cartdata';
               
      // url = url.replace("https://","http://"); // New Code
      var data = '';
      // data += '&size=1288148';
  
  
     // jQuery('#ajax_loader').show();
    // jQuery( document ).ready(function() {


      jQuery.ajax({
        url: url,
        dataType: 'json',
        type: 'post',
        data: data,
        success: function (data) {    
                      console.log("SUCCESS");
        //     jQuery('.hdr-mybasket').html(data.sidebar);
     
                    
     
    
        window.toshiData = data;

        //fe7032eeddac4ebfb1b0e1085b1696f2
       //https://staging.toshi.co          

window.modal = window.toshi.createToshiModal({
  api: {
    url: 'https://www.toshi.co',
    key: '0d0994bfe75b469faebe399edb017499'
  },
  services: {
    waitAndTry: {
      selectedDefault: true,
      available: false
    },
    inspireMe: true,
    sizing: false,
    alterations: false,
  },
  analytics: {
    trackingCode: 'GA-ABC123-2'
  },
  interfaceProps: {
    showEmailField: false,
    showContactNumberField: false
  }
});


toshiData.firstName = jQuery( "input[name='firstname']" ).val();
toshiData.lastName = jQuery( "input[name='lastname']" ).val();

                                  
                                   
//toshiData.postcode = 'SW1A 2LW';
 
toshiData.postcode = jQuery( "input[name='postcode']" ).val()
toshiData.address_line_1 = jQuery( "input[name='street\\[0\\]']" ).val();
toshiData.address_line_2 = jQuery( "input[name='street\\[1\\]']" ).val();
toshiData.customerEmail = jQuery( "#customer-email" ).val();
toshiData.contactNumber = jQuery( "input[name='telephone']" ).val();
 
toshiData.town = '';



window.modal.setFirstName(toshiData.firstName);
window.modal.setLastName(toshiData.lastName);
window.modal.setEmail(toshiData.customerEmail);
window.modal.setPhone(toshiData.contactNumber);
window.modal.setAddress({
  line1: toshiData.address_line_1,
  line2: toshiData.address_line_2,
  town: toshiData.town,
  postcode: toshiData.postcode
});
window.modal.setOrderTotal({
value: toshiData.orderTotalPrice,
currency: 'GBP'
 }
);

window.modal.setStoreOrderReference(toshiData.quoteNumber);
window.modal.setProducts(toshiData.products);
window.modal.setStore(14);
  
// Subscribe and react to changes in the modal state. 


window.modal.onShadowOrderCreated(function(e){
     

//console.log(e)

});
 // A $( document ).ready() block.

 

      window.modal.mount(document.getElementById('toshi-modal'));
                                   

          jQuery('#toshi-modal').show();
          }
      });
},

 waitForEl : function(selector, callback) {
 var self = this;
  if (jQuery(selector).length) {
    callback();
  } else {
    setTimeout(function() {        
     console.log("WAIT");
      self.waitForEl(selector, callback);
    }, 100);
  }
},
 waitForEls : function(selector1, selector2, selector3, callback) {
 var self = this;              
  if (jQuery(selector1).length && jQuery(selector2).length && jQuery(selector3).length) {
    callback();
  } else {
    setTimeout(function() {        
     
      self.waitForEls(selector1, selector2, selector3, callback);
    }, 100);
  }
},
 waitForElsInput : function(selector1, selector2, selector3, callback) {
 var self = this;              
  if ((jQuery(selector1).length && jQuery(selector1).val().length) && (jQuery(selector2).length && jQuery(selector2).val().length) && (jQuery(selector3).length && jQuery(selector3).val().length)) {
    callback();
  } else {
    setTimeout(function() {        
     
      self.waitForElsInput(selector1, selector2, selector3, callback);
    }, 100);
  }
},


        initialize: function () {
        	var self = this;
        	quote.shippingMethod.subscribe(function () {
         
          if(quote.shippingMethod()){
        
        console.log(quote.shippingMethod().carrier_code);
            	if (quote.shippingMethod().carrier_code == 'clickandcollect') {
                          console.log("IN");
                          
var selector = '#shipping-method-buttons-container';
   
self.waitForEl(selector, function() {

jQuery( "#shipping-method-buttons-container" ).prepend( '<div id="toshi-modal"></div>' );
});
 
      
      

   var selector = '#toshi-modal';
   
self.waitForEl(selector, function() {
  // work the magic
  
  var selector1 = '#customer-email';
  var selector2 = "input[name='postcode']";
  var selector3 = "input[name='telephone']";
self.waitForEls(selector1,selector2, selector3, function() {


  var selector4 = '#customer-email';
  var selector5 = "input[name='telephone']";
  var selector6 = "input[name='postcode']";
 self.waitForElsInput(selector4,selector5, selector6, function() {
 
     self.toshiPopup();
 
  }); 



});  

});
              

//Code you wrote that may or may not attach it to the DOM






             /*   
                $.fancybox.open({
                	src  : '#toshi-modal',
                	type : 'inline',
                	opts : {
                		afterShow : function( instance, current ) {
                			console.info( 'done!' );
                		}
                	}
                }); 
              */                  
            		self.isClickAndCollect(true);
                    var stores = $.parseJSON(window.checkoutConfig.shipping.select_store.stores);
                    if(stores.totalRecords > 1) {
                        self.isSelectStoreVisible(true);
                    }
            	} else {
                    jQuery('#toshi-modal').hide();
            		self.isClickAndCollect(false);
            	}
              }
              else{
              
                jQuery('#toshi-modal').hide();
              }
          
            });

            this.isMapVisible.subscribe(function (value) {
                if (value) {
                    self.getPopUp().openModal();
                } else {
                    self.getPopUp().closeModal();
                }
            });

            ko.bindingHandlers.datetimepicker = {
                init: function (element, valueAccessor, allBindingsAccessor) {
                    var $el = $(element);
                    $el.datetimepicker({
                        'showTimepicker': false,
                        'format': 'yyyy-MM-dd'
                    });
                    var writable = valueAccessor();
                    if (!ko.isObservable(writable)) {
                        var propWriters = allBindingsAccessor()._ko_property_writers;
                        if (propWriters && propWriters.datetimepicker) {
                            writable = propWriters.datetimepicker;
                        } else {
                            return;
                        }
                    }
                    writable($(element).datetimepicker("getDate"));
                },
                update: function (element, valueAccessor) {
                    var widget = $(element).data("DateTimePicker");
                    if (widget) {
                        var date = ko.utils.unwrapObservable(valueAccessor());
                        widget.date(date);
                    }
                }
            };

            $('body').on('click', '.apply-store', function() {
                $('#pickup-store').val($(this).data('id'));
                $('#selected-store-msg')
                    .show()
                    .find('span')
                    .text( $(this).data('name') );
                self.isMapVisible(false);
            });

            return this._super();
        },

        showMap: function () {
            this.isMapVisible(true);
        },

        getPopUp: function () {
            var self = this,
                buttons;

            if (!popUp) {
                MapLoader.done($.proxy(map.initMap, this)).fail(function() {
                    console.error("ERROR: Google maps library failed to load");
                });
                popUp = modal({
                	'responsive': true,
                	'innerScroll': true,
                    'buttons': [],
                    'type': 'slide',
                    'modalClass': 'mc_cac_map',
                	closed: function() {
            			self.isMapVisible(false)
            		}
                }, $('#map-canvas'));
            }
            return popUp;
        }
    });
});