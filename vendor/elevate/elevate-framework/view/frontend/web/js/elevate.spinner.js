

var ELEVATE = ELEVATE || {};


(function ($, window) {

  ELEVATE.Spinner = (function (options) {

    var self = this;


      var default_options = {
          enableHistory           : true
      };
      //call like (self.options.enableHistory
      this.options = $.extend(true, {}, default_options, typeof options === 'object' && options);



      function init()
    {


    }

 function getSpinner(element){
          // Why Are you setting a minimum width on something here?
         // Causing problems on responsiveness at medium ish breakpoint on Product List
     var searchbuttonwidth = jQuery(element).outerWidth();
     jQuery(element).html('<div class="spinner-fading-circle"><div class="spinner-circle1 spinner-circle"></div><div class="spinner-circle2 spinner-circle"></div> <div class="spinner-circle3 spinner-circle"></div><div class="spinner-circle4 spinner-circle"></div><div class="spinner-circle5 spinner-circle"></div><div class="spinner-circle6 spinner-circle"></div><div class="spinner-circle7 spinner-circle"></div><div class="spinner-circle8 spinner-circle"></div><div class="spinner-circle9 spinner-circle"></div><div class="spinner-circle10 spinner-circle"></div><div class="spinner-circle11 spinner-circle"></div><div class="spinner-circle12 spinner-circle"></div></div>')
         .css({"min-width": searchbuttonwidth});
 }

      function getSpinnerNoMinWidth(element){
          jQuery(element).html('<div class="spinner-fading-circle"><div class="spinner-circle1 spinner-circle"></div><div class="spinner-circle2 spinner-circle"></div> <div class="spinner-circle3 spinner-circle"></div><div class="spinner-circle4 spinner-circle"></div><div class="spinner-circle5 spinner-circle"></div><div class="spinner-circle6 spinner-circle"></div><div class="spinner-circle7 spinner-circle"></div><div class="spinner-circle8 spinner-circle"></div><div class="spinner-circle9 spinner-circle"></div><div class="spinner-circle10 spinner-circle"></div><div class="spinner-circle11 spinner-circle"></div><div class="spinner-circle12 spinner-circle"></div></div>')
      }

    return {
      init : function() {
        init();
      },
        getSpinner : function(element) {
            getSpinner(element);
        },
        getSpinnerNoMinWidth : function(element) {
            getSpinnerNoMinWidth(element);
        }
    };

  }());

}(jQuery.noConflict(), window))


