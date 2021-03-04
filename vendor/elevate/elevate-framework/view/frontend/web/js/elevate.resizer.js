

var ELEVATE = ELEVATE || {};


(function ($, window) {

  ELEVATE.Resizer = (function (options) {

    var self = this;
    // Need to make this pull from admin maybe?
    var desktopbreakpoint = 992;
    var smalldesktopbreakpoint = 768;
    var isdesktop = '';
    var issmalldesktop = '';

      var default_options = {
          enableHistory           : true
      };
      //call like (self.options.enableHistory
      this.options = $.extend(true, {}, default_options, typeof options === 'object' && options);



      function init()
      {
          ev_checkWindowWidth();

        jQuery(window).on('resize', function () {
            (!window.requestAnimationFrame) ? setTimeout(ev_checkWindowWidth, 10) : window.requestAnimationFrame(ev_checkWindowWidth);
        });
    }


      function ev_checkWindowWidth() {
          //check window width (scrollbar included)
          var e = window,
              a = 'inner';
          if (!('innerWidth' in window)) {
              a = 'client';
              e = document.documentElement || document.body;
          }

          var width = e[a + 'Width'];

          if (width >= desktopbreakpoint) {
              isdesktop = true;
          } else {
              isdesktop = false;
          }

          if (width >= smalldesktopbreakpoint) {
              issmalldesktop = true;
          } else {
              issmalldesktop = false;
          }
      }


    return {
      init : function() {
        init();
      },
        ev_checkWindowWidth : function() {
            ev_checkWindowWidth();
        },

        getIsDesktop: function() {
            return isdesktop;
        },
        getIsSmallDesktop: function() {
            return issmalldesktop;
        }
    };

  }());

}(jQuery.noConflict(), window))


