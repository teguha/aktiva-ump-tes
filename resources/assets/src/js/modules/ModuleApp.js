window.UserModule = require('./setting/user/UserModule.js');
window.RoleModule = require('./setting/role/RoleModule.js');

const ModuleApp = function () {
	return {
		getName: function () {
			return $('.content .content-page').data('module-name');
		},
		// Auto call on reload page or content replaced
		init: function () {
			var moduleName = this.getName();
			switch(moduleName) {
				case 'backend_setting_user':
					// script nodule scope
					UserModule.init();
				break;
				case 'backend_setting_role':
					// script nodule scope
					RoleModule.init();
				break;
			}
		},
		// Auto call on reload page
		documentEvent: function () {
			UserModule.documentEvent();
			RoleModule.documentEvent();
		}
	}
}();

// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = ModuleApp;
}

$(function () {
	ModuleApp.init();
	ModuleApp.documentEvent();
});