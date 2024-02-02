const BaseApp = function () {
    return {
        init: function () {
            BaseApp.initToken();
            BaseUtil.init();
            BaseList.init();
            BasePlugin.init();
            BaseContent.init();
        },
        documentEvent: function () {
            BaseUtil.documentEvent();
            BaseContent.documentEvent();
            BaseModal.documentEvent();
            BaseForm.documentEvent();
            BaseList.documentEvent();
        },
        lang: function(key) {
            var lang = BaseAppLang;
            var keys = key.split('.');
            $.each(keys, function (i, val) {
                lang = lang[val];
            });
            return lang;
        },
        initToken: function () {
            window.BaseToken = new Date().getTime();
        },
    }
}();

// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = BaseApp;
}

$(function () {
    BaseApp.init();
    BaseApp.documentEvent();
});
