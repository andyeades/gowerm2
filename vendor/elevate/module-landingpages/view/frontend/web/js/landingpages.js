var ELEVATE = ELEVATE || {};


(function ($, window) {

    ELEVATE.Landingpages = (function (options) {

        var self = this;
        var disable_click_flag = false;
        var dragging = false;
        var default_options = {
            enableHistory: true,
            enableHistory: 768
        };
//call like (self.options.enableHistory
        this.options = $.extend(true, {}, default_options, typeof options === 'object' && options);

        function saveState(url, title, html) {

            if (this.options.enableHistory) {

                var state = {
                    products_list: html.products_list,
                    left_nav: html.left_nav,
                    top_description: html.top_description,
                    bottom_description: html.bottom_description,
                    breadcrumbs: html.breadcrumbs,
                    total_column: html.total_column
                };
                //   console.log(state);
                history.pushState(state, title, url);
            }
        }

        function updateContent2(html) {


            if (html.total_column) {


                jQuery('.column.main').html(html.total_column).promise().done(function () {

                });
            }


            if (html.left_nav) {

                jQuery('.sidebar-main').html(html.left_nav).promise().done(function () {
                    attachEvents();
                });


            }

        }

        function updateContent(html) {


            if (html.products_list) {

                jQuery('.products-grid').replaceWith(html.products_list).promise().done(function () {

                });


            }
            // if (html.title) {

            jQuery('.page-title span').html(html.title).promise().done(function () {

            });

            //  }
            if (html.breadcrumbs) {

                jQuery('.breadcrumbs').html(html.breadcrumbs).promise().done(function () {

                });

            }
            // if (html.top_description) {

            jQuery('.category-view').html(html.top_description).promise().done(function () {

            });

            // }
            // if (html.bottom_description) {

            jQuery('#ev_ln_btm_description_wrapper').html(html.bottom_description).promise().done(function () {

            });

            // }


            if (html.left_nav) {

                jQuery('.sidebar-main').html(html.left_nav).promise().done(function () {
                    attachEvents();
                });


            }

        }

        function init() {
            //       console.log("INIT4");


            //detect drag
            jQuery("body").on("touchmove", function () {
                dragging = true;
            });

            jQuery("body").on("touchstart", function () {
                dragging = false;
            });

            //disable url history if not available in browser
            if (typeof (history) === 'undefined') {
                // console.log("SET TO FALSE");
                self.options.enableHistory = false;
            }

            if (self.options.enableHistory) {

                //console.log("HISTORY ENABLED");

                var currentUrl = window.location.href;
                var url = new URL(currentUrl);

                var html_data = {
                    products_list: jQuery('.products-grid').html(),
                    left_nav: jQuery('.sidebar-main').html(),
                    title: jQuery('.page-title span').html(),
                    top_description: jQuery('.category-view').html(),
                    bottom_description: jQuery('#ev_ln_btm_description_wrapper').html(),
                    breadcrumbs: jQuery('.breadcrumbs').html(),
                    total_column: jQuery('.column.main').html(),

                };
                var data = {html: html_data};


                var newUrl = url.href;

                saveState(newUrl, 'title', data.html);


                // history.pushState({}, '');
                window.addEventListener('popstate', function (event) {


                    try {

                        var state = window.history.state;

                        //  var data = state;
                        if (state !== null) {
                            /* jQuery('.toolbar-products').remove(); */
                            updateContent2(state);


                            //   this.handlePriceSliders();

                        } else {

                        }
                    } catch (e) {
                        if (e.code === 22) {
                            // we've hit our local storage limit! lets remove 1/3rd of the entries (hopefully chronologically)
                            // and try again... If we fail to remove entries, lets silently give up
                            console.log('Local storage capacity reached.')

                            var maxLength = localStorage.length
                                , reduceBy = ~~(maxLength / 3);

                            for (var i = 0; i < reduceBy; i++) {
                                if (localStorage.key(0)) {
                                    localStorage.removeItem(localStorage.key(0));
                                } else break;
                            }

                            if (localStorage.length < maxLength) {
                                console.log('Cache data reduced to fit new entries. (' + maxLength + ' => ' + localStorage.length + ')');
                                public.set(param, value);
                            } else {
                                console.log('Could not reduce cache size. Removing session cache setting from this instance.');
                                public.set = function () {
                                }
                            }
                        }
                    }

                }.bind(this));
            }
            attachEvents();
        }

        function attachEvents() {


            jQuery('.ev_ln_filter_click').on('click touchend', function (e) {

                if (dragging) {
                    return;
                }
                e.preventDefault();

                jQuery('body').css('overflow', 'hidden');
                jQuery('html, body').animate({scrollTop: '0px'}, 300);
                jQuery('.ev_ln_filter').addClass('ev_ln_filteropen');
                jQuery('.ev_ln_overlay').addClass('ev_ln_overlay_open');


            });

//ev_ln_filter_close - close x button (currently conflicting with clear all
            jQuery('.ev_ln_filter_close, .ev_ln_overlay, .ev_ln_filter .filter-panel__apply .button--full').on('click touchend', function (e) {
                jQuery('body').css('overflow', 'auto');
                //eades
                jQuery('.ev_ln_filter').removeClass('ev_ln_filteropen');
                jQuery('.ev_ln_overlay').removeClass('ev_ln_overlay_open');
            });

            jQuery('.ev_ln_item label, .layerclick, .pages-items .item').on('click touchend', function (e) {
                if (dragging) {
                    return;
                }
                jQuery('body').css('overflow', 'auto');
                //console.log("check2");
                e.stopPropagation();
                e.preventDefault();
                var el = $(this).find('a');
                var url = el.attr("href");

                if (url) {
                    handleLayer(url);
                }

            });


            jQuery('#sorter').on('change', function () {
                jQuery('body').css('overflow', 'auto');
                var sortOrder = this.value;

                var currentUrl = window.location.href;
                var url = new URL(currentUrl);
                //handle the sort by options
                url.searchParams.set("product_list_order", sortOrder); // setting your param
                var newUrl = url.href;

                if (url) {
                    handleLayer(newUrl);
                }
                //  console.log(newUrl);

            });

        }

        function handleLayer(url) {


            //remove the overlaye
            var multi_filter = false;
            if (multi_filter) {

            }

            jQuery('.ev_ln_overlay').removeClass('ev_ln_overlay_open');
            jQuery('body').css('overflow', 'auto'); //allow body scroll
            window.scrollTo(0, 0);

            var data = '';
            data += '&isAjax=1'; //tell plugin isajax to respond back category in js

            var tS = new Date().getTime();
            data += '&ts=' + tS; //get around caching issues - always force unique - needs review - caching desireable

            ELEVATE.Spinner.getSpinner('.products-grid');
            ELEVATE.Spinner.getSpinner('.sidebar-main');

            jQuery('.toolbar-products').remove();

            jQuery.ajax({
                url: url,
                dataType: 'json',
                type: 'post',
                data: data,
                success: function (data) {


                    //  jQuery('.toolbar-products').remove();

                    updateContent(data.html);
                    data.html.total_column = data.html.products_list;
                    //save this state into the history
                    if (self.options.enableHistory) {
                        saveState(url, 'title', data.html);
                    }


                    //    if (content.filters) {
                    //        $(this.options.layeredNavigationFilterBlock).replaceWith(content.filters);
                    //        $(this.options.layeredNavigationFilterBlock).trigger('contentUpdated');
                    //    }


                },
                error: function (data) {
                    console.log(data);
                }
            });

        }

        return {
            init: function () {
                init();
            },
            handlelayer: function (url) {
                handleLayer(url);
            }
        };

    }());

}(jQuery.noConflict(), window));
