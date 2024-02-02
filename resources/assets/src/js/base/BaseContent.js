/*
 * BASE CONTENT
 */

const BaseContent = function () {
    return {
        init: function () {
            this.handlePushStyles();
        },
        replace: function (element, options = {}) {
            var el = $(element);
            var url = el.attr('href') ?? el.data('url');

            BaseContent.replaceByUrl(url, options);
        },
        replaceByUrl: function (url, options = {}) {
            options.url = url;
            var useReplace = $('meta[name="replace"]').attr('content');
            if (useReplace && $.isFunction(window.history.pushState)) {
                var contentLoading = setTimeout(function(){ 
                    BaseContent.loader(true, false); 
                }, 500);
                $.ajax({
                    url: url,
                    dataType: 'html',
                    headers: { 'Base-Replace-Content':true },
                    beforeSend: function() {
                        BaseContent.clearBody();
                    },
                    success: function(resp) {
                        clearTimeout(contentLoading);
                        BaseContent.handleContentPageState(resp, options);
                        BaseContent.handlePushStyles();
                        BaseContent.loader(false);
                    },
                    error: function() {
                        clearTimeout(contentLoading);
                        BaseContent.loader(false);
                        BaseUtil.redirect(url);
                    }
                });
            }
            else {
                BaseContent.loader(true, false); 
                setTimeout(function(){ 
                    BaseUtil.redirect(url);
                }, window.RemoveCacheTime ?? 0);
            }
        },
        clearBody: function () {
            $('body>:not(.no-body-clear,#gritter-notice-wrapper,.swal-overlay,.phpdebugbar,.phpdebugbar-openhandler,.phpdebugbar-openhandler-overlay)').remove();
        },
        loader: function (loading = true, fullbackdrop = true) {
            let body = $('body');
            if (loading === true) {
                if (!body.hasClass('content-loading')) {
                    body.addClass('content-loading');
                    if (fullbackdrop === true) {
                        body.addClass('full-backdrop');
                    }
                }
            } 
            else {
                body.removeClass('content-loading full-backdrop');
            }
        },
        handlePushStyles: function () {
            $('head [data-content-page-style="true"]').remove();
            $('#content style').attr('data-content-page-style', true).appendTo('head');
        },
        handleContentPageState: function (resp, options) {
            if (resp.includes('$(document).on')) {
                Swal.fire("Change $(document).on to $('.content-page') or $('.page')");
                return false;
            }

            $('#content').html(resp);
            var dataContent = $('#content').find('.base-content--state').first();
                
            if (dataContent.length) {
                var state = {
                    title: dataContent.data('title') ? dataContent.data('title') : $('title').text,
                    url: dataContent.data('url') ? dataContent.data('url') : options.url,
                };
                window.history.pushState(state, state.title, state.url);
                document.title = state.title;
                // Refresh token
                var token = dataContent.data('csrf-token');
                if (token) {
                    $('meta[name="csrf-token"]').attr('content', token);
                }
                // Reload Plugins and component
                BaseList.init();
                BasePlugin.init();
                BaseUtil.refreshComponent();
                // Check Notification
                var lastNotify = $('.base-notification-wrapper').data('last-user-notification');
                if (dataContent.data('last-user-notification') != lastNotify) {
                    BaseUtil.userNotification();
                }
                // Reload ModuleApp
                ModuleApp.init();

                if ($.isFunction(options.callback)) {
                    options.callback();
                }
                $('body,html').animate({scrollTop: 0}, 500);
            }
            else {
                $('#content').css('opacity', 0);
                BaseUtil.redirect(options.url);
            }
        },
        documentEvent: function () {
            $(document).on('click', '.base-content--replace', function(e) {
                e.preventDefault();
                BaseContent.replace(this);
            });
        }
    }
}();

// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = BaseContent;
}