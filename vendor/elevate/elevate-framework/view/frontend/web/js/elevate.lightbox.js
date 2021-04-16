var ELEVATE = ELEVATE || {};
(function ($) {
    ELEVATE.Lightbox = (function (options) {
        var lightboxInitialised = false;


        var default_options = {
            enableHistory: true
        };

        //call like (self.options.enableHistory)
        this.options = $.extend(true, {}, default_options, typeof options === 'object' && options);

        var init = function () {
                initialiseLightbox();
                attachEventHandlers();
            },
            initialiseLightbox = function () {
                var lightbox = '<div class="bs-modal fade" id="elevateLightboxModal" tabindex="-1" role="dialog" aria-labelledby="elevateLightboxModalLabel" aria-hidden="true">';
                lightbox += '<div id="elevateLightboxModalDialog" class="bs-modal-dialog bs-modal-lg">';
                lightbox += ' <div class="bs-modal-content">';
                lightbox += '   <div class="bs-modal-header">';
                lightbox += '    <button type="button" class="close" data-dismiss="bs-modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                lightbox += '<h4 class="bs-modal-title" id="elevateLightboxModalLabel"></h4>';
                lightbox += '</div>';
                lightbox += '<div class="bs-modal-body"></div><div class="bs-modal-footer"></div></div></div></div>';
                var $div = jQuery(lightbox).appendTo('body');
            },
            openLightbox = function (body_type,
                                     body,
                                     title_type,
                                     title,
                                     footer_type,
                                     footer,
                                     size) {


                if (undefined !== footer && footer.length > 0) {
                    jQuery("#elevateLightboxModal .bs-modal-footer").show();
                    if (footer_type == 'div') {
                        jQuery("#elevateLightboxModal .bs-modal-footer").html(jQuery(footer).html());
                    } else {
                        jQuery("#elevateLightboxModal .bs-modal-footer").html(footer);
                    }
                } else {
                    jQuery("#elevateLightboxModal .bs-modal-footer").hide();
                }
                if (undefined !== title && title.length > 0) {
                    jQuery("#elevateLightboxModal .bs-modal-title").show();
                    if (title_type == 'div') {
                        jQuery("#elevateLightboxModal .bs-modal-title").html(jQuery(title).html());
                    } else {
                        jQuery("#elevateLightboxModal .bs-modal-title").html(title);
                    }
                } else {
                    jQuery("#elevateLightboxModal .bs-modal-title").hide();
                }
                if (size == '') {
                    jQuery("#elevateLightboxModalDialog").removeClass().addClass('bs-modal-dialog').addClass('bs-modal-lg');
                } else {
                    jQuery("#elevateLightboxModalDialog").removeClass().addClass('bs-modal-dialog').addClass(size);
                }


                if (body_type == 'div') {
                    jQuery("#elevateLightboxModal .bs-modal-body").html(jQuery(body).html());
                } else if (body_type == 'inline') {
                    jQuery("#elevateLightboxModal .bs-modal-body").html(body);
                } else if (body_type == 'ajax') {
                    loadInfo(body, true);
                } else if (body_type == 'ajaxurl') {
                    loadInfoHtml(body, true);
                } else if (body_type == 'iframe') {
                    loadInfoIframe(body);
                } else {
                    loadInfo(jQuery(this).data('body'), jQuery(this).data('json'));
                }
                jQuery("#elevateLightboxModal").bsmodal('show');


            },
            attachEventHandlers = function () {

                jQuery('.evlightbox').off('click').on('click', function (e) {
                    var searchbuttonwidth = jQuery("#elevateLightboxModal .bs-modal-body").outerWidth();
                    jQuery("#elevateLightboxModal .bs-modal-body").html('<img src="/skin/frontend/base/default/cartassignments/spinner.svg" style="\n' +
                        '    margin: 29px auto;\n' +
                        '    display: block;\n' +
                        '">').css({
                        "min-width": searchbuttonwidth
                    });

                    var body_type = jQuery(this).data('body-type');
                    var body = jQuery(this).data('body');
                    var title_type = jQuery(this).data('title-type');
                    var title = jQuery(this).data('title');
                    var footer_type = jQuery(this).data('footer-type');
                    var footer = jQuery(this).data('footer');
                    var size = jQuery(this).data('size');


                    openLightbox(
                        body_type,
                        body,
                        title_type,
                        title,
                        footer_type,
                        footer,
                        size
                    );

                });
            },
            loadInfo = function (url, isJson) {
                if (isJson) {
                    url = url + '/json/1';
                }
                console.log(url);
                var data = '';


                var tS = new Date().getTime();
                url += '/ts/' + tS; //get around caching issues - always force unique - needs review - caching desireable


                jQuery.ajax({
                    url: url,
                    dataType: 'json',
                    type: 'get',
                    success: function (data) {
                        if (isJson) {
                            jQuery("#elevateLightboxModal .bs-modal-title").html(data.title);
                        }
                        jQuery("#elevateLightboxModal .bs-modal-title").html(data.title);
                        jQuery("#elevateLightboxModal .bs-modal-body").html(data.html);
                    }
                });
            },
            loadInfoIframe = function (url) {


                var html = '<div class="mfp-iframe-scaler"><iframe class="elevate-iframe" style="position: absolute;display: block;top: 0;left: 0;width: 100%;height: 100%;opacity: 0;background-color: #fff;" src="' + url + '" frameborder="0" allowfullscreen="" style="opacity: 1;"></iframe></div>';
                jQuery("#elevateLightboxModal .bs-modal-body").html(html);


                jQuery(document).ready(function () {
                    var lastHeight = 0,
                        curHeight = 0;
                    var parentBody = window.parent.document.body;
                    // $('.mfp-preloader', parentBody).css('display', 'none');
                    // $('.mfp-close', parentBody).css('display', 'block');
                    jQuery('.bs-modal-body', parentBody).css('width', '100%');
                    jQuery('.mfp-iframe-scaler iframe', parentBody).animate({
                        'opacity': 1
                    }, 1000);

                    // $('body').css('overflow', 'hidden');

                    function recalculateHeight(animateDelay) {
                        curHeight = jQuery('.page-wrapper').outerHeight(true);
                        documentHeight = curHeight + "px";
                        if (curHeight != lastHeight) {
                            jQuery('.bs-modal-body', parentBody).animate({
                                'height': documentHeight
                            }, animateDelay);
                            lastHeight = curHeight;
                        }
                    }

                    recalculateHeight(0);
                    setInterval(function () {
                        recalculateHeight(500);
                    }, 1000);
                });
            },


            loadInfoHtml = function (url, isJson) {
                if (isJson) {
                    url = url + '/json/1';
                }
                console.log(url);
                var data = '';
                jQuery.ajax({
                    url: url,
                    dataType: 'html',
                    type: 'get',
                    success: function (data) {
                        if (isJson) {
                            // jQuery("#elevateLightboxModal .bs-modal-title").html("Quick View");
                        }
                        jQuery("#elevateLightboxModal .bs-modal-body").html(data);
                    }
                });
            }
        return {
            init: function () {
                init();
            },
            attachEventHandlers: function () {
                attachEventHandlers();
            },
            openLightbox: function (body_type,
                                    body,
                                    title_type,
                                    title,
                                    footer_type,
                                    footer,
                                    size) {
                openLightbox(body_type,
                    body,
                    title_type,
                    title,
                    footer_type,
                    footer,
                    size);
            }
        };
    }());
}(jQuery.noConflict()));
jQuery(document).ready(function () {
    ELEVATE.Lightbox.init();
});