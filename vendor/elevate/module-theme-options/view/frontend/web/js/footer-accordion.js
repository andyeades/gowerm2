jQuery(document).ready(function() {
    // EV Check window Width Needed

    var MqL = 768;

    function ev_checkWindowWidth() {
        //check window width (scrollbar included)
        var e = window,
            a = 'inner';
        if (!('innerWidth' in window)) {
            a = 'client';
            e = document.documentElement || document.body;
        }

        var width = e[a + 'Width'];

        if (width >= MqL) {
            return true;
        } else {

            return false;
        }
    }
    function resizeWindow() {
        if (ev_checkWindowWidth()) {
            console.log('resize window');
            jQuery('.footer-block ul').show();
            jQuery('.ftr-b-collapsible').show();
            jQuery('.footer-block h4').removeClass('ftr-nav-opened');
        }
    }
    function checkState(jqueryobject,classtocheck,classtoremove,classtoadd) {

        if (jQuery(jqueryobject).hasClass(classtocheck)) {
            jQuery(jqueryobject).removeClass(classtoremove);
        } else {
            jQuery(jqueryobject).addClass(classtoadd);
        }
    }

    jQuery('#ev-footer-main .footer-block h4').click(function() {
        if (!ev_checkWindowWidth()) {
            if (jQuery(this).hasClass('opens-next-as-well')) {
            var opensnext = jQuery('.opens-next-as-well').parent().parent().next().children().find('ul');
            }



            checkState(this,'ftr-nav-opened','ftr-nav-opened','ftr-nav-opened');
           if (typeof opensnext != "undefined") {
               checkState(opensnext,'ftr-nav-opened','ftr-nav-opened','ftr-nav-opened');
               jQuery(this).next('ul').slideToggle();
               jQuery(opensnext).slideToggle();
           } else {
               jQuery(this).next('ul').slideToggle();
           }

            //jQuery(this).next('.ftr-b-collapsible').slideToggle();
        }


    });

    jQuery(window).on('resize', function(){
        (!window.requestAnimationFrame) ? setTimeout(resizeWindow, 10) : window.requestAnimationFrame(resizeWindow);
    });
});
