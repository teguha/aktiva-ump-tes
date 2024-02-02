const UserModule = function () {
	let moduleName = 'backend_setting_user';
	let moduleScope = '[data-module-name="backend_setting_user"]';
	
	return {
		init: function () {
			if (ModuleApp.getName() == moduleName) {
				console.log('Run your script/function module here.');
			}
		},
		documentEvent: function () {
			$(document).on('click', moduleScope+' .btn-test', function () {
				// Example call Base functions
				BaseApp.test();
			});
		}
	}
}();

// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = UserModule;
}