(function($) {
  'use strict';

  // default configuration values
  var _cfg = {
    interval: 100,
    sensitivity: 6,
    timeout: 0
  };

  // counter used to generate an ID for each instance
  var INSTANCE_COUNT = 0;

  // current X and Y position of mouse, updated during mousemove tracking (shared across instances)
  var cX, cY;

  // saves the current pointer position coordinated based on the given mouse event
  var track = function(ev) {
    cX = ev.pageX;
    cY = ev.pageY;
  };

  // compares current and previous mouse positions
  var compare = function(ev,$el,s,cfg) {
    // compare mouse positions to see if pointer has slowed enough to trigger `over` function
    if ( Math.sqrt( (s.pX-cX)*(s.pX-cX) + (s.pY-cY)*(s.pY-cY) ) < cfg.sensitivity ) {
      $el.off('mousemove.hoverIntent'+s.namespace,track);
      delete s.timeoutId;
      // set hoverIntent state as active for this element (so `out` handler can eventually be called)
      s.isActive = true;
      // clear coordinate data
      delete s.pX; delete s.pY;
      return cfg.over.apply($el[0],[ev]);
    } else {
      // set previous coordinates for next comparison
      s.pX = cX; s.pY = cY;
      // use self-calling timeout, guarantees intervals are spaced out properly (avoids JavaScript timer bugs)
      s.timeoutId = setTimeout( function(){compare(ev, $el, s, cfg);} , cfg.interval );
    }
  };

  // triggers given `out` function at configured `timeout` after a mouseleave and clears state
  var delay = function(ev,$el,s,out) {
    delete $el.data('hoverIntent')[s.id];
    return out.apply($el[0],[ev]);
  };

  $.fn.hoverIntent = function(handlerIn,handlerOut,selector) {
    // instance ID, used as a key to store and retrieve state information on an element
    var instanceId = INSTANCE_COUNT++;

    // extend the default configuration and parse parameters
    var cfg = $.extend({}, _cfg);
    if ( $.isPlainObject(handlerIn) ) {
      cfg = $.extend(cfg, handlerIn );
    } else if ($.isFunction(handlerOut)) {
      cfg = $.extend(cfg, { over: handlerIn, out: handlerOut, selector: selector } );
    } else {
      cfg = $.extend(cfg, { over: handlerIn, out: handlerIn, selector: handlerOut } );
    }

    // A private function for handling mouse 'hovering'
    var handleHover = function(e) {
      // cloned event to pass to handlers (copy required for event object to be passed in IE)
      var ev = $.extend({},e);

      // the current target of the mouse event, wrapped in a jQuery object
      var $el = $(this);

      // read hoverIntent data from element (or initialize if not present)
      var hoverIntentData = $el.data('hoverIntent');
      if (!hoverIntentData) { $el.data('hoverIntent', (hoverIntentData = {})); }

      // read per-instance state from element (or initialize if not present)
      var state = hoverIntentData[instanceId];
      if (!state) { hoverIntentData[instanceId] = state = { id: instanceId }; }

      // state properties:
      // id = instance ID, used to clean up data
      // timeoutId = timeout ID, reused for tracking mouse position and delaying "out" handler
      // isActive = plugin state, true after `over` is called just until `out` is called
      // pX, pY = previously-measured pointer coordinates, updated at each polling interval
      // namespace = string used as namespace for per-instance event management

      // clear any existing timeout
      if (state.timeoutId) { state.timeoutId = clearTimeout(state.timeoutId); }

      // event namespace, used to register and unregister mousemove tracking
      var namespace = state.namespace = '.hoverIntent'+instanceId;

      // handle the event, based on its type
      if (e.type === 'mouseenter') {
        // do nothing if already active
        if (state.isActive) { return; }
        // set "previous" X and Y position based on initial entry point
        state.pX = ev.pageX; state.pY = ev.pageY;
        // update "current" X and Y position based on mousemove
        $el.on('mousemove.hoverIntent'+namespace,track);
        // start polling interval (self-calling timeout) to compare mouse coordinates over time
        state.timeoutId = setTimeout( function(){compare(ev,$el,state,cfg);} , cfg.interval );
      } else { // "mouseleave"
        // do nothing if not already active
        if (!state.isActive) { return; }
        // unbind expensive mousemove event
        $el.off('mousemove.hoverIntent'+namespace,track);
        // if hoverIntent state is true, then call the mouseOut function after the specified delay
        state.timeoutId = setTimeout( function(){delay(ev,$el,state,cfg.out);} , cfg.timeout );
      }
    };

    // listen for mouseenter and mouseleave
    return this.on({'mouseenter.hoverIntent':handleHover,'mouseleave.hoverIntent':handleHover}, cfg.selector);
  };


})(jQuery); // Fully reference jQuery after this point.


/*
By Osvaldas Valutis, www.osvaldas.info
Available for use under the MIT License
*/



;(function( jQuery, window, document, undefined )
{
  jQuery.fn.doubleTapToGo = function( params )
  {
    if( !( 'ontouchstart' in window ) &&
      !navigator.msMaxTouchPoints &&
      !navigator.userAgent.toLowerCase().match( /windows phone os 7/i ) ) return false;

    this.each( function()
    {
      var curItem = false;

      jQuery( this ).on( 'click', function( e )
      {
        var item = jQuery( this );
        if( item[ 0 ] != curItem[ 0 ] )
        {
          e.preventDefault();
          curItem = item;
        }
      });

      jQuery( document ).on( 'click touchstart MSPointerDown', function( e )
      {
        var resetItem = true,
          parents   = jQuery( e.target ).parents();

        for( var i = 0; i < parents.length; i++ )
          if( parents[ i ] == curItem[ 0 ] )
            resetItem = false;

        if( resetItem )
          curItem = false;
      });
    });
    return this;
  };
})( jQuery, window, document );


var ELEVATE = ELEVATE || {};


(function ($, window) {

  ELEVATE.Megamenu = (function () {

    var self = this;
    var dragging = false;
    var menu_type = false;
    var MqL = 768;
    //var MqL = 64; //em

    function init()
    {
      console.log('In Init Function');
      jQuery( '<div class="cd-overlay"></div>' ).insertAfter( "#nav-bar-outer" );
      attachEvents();
      ev_moveNavigation();
      ev_categoriesDynamic();
      jQuery(window).on('resize', function(){
        (!window.requestAnimationFrame) ? setTimeout(ev_moveNavigation, 10) : window.requestAnimationFrame(ev_moveNavigation);
      });
    }

    function collectElements()
    {
      link       = $(link_selector);
      trigger    = $(trigger_selector);
      summary    = $(summary_selector);
      link_image = $('img', link);
    }

    function attachEvents()
    {

      jQuery( document ).ready(function() {
        if(jQuery(this).width() <= 1024 ){
          jQuery( '.ms-topmenu .col-level .parent' ).doubleTapToGo({
            automatic: true
          });

        }

      });

      $('.col-level-top-link').children('a').on('click touchend', function (e) {

        // If Mobile Don't Follow Tag Links on this one, goto Sub Categories
        if( !ev_checkWindowWidth() ) {
          e.preventDefault();
          var content_div_name = $(this).prev().attr('title');
          //console.log(content_div_name);
          // if ($(this).has('dynamic-content')
          var content_div = $('#'+content_div_name);
          var check_for_next = $(this).next('.col-dynamic');
          console.log(check_for_next);
          if (check_for_next.length > 0) {
            console.log('next is col dynamic?');
            content_div.detach();
            var parent_container =  $(this).parents('.col-category-inner');
            var dynamic_content_container = $(parent_container).find('.dynamic-content');
            $(dynamic_content_container).append(content_div);
          } else {
            content_div.detach();
            $(this).parent().append(content_div);
          }


        }
      });

      //detect drag
      $("body").on("touchmove", function () {
        dragging = true;
      });

      $("body").on("touchstart", function () {
        dragging = false;
      });

      //mobile - open lateral menu clicking on the menu icon
      $('.nav-toggle').on('click touchend', function(event){
        if (dragging)
          return;
        event.preventDefault();
        if( $('.cd-main-content').hasClass('nav-is-visible') ) {
          ev_closeNav(true);
          $('.cd-overlay').removeClass('is-visible');
        } else {

          $('#mobile-search').addClass('is-hidden');
          $('#mobile-search').removeClass('mobile-search-visible');

          //open the menu
          $(this).addClass('nav-is-visible');
          $('.cd-primary-nav').removeClass('is-hidden');
          $('.cd-primary-nav').addClass('nav-is-visible');
          $('.main-container').addClass('nav-is-visible');
          //	$('#header_wrap').addClass('nav-is-visible');
          //$('body').addClass('overflow-hidden');

          //	$('.cd-main-content').addClass('nav-is-visible').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
          //		$('body').addClass('overflow-hidden');
          //	});

          $('.cd-overlay').addClass('is-visible');
        }
      });


      //close lateral menu on mobile
      $('.cd-overlay').on('swiperight', function(){
        if($('.cd-primary-nav').hasClass('nav-is-visible')) {
          ev_closeNav(true);
          $('.cd-overlay').removeClass('is-visible');
        }
      });

      $('.nav-on-left .cd-overlay').on('swipeleft', function(){
        if($('.cd-primary-nav').hasClass('nav-is-visible')) {
          ev_closeNav(true);
          $('.cd-overlay').removeClass('is-visible');
        }
      });

      $('.cd-overlay').on('click', function(){
        ev_closeNav(true);

        $('.cd-overlay').removeClass('is-visible');
      });


      $('.cd-nav-trigger-close').on('click touchend', function(e){
        ev_closeNav(true);
        $('.cd-overlay').removeClass('is-visible');
      });


      $('.cd-primary-nav .has-children').children('a').on('click touchend', function (e) {
        if (dragging)
          return;
        //mobile if not chek
        if( !ev_checkWindowWidth() ) e.preventDefault();
        var selected = $(this);
        //desktop version only
        if( selected.next('ul').hasClass('is-hidden') ) {
          //desktop version only
          selected.addClass('selected').next('ul').removeClass('is-hidden').end().parent('.has-children').parent('ul').addClass('moves-out');
          selected.parent('.has-children').siblings('.has-children').children('ul').addClass('is-hidden').end().children('a').removeClass('selected');
          $('.cd-overlay').addClass('is-visible');
        } else {
          selected.removeClass('selected').next('ul').addClass('is-hidden').end().parent('.has-children').parent('ul').removeClass('moves-out');
          $('.cd-overlay').removeClass('is-visible');
        }

      });

      $('.ev_nav_level_1.has-children').hoverIntent(function(e){


        if (!ev_checkWindowWidth()) {
          e.preventDefault();
        }
        else{

          var selected = $(this);
          //desktop version only
          selected.addClass('selected');



          selected.find('.ev_nav_level_2').removeClass('is-hidden').end().parent('.has-children').parent('ul').addClass('moves-out');
          selected.find('.dropdown-icon').removeClass('is-hidden');
          //	selected.parent('.has-children').siblings('.has-children').children('ul').addClass('is-hidden').end().children('a').removeClass('selected');
          //$('.cd-overlay').addClass('is-visible');
        }
      }, function(e){
        if (!ev_checkWindowWidth()) {
          e.preventDefault();
        }
        else{

          var selected = $(this);
          selected.find('.dropdown-icon').addClass('is-hidden');
          selected.removeClass('selected').find('.ev_nav_level_2').addClass('is-hidden').end().parent('.has-children').parent('ul').removeClass('moves-out');

          //$('.cd-overlay').removeClass('is-visible');
        }
      });


      // RJ - 06/10/2015 - Added to make menu work on links to follow iOS/Android.
      $('.clickable').on('click touchend', function(e){
        if (dragging)
          return;
        window.location = $(this).attr('href');
      });


      //submenu items - go back link
      $('.go-back').on('click touchend', function(e){
        if (dragging)
          return;
        e.stopImmediatePropagation();
        ev_moveDynamicContentBack();
        $(this).parent('ul').addClass('is-hidden').parent('.has-children').parent('ul').removeClass('moves-out');
        return false;
      });

      /* Search & Account Header Side Buttons */
      $('#hdr-search').on('click touchend', function(event){
          ev_opennav(event);
          $('#header-top-search').focus().removeClass('field-highlight');
          setTimeout(function(){
            $('#header-top-search').addClass('field-highlight');
          }, 0);
        }
      );
      $('#hdr-account').on('click touchend', function(event){
        ev_opennav(event);
        $('#header-top-search').focus().removeClass('field-highlight');
        setTimeout(function(){
          $('#header-top-search').addClass('field-highlight');
        }, 0);
      });
      $('#header-mobile-search').on('click touchend', function (e) {


        if ($('#mobile-search').hasClass('is-hidden')) {
          $('#mobile-search').removeClass('is-hidden');
        } else {
          $('#mobile-search').addClass('is-hidden');
        }
        if ($('#mobile-search').hasClass('mobile-search-visible')) {
          $('#mobile-search').removeClass('mobile-search-visible');
        } else {
          $('#mobile-search').addClass('mobile-search-visible');
        }
        e.preventDefault();

        $('#search').focus().removeClass('field-highlight');
        setTimeout(function () {
          $('.search').addClass('field-highlight');
        }, 0);
      });

    }

    function ev_closeNav(move) {
      ev_moveDynamicContentBack();
      $('.cd-main-header').removeClass('is-hidden');
      $('.cd-primary-nav').addClass('is-hidden');
      $('.cd-nav-trigger').removeClass('nav-is-visible');
      $('.cd-main-header').removeClass('nav-is-visible');
      $('.cd-primary-nav').removeClass('nav-is-visible');
      $('.cd-primary-nav').css('-webkit-overflow-scrolling', '');
      $('.page').removeClass('nav-is-visible');
      $('.has-children ul').addClass('is-hidden');
      $('.has-children a').removeClass('selected');
      $('.moves-out').removeClass('moves-out');

      //$('body').removeClass('overflow-hidden');
      $('.cd-main-content').removeClass('nav-is-visible').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
        //$('body').removeClass('overflow-hidden');
      });
      $('.main-container').removeClass('nav-is-visible');
      if(move){
        ev_moveNavigation();
      }
      $('.cd-overlay').removeClass('is-visible');
    }

    function ev_checkWindowWidth() {
      //check window width (scrollbar included)
      var e = window,
        a = 'inner';
      if (!('innerWidth' in window )) {
        a = 'client';
        e = document.documentElement || document.body;
      }

      var width =  e[ a+'Width' ];
      //var newwidth = width / parseFloat($("body").css("font-size"));
      if ( width >= MqL ) {
        return true;
      } else {

        return false;
      }
    }

    function ev_moveNavigation(){
      console.log("in move navigation");
      var navigation = $('.cd-nav');
      var logo = $('#hdr-logo');
      var searchbox = $('#hdr-search-container');
      var desktop = ev_checkWindowWidth();
      var productpage = 0;
      var ie9 = 0;
      //console.log(menu_type);
      if ($('body').hasClass('catalog-product-view')) {
        productpage = 1;
      }
      if ($('body').hasClass('ie9')) {
        ie9 = 1;
      }
      if (desktop && (menu_type != 1 || !menu_type)) {

        menu_type = 1;
        navigation.detach();
        //$('#header-main-container').removeClass('is-hidden');
        navigation.appendTo('.cd-main-header');

        //$('#hdr-search-container').detach().prependTo($('#mhdr-search-wrap'));
        $('#mobile-search').addClass('is-hidden');

        $('.cd-primary-nav').removeClass('is-hidden');
        $('#hdr-search-container').addClass('hide_on_mob');
      } else if (!desktop && (menu_type != 2 || !menu_type)) {
        console.log("WE HERE!");
        navigation.detach();

        menu_type = 2;
        ev_closeNav(false);
        //$('#header-main-container').addClass('is-hidden');
        navigation.insertAfter('.cd-main-header');
        $('#hdr-search-container').detach().appendTo($('#mobile-search'));
        if (productpage == 1) {
          if (ie9 != 1) {
            jQuery('#main-product-name').detach().insertBefore('.product-img-box');
          }
          jQuery(".btn-postcode-search").css({"min-width": "none"});
        }
        $('#hdr-search-container').removeClass('hide_on_mob');
      }
    }
    function ev_search(){
      var searchbutton = $('#hdr-search');

      searchbutton.click(function() {

      });
    }
    /* Opens Nav */
    function ev_opennav(event) {
      if (jQuery('#mobile-search').hasClass('mobile-search-visible')) {
        //  jQuery('#header-mobile-search').click();
      }
      $('#mobile-search').addClass('is-hidden');
      $('#mobile-search').removeClass('mobile-search-visible');

      event.preventDefault();
      if( $('.cd-main-content').hasClass('nav-is-visible') ) {
        ev_closeNav(true);
        $('.cd-overlay').removeClass('is-visible');
      } else {
        //open the menu

        $(this).addClass('nav-is-visible');
        $('.cd-primary-nav').addClass('nav-is-visible');
        $('.cd-primary-nav').css('-webkit-overflow-scrolling', 'touch');
        $('.main-container').addClass('nav-is-visible');
        //	$('#header_wrap').addClass('nav-is-visible');

        //$('body').addClass('overflow-hidden');
        //   $('.cd-main-content').addClass('nav-is-visible').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function () {
        //   $('body').addClass('overflow-hidden');
        ///  });

        $('.cd-overlay').addClass('is-visible');
      }
    }

    function ev_categoriesDynamic() {

    }




    function ev_makeTall() {

    }

    function ev_moveDynamicContentBack() {
      console.log('in Move Dynamic COntent Back Function')
      var sub_menu = $('.col-level-top-link .col-dynamic');
      var parent_container = $(sub_menu).parents('.col-category-inner');
      var dynamic_content_container = $(parent_container).find('.dynamic-content');
      sub_menu.detach().appendTo(dynamic_content_container);
    }

    return {
      init : function() {
        init();
      },
      ev_makeTall: function() {
        ev_makeTall();
      },
      ev_checkWindowWidth: function() {
        ev_checkWindowWidth();
      },
      ev_categoriesDynamic: function() {
        ev_categoriesDynamic();
      }
    };

  }());

}(jQuery.noConflict(), window));