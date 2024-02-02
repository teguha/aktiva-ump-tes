const RoleModule = function () {
	let moduleName = 'backend_setting_role';
	let moduleScope = '[data-module-name="backend_setting_role"]';
	
	return {
		init: function () {
			if (ModuleApp.getName() === moduleName) {
				console.log('Run your script/function module here.');
				// Example call base functions
				// BaseApp.test();
			}
		},
		documentEvent: function () {
			$(document).on('click', moduleScope+' .btn-test', function () {
				// Example call base functions
				BaseApp.test();
			});
		}
	}
}();

// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = RoleModule;
}