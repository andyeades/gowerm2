var productPage = {
    init: function () {
        jQuery('.togglet').bind('click', function () {
            setTimeout(function () {
                jQuery(window).trigger('resize')
            }, 300);
        });
    },

    load: function () {
        this.action();
        this.mageSticky();
        this.addMinHeight();
    },

    ajaxComplete: function () {
        this.mageSticky();
        this.adjustHeight();
    },

    resize: function () {
        this.action();
        this.adjustHeight();
        this.mageSticky();
    },

    adjustHeight: function () {
        // adjust left media height as well, in case it is smallers
        var media = jQuery('.product.media'),
            mediaGallery = jQuery('.product.media .gallery'),
            infoMain = jQuery('.product-info-main');

        if (jQuery('body').hasClass('wp-device-xs') ||
            jQuery('body').hasClass('wp-device-s') ||
            jQuery('body').hasClass('wp-device-m')
        ) {
            media.height('auto');
        } else {
            if ((mediaGallery.height() > 0) && (mediaGallery.height() < infoMain.height())) {
                media.height(infoMain.height());
            }
        }
    },

    mageSticky: function () {
        var positionProductInfo = window.positionProductInfo;
        //pass adjustment info into PP - called from productview


        if (positionProductInfo == 1) {


        }


        jQuery('.product-info-main.product_v2.cart-summary').mage('sticky', {
            container: '.product-top-main.product_v2',
            spacingTop: 100
        });


    },

    action: function () {
        var media = jQuery('.product.media.product_v2'),
            media_v4 = jQuery('.product.media.product_v4'),
            swipeOff = jQuery('.swipe_desktop_off #swipeOff');

        if (jQuery(window).width() > 768) {
            media.addClass('v2');
            media_v4.addClass('v4');
        } else {
            media.removeClass('v2');
            media_v4.removeClass('v4');
        }

        if (jQuery(window).width() > 1024) {
            swipeOff.addClass('active');
        } else {
            swipeOff.removeClass('active');
        }
    },

    addMinHeight: function () {

        var heightMatch = jQuery('.product-info-main').outerHeight();
        var mediaContainer = jQuery('.product.media');
        mediaContainer.css('min-height', heightMatch);


    },

    waitForEl: function (selector, callback) {
        var that = this;
        if (jQuery(selector).length) {
            callback();
        } else {
            setTimeout(function () {
                that.waitForEl(selector, callback);
            }, 500);
        }
    },

    bindStickyScroll: function () {
        var productInfoMain = jQuery('.MagicToolboxContainer'),
            productInfoMainLeft = parseInt(productInfoMain.offset().left),
            productInfoMainWidth = parseInt(productInfoMain.width()),
            bottomCorrection = '27px',
            leftCorrection = productInfoMainLeft - 15 + 'px',
            topOffset = 136,
            topOffsetV2 = jQuery('.product-info-main').offset().top,
            lastScrollTop = 0,
            fixedPos = 0;

        jQuery(window).bind('scroll', function () {
            var scrollTopPos = parseInt(jQuery(window).scrollTop()),
                scrollPos = parseInt(jQuery(window).scrollTop()) + parseInt(jQuery(window).outerHeight()),
                productInfoMainBottom = parseInt(productInfoMain.offset().top) + parseInt(productInfoMain.outerHeight()),
                topPos = scrollTopPos + parseInt(productInfoMain.outerHeight()) + 95,
                productInfoMainTop = parseInt(productInfoMain.offset().top) - parseInt(productInfoMain.css('top')),
                footerEl = jQuery('.product-info-main'),
                imageWrapper = jQuery('.product.media'),
                footerOffset = parseInt(footerEl.offset().top) + parseInt(footerEl.outerHeight() - 20),
                scrollDir = 'dwn';

            if (scrollTopPos > lastScrollTop) {
                scrollDir = 'dwn';
            } else {
                scrollDir = 'up';
            }


            console.log("productInfoMainBottom" + productInfoMainBottom);
            console.log("footerOffset" + footerOffset);
            console.log("scrollPos" + scrollPos);
            console.log("topPos" + topPos);
            console.log("scrollTopPos" + scrollTopPos);
            console.log("fixedPos" + fixedPos);

            if (scrollTopPos >= 0 && scrollTopPos <= topOffsetV2) {
                imageWrapper.removeClass('pp-fixed-height');
                productInfoMain.removeClass('pp-fixed').removeAttr('style');
            } else if (scrollTopPos <= (footerOffset - parseInt(productInfoMain.outerHeight())) && scrollTopPos >= topOffsetV2 && productInfoMainBottom <= footerOffset) {
                console.log("ONE");
                imageWrapper.removeClass('pp-fixed-height');

                productInfoMain.addClass('pp-fixed').removeAttr('style').css({
                    'left': productInfoMainLeft + 'px',
                    'width': productInfoMainWidth + 'px'
                });
            } else if (productInfoMainTop > topOffsetV2 && scrollDir == 'up' && productInfoMainBottom <= footerOffset && topPos <= footerOffset) {
                console.log("TWO");
                imageWrapper.removeClass('pp-fixed-height');
                productInfoMain.addClass('pp-fixed').removeAttr('style').css({
                    'left': productInfoMainLeft + 'px',
                    'width': productInfoMainWidth + 'px'
                });
            } else if (productInfoMainBottom >= footerOffset &&
                scrollPos >= footerOffset &&
                topPos >= footerOffset &&
                scrollTopPos >= fixedPos) {
                console.log("THREE");
                imageWrapper.addClass('pp-fixed-height');
                if (fixedPos == 0) fixedPos = scrollTopPos;

                productInfoMain.removeClass('pp-fixed').removeAttr('style').css({
                    'margin': '0 !important',
                    'padding': '0 !important',
                    'position': 'absolute',
                    'bottom': '0',
                    'width': productInfoMainWidth + 'px'
                });


            } else if (scrollTopPos <= fixedPos && scrollDir == 'up') {
                console.log("FOUR");
                imageWrapper.removeClass('pp-fixed-height');
                fixedPos = 0;
                productInfoMain.addClass('pp-fixed').removeAttr('style').css({
                    'left': productInfoMainLeft + 'px',
                    'width': productInfoMainWidth + 'px'
                });
            } else {
                console.log("FIVE");
                // imageWrapper.removeClass('pp-fixed-height');
                // productInfoMain.removeAttr('style').css({'left': productInfoMainLeft+'px', 'width': productInfoMainWidth+'px'});
            }

            lastScrollTop = scrollTopPos;
        })
    }

};


require(['jquery', 'productPage', 'mage/mage', 'mage/ie-class-fixer', 'mage/gallery/gallery'],
    function ($) {
        $(document).ready(function () {

            //togglet - newsletter opening only?
            productPage.init();
        });

        $(window).load(function () {
            productPage.load();
            var positionProductInfo = window.positionProductInfo;
            var isMobileCheck = jQuery('body').hasClass('wp-device-xs');
            if (positionProductInfo == 1 && !isMobileCheck) {
                productPage.bindStickyScroll();

            }
            $('.product-info-main').removeClass('pp-floating-v4');
            if (!isMobileCheck && $('.product-info-main').hasClass('product_v4')) {
                $('.product-info-main').addClass('pp-floating-v4');
            }

        });

        $(document).ajaxComplete(function () {
            productPage.ajaxComplete();
        });


        var reinitTimer;
        $(window).on('resize', function () {
            clearTimeout(reinitTimer);
            reinitTimer = setTimeout(productPage.resize(), 300);
        });

        var headerSection = $('.page-wrapper div.page-header');
        var stickyElement = $('.product-info-main.cart-summary');
        /*$(window).scroll(function() {
            if (headerSection.hasClass('sticky-header')) {
				$(stickyElement.children().get(0)).css('padding-top', headerSection.height());
            } else {
                $(stickyElement.children().get(0)).css('padding-top', 0);
			}
        });*/
    }
);
