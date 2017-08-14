// Sticky Plugin v1.0.3 for jQuery
// =============
// Author: Anthony Garand
// Improvements by German M. Bravo (Kronuz) and Ruud Kamphuis (ruudk)
// Improvements by Leonardo C. Daronco (daronco)
// Created: 02/14/2011
// Date: 07/20/2015
// Website: http://stickyjs.com/
// Description: Makes an element on the page stick on the screen as you scroll
//              It will only set the 'top' and 'position' of your element, you
//              might need to adjust the width in some cases.

(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof module === 'object' && module.exports) {
        // Node/CommonJS
        module.exports = factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {
    var slice = Array.prototype.slice; // save ref to original slice()
    var splice = Array.prototype.splice; // save ref to original slice()

    var defaults = {
            topSpacing: 0,
            bottomSpacing: 0,
            className: 'is-sticky',
            wrapperClassName: 'sticky-wrapper',
            center: false,
            getWidthFrom: '',
            widthFromWrapper: true, // works only when .getWidthFrom is empty
            responsiveWidth: false
        },
        $window = $(window),
        $document = $(document),
        sticked = [],
        windowHeight = $window.height(),
        scroller = function () {
            var scrollTop = $window.scrollTop(),
                documentHeight = $document.height(),
                dwh = documentHeight - windowHeight,
                extra = (scrollTop > dwh) ? dwh - scrollTop : 0;
           /* nfc.pr('============ Start ============');
            nfc.pr('documentHeight:'+documentHeight);
            nfc.pr('windowHeight:'+windowHeight);
            nfc.pr('dwh:'+dwh);
            nfc.pr('extra:'+extra);
            nfc.pr('scrollTop:'+scrollTop);*/

            for (var i = 0, l = sticked.length; i < l; i++) {
                var s = sticked[i],
                    elementTop = s.stickyWrapper.offset().top,
                    elementHeight = s.stickyElement.outerHeight();
                    limiterTop = 0;
                if(s.stickyLimiter)
                    limiterTop= s.stickyLimiter.offset().top;
                scrollTopLimit = scrollTop+ elementHeight;

                etse = elementTop - s.topSpacing - extra;

                //update height in case of dynamic content
                s.stickyWrapper.css('height',elementHeight);
                /*nfc.pr('etse:'+etse);
                nfc.pr('elementTop:'+elementTop);
                nfc.pr('elementHeight:'+elementHeight);
                nfc.pr('limiterTop:'+limiterTop);
                nfc.pr('scrollTopLimit:'+scrollTopLimit);*/

                if (scrollTop <= etse) {
                    if (s.currentTop !== null) {
                        s.stickyElement
                            .css({
                                'width': '',
                                'position': '',
                                'top': ''
                            });
                        s.stickyElement.parent().removeClass(s.className);
                        s.stickyElement.trigger('sticky-end', [s]);
                        s.currentTop = null;
                    }
                }
                else {
                    var newTop = documentHeight - s.stickyElement.outerHeight()
                        - s.topSpacing - s.bottomSpacing - scrollTop - extra;
                    if (newTop < 0) {
                        newTop = newTop + s.topSpacing;
                    } else {
                        newTop = s.topSpacing;
                    }
                    //nfc.pr('--currentTop:'+s.currentTop);
                    //nfc.pr('--newTop:'+newTop);

                    if (s.currentTop !== newTop) {
                        var newWidth;
                        if (s.getWidthFrom) {
                            newWidth = $(s.getWidthFrom).width() || null;
                        } else if (s.widthFromWrapper) {
                            newWidth = s.stickyWrapper.width();
                        }
                        if (newWidth == null) {
                            newWidth = s.stickyElement.width();
                        }

                        if (s.stickyLimiter && scrollTopLimit > limiterTop) {
                            newTop = limiterTop - scrollTopLimit;
                            //nfc.pr('--newTop reset:'+newTop);
                        }

                        s.stickyElement
                            .css('width', newWidth)
                            .css('position', 'fixed')
                            .css('top', newTop);

                        s.stickyElement.parent().addClass(s.className);

                        if (s.currentTop === null) {
                            s.stickyElement.trigger('sticky-start', [s]);
                        } else {
                            // sticky is started but it have to be repositioned
                            s.stickyElement.trigger('sticky-update', [s]);
                        }

                        if (s.currentTop === s.topSpacing && s.currentTop > newTop || s.currentTop === null && newTop < s.topSpacing) {
                            // just reached bottom || just started to stick but bottom is already reached
                            s.stickyElement.trigger('sticky-bottom-reached', [s]);
                        } else if (s.currentTop !== null && newTop === s.topSpacing && s.currentTop < newTop) {
                            // sticky is started && sticked at topSpacing && overflowing from top just finished
                            s.stickyElement.trigger('sticky-bottom-unreached', [s]);
                        }


                        s.currentTop = newTop;
                    }
                    else{
                        if (s.stickyLimiter && scrollTopLimit> limiterTop) {
                            newTop = limiterTop - scrollTopLimit;
                           // nfc.pr('--newTop reset:'+newTop);
                            s.currentTop =newTop;
                        }

                    }
                }
               // nfc.pr('============ End ============');
            }
        },
        resizer = function () {
            windowHeight = $window.height();

            for (var i = 0, l = sticked.length; i < l; i++) {
                var s = sticked[i];
                var newWidth = null;
                if (s.getWidthFrom) {
                    if (s.responsiveWidth) {
                        newWidth = $(s.getWidthFrom).width();
                    }
                } else if (s.widthFromWrapper) {
                    newWidth = s.stickyWrapper.width();
                }
                if (newWidth != null) {
                    s.stickyElement.css('width', newWidth);
                }
            }
        },
        methods = {
            init: function (options) {
                var o = $.extend({}, defaults, options);
                return this.each(function () {
                    var stickyElement = $(this);

                    var stickyId = stickyElement.attr('id');
                    var stickyHeight = stickyElement.outerHeight();
                    var wrapperId = stickyId ? stickyId + '-' + defaults.wrapperClassName : defaults.wrapperClassName;
                    var wrapper = $('<div></div>')
                        .attr('id', wrapperId)
                        .addClass(o.wrapperClassName);

                    stickyElement.wrapAll(wrapper);

                    var stickyWrapper = stickyElement.parent();

                    if (o.center) {
                        stickyWrapper.css({width: stickyElement.outerWidth(), marginLeft: "auto", marginRight: "auto"});
                    }

                    if (stickyElement.css("float") === "right") {
                        stickyElement.css({"float": "none"}).parent().css({"float": "right"});
                    }

                    stickyWrapper.css('height', stickyHeight);

                    var stickyLimiter = null;
                    if (stickyElement.data('limiter') != undefined) {
                        stickyLimiter = $(stickyElement.data('limiter'));
                       // nfc.pr(stickyLimiter);
                    }

                    o.stickyElement = stickyElement;
                    o.stickyWrapper = stickyWrapper;
                    o.stickyLimiter = stickyLimiter;
                    o.currentTop = null;

                    sticked.push(o);
                });
            },
            update: scroller,
            unstick: function (options) {
                return this.each(function () {
                    var that = this;
                    var unstickyElement = $(that);

                    var removeIdx = -1;
                    var i = sticked.length;
                    while (i-- > 0) {
                        if (sticked[i].stickyElement.get(0) === that) {
                            splice.call(sticked, i, 1);
                            removeIdx = i;
                        }
                    }
                    if (removeIdx !== -1) {
                        unstickyElement.unwrap();
                        unstickyElement
                            .css({
                                'width': '',
                                'position': '',
                                'top': '',
                                'float': ''
                            })
                        ;
                    }
                });
            }
        };

    // should be more efficient than using $window.scroll(scroller) and $window.resize(resizer):
    if (window.addEventListener) {
        window.addEventListener('scroll', scroller, false);
        window.addEventListener('resize', resizer, false);
    } else if (window.attachEvent) {
        window.attachEvent('onscroll', scroller);
        window.attachEvent('onresize', resizer);
    }

    $.fn.sticky = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.sticky');
        }
    };

    $.fn.unstick = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.unstick.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.sticky');
        }
    };
    $(function () {
        setTimeout(scroller, 0);
    });
}));
