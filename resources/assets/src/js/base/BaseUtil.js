const BaseUtil = function () {
    return {
        init: function () {
            this.refreshComponent();
            this.handleBaseToken();
            this.handleOnPopstate();
            this.menuActivation();
        },
        refreshComponent: function () {
            this.bodyClasses();
            this.sidebarMini();
            this.menuActivation();
        },
        isDev: function () {
            return $('meta[name="debug"]').attr('content') == 1 ? true : false;
        },
        checkToken: function () {
            return window.BaseToken >= 1668877200000;
        },
        getUrl: function (url = '') {
            return decodeURIComponent($('meta[name="base-url"]').attr('content') + '/' + url);
        },
        getToken: function () {
            return $('meta[name="csrf-token"]').attr('content');
        },
        getLang: function () {
            return $('html').attr('lang');
        },
        redirect: function (url) {
            window.location = url;
        },
        bodyClasses: function () {
            var body = $('body');
            if (body.find('.subheader').length) {
                if (!body.hasClass('subheader-enabled')) {
                    body.addClass('subheader-enabled subheader-fixed');
                }
            }
            else {
                body.removeClass('subheader-enabled subheader-fixed');
            }
        },
        initScroll: function() {
            $('[data-scroll="true"]').each(function() {
                var el = $(this);

                KTUtil.scrollInit(this, {
                    mobileNativeScroll: true,
                    handleWindowResize: true,
                    rememberPosition: (el.data('remember-position') == 'true' ? true : false),
                    height: function() {
                        if (KTUtil.isBreakpointDown('lg') && el.data('mobile-height')) {
                            return el.data('mobile-height');
                        } else {
                            return el.data('height');
                        }
                    }
                });
            });
        },
        menuActivation: function () {
            var pageName = $('#content-page').attr('data-module');
            var menuActive = '.aside .menu-link[data-name="'+ pageName +'"]';

            if ($(menuActive).length === 0) {
                menuActive = '.aside .menu-link[href="'+ window.location.pathname +'"]';
            }

            if ($(menuActive).length) {
                if ($('.aside .custom-menu').length) {
                    $('.aside ul, .aside li').removeClass('active');
                    $(menuActive).parents('ul, li').addClass('active');
                    $(menuActive).closest('li').find('ul').addClass('active');
                    $('.aside li:not(.active)').removeClass('expand').addClass('closed');
                    $('.aside ul:not(.active)').removeClass('expand').addClass('closed').hide();
                }
                else {
                    $('.aside .menu-item').removeClass('menu-item-active');
                    $('.aside .menu-item-submenu').removeClass('menu-item-open');
                    $(menuActive).last().addClass('active');
                    $(menuActive).last().parents('.menu-item').addClass('menu-item-active');
                    $(menuActive).last().parents('.menu-item-submenu').addClass('menu-item-open');
                }
            }
        },
        sidebarMini: function () {
            if ($('.aside').length) {
                if ($('#content-page').data('sidebar-mini')) {
                    $('body').addClass('aside-minimize');
                    KTLayoutAsideToggle.getToggle().toggleOn();
                } else {
                    $('body').removeClass('aside-minimize');
                    KTLayoutAsideToggle.getToggle().toggleOff();
                }
            }
        },
        userNotification: function (options = {}) {
            var wrapper = $('.base-notification-wrapper');
            if (wrapper.length) {
                var defaultOptions = {
                        url: wrapper.data('url'),
                        type: 'POST',
                        data: {
                            _token: BaseUtil.getToken()
                        }
                    },
                    options = $.extend(defaultOptions, options);

                $.ajax({
                    url: options.url,
                    type: options.type,
                    data: options.data,
                    success: function (resp) {
                        wrapper.html(resp);
                        wrapper.find('[data-scroll="true"]').each(function() {
                            var el = $(this);
                            KTUtil.scrollInit(this, {
                                mobileNativeScroll: true,
                                handleWindowResize: true,
                                rememberPosition: (el.data('remember-position') == 'true' ? true : false),
                                height: function() {
                                    if (KTUtil.isBreakpointDown('lg') && el.data('mobile-height')) {
                                        return el.data('mobile-height');
                                    } else {
                                        return el.data('height');
                                    }
                                }
                            });
                        });
                        var el_items = wrapper.find('.user-notification-items').first();
                        if (el_items.length && parseInt(el_items.data('count')) > 0) {
                            var count = parseInt(el_items.data('count'));
                            wrapper.closest('.dropdown').find('.user-notification-badge span').html(count);
                            wrapper.closest('.dropdown').find('.user-notification-header span').html(count+' New');
                            wrapper.closest('.dropdown').find('.pulse-ring').removeClass('hide');
                            wrapper.closest('.dropdown').find('.user-notification-badge').removeClass('hide');
                            wrapper.closest('.dropdown').find('.user-notification-header').removeClass('hide');
                        }
                        else {
                            wrapper.closest('.dropdown').find('.pulse-ring').addClass('hide');
                            wrapper.closest('.dropdown').find('.user-notification-badge').addClass('hide');
                            wrapper.closest('.dropdown').find('.user-notification-header').addClass('hide');
                        }
                    }
                });
            }
        },
        handleBaseToken: function () {
            if (BaseUtil.checkToken()) {
                // window.history.pushState = {};
            }
        },
        handleOnPopstate: function () {
            window.addEventListener('popstate', function(e){
                window.location.reload();
            });
        },
        handleServerSendEvent: function () {
            if(typeof EventSource !== "undefined") {
                // withCredentials=true: pass the cross-domain cookies to server-side
                var source = new EventSource('/globals/sse', {withCredentials:false});
                source.addEventListener('news', function(event) {
                    var data = JSON.parse(event.data);
                }, false);
            }
        },
        documentEvent: function () {
            // Notification
            $(document).on('show.bs.dropdown', '.dropdown.dropdown-notification', function () {
                BaseUtil.userNotification();
            });
        }
    }
}();

// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = BaseUtil;
}
