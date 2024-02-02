/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/assets/src/js/modules.js":
/*!********************************************!*\
  !*** ./resources/assets/src/js/modules.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

eval("window.ModuleApp = __webpack_require__(/*! ./modules/ModuleApp.js */ \"./resources/assets/src/js/modules/ModuleApp.js\");//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvYXNzZXRzL3NyYy9qcy9tb2R1bGVzLmpzLmpzIiwibWFwcGluZ3MiOiJBQUFBQSxNQUFNLENBQUNDLFNBQVAsR0FBbUJDLG1CQUFPLENBQUMsOEVBQUQsQ0FBMUIiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvYXNzZXRzL3NyYy9qcy9tb2R1bGVzLmpzPzNlNDgiXSwic291cmNlc0NvbnRlbnQiOlsid2luZG93Lk1vZHVsZUFwcCA9IHJlcXVpcmUoJy4vbW9kdWxlcy9Nb2R1bGVBcHAuanMnKTsiXSwibmFtZXMiOlsid2luZG93IiwiTW9kdWxlQXBwIiwicmVxdWlyZSJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/assets/src/js/modules.js\n");

/***/ }),

/***/ "./resources/assets/src/js/modules/ModuleApp.js":
/*!******************************************************!*\
  !*** ./resources/assets/src/js/modules/ModuleApp.js ***!
  \******************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

eval("window.UserModule = __webpack_require__(/*! ./setting/user/UserModule.js */ \"./resources/assets/src/js/modules/setting/user/UserModule.js\");\nwindow.RoleModule = __webpack_require__(/*! ./setting/role/RoleModule.js */ \"./resources/assets/src/js/modules/setting/role/RoleModule.js\");\n\nvar ModuleApp = function () {\n  return {\n    getName: function getName() {\n      return $('.content .content-page').data('module-name');\n    },\n    // Auto call on reload page or content replaced\n    init: function init() {\n      var moduleName = this.getName();\n\n      switch (moduleName) {\n        case 'backend_setting_user':\n          // script nodule scope\n          UserModule.init();\n          break;\n\n        case 'backend_setting_role':\n          // script nodule scope\n          RoleModule.init();\n          break;\n      }\n    },\n    // Auto call on reload page\n    documentEvent: function documentEvent() {\n      UserModule.documentEvent();\n      RoleModule.documentEvent();\n    }\n  };\n}(); // webpack support\n\n\nif ( true && typeof module.exports !== 'undefined') {\n  module.exports = ModuleApp;\n}\n\n$(function () {\n  ModuleApp.init();\n  ModuleApp.documentEvent();\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvYXNzZXRzL3NyYy9qcy9tb2R1bGVzL01vZHVsZUFwcC5qcy5qcyIsIm1hcHBpbmdzIjoiQUFBQUEsTUFBTSxDQUFDQyxVQUFQLEdBQW9CQyxtQkFBTyxDQUFDLGtHQUFELENBQTNCO0FBQ0FGLE1BQU0sQ0FBQ0csVUFBUCxHQUFvQkQsbUJBQU8sQ0FBQyxrR0FBRCxDQUEzQjs7QUFFQSxJQUFNRSxTQUFTLEdBQUcsWUFBWTtBQUM3QixTQUFPO0FBQ05DLElBQUFBLE9BQU8sRUFBRSxtQkFBWTtBQUNwQixhQUFPQyxDQUFDLENBQUMsd0JBQUQsQ0FBRCxDQUE0QkMsSUFBNUIsQ0FBaUMsYUFBakMsQ0FBUDtBQUNBLEtBSEs7QUFJTjtBQUNBQyxJQUFBQSxJQUFJLEVBQUUsZ0JBQVk7QUFDakIsVUFBSUMsVUFBVSxHQUFHLEtBQUtKLE9BQUwsRUFBakI7O0FBQ0EsY0FBT0ksVUFBUDtBQUNDLGFBQUssc0JBQUw7QUFDQztBQUNBUixVQUFBQSxVQUFVLENBQUNPLElBQVg7QUFDRDs7QUFDQSxhQUFLLHNCQUFMO0FBQ0M7QUFDQUwsVUFBQUEsVUFBVSxDQUFDSyxJQUFYO0FBQ0Q7QUFSRDtBQVVBLEtBakJLO0FBa0JOO0FBQ0FFLElBQUFBLGFBQWEsRUFBRSx5QkFBWTtBQUMxQlQsTUFBQUEsVUFBVSxDQUFDUyxhQUFYO0FBQ0FQLE1BQUFBLFVBQVUsQ0FBQ08sYUFBWDtBQUNBO0FBdEJLLEdBQVA7QUF3QkEsQ0F6QmlCLEVBQWxCLEMsQ0EyQkE7OztBQUNBLElBQUksU0FBaUMsT0FBT0MsTUFBTSxDQUFDQyxPQUFkLEtBQTBCLFdBQS9ELEVBQTRFO0FBQ3hFRCxFQUFBQSxNQUFNLENBQUNDLE9BQVAsR0FBaUJSLFNBQWpCO0FBQ0g7O0FBRURFLENBQUMsQ0FBQyxZQUFZO0FBQ2JGLEVBQUFBLFNBQVMsQ0FBQ0ksSUFBVjtBQUNBSixFQUFBQSxTQUFTLENBQUNNLGFBQVY7QUFDQSxDQUhBLENBQUQiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvYXNzZXRzL3NyYy9qcy9tb2R1bGVzL01vZHVsZUFwcC5qcz85OGUxIl0sInNvdXJjZXNDb250ZW50IjpbIndpbmRvdy5Vc2VyTW9kdWxlID0gcmVxdWlyZSgnLi9zZXR0aW5nL3VzZXIvVXNlck1vZHVsZS5qcycpO1xyXG53aW5kb3cuUm9sZU1vZHVsZSA9IHJlcXVpcmUoJy4vc2V0dGluZy9yb2xlL1JvbGVNb2R1bGUuanMnKTtcclxuXHJcbmNvbnN0IE1vZHVsZUFwcCA9IGZ1bmN0aW9uICgpIHtcclxuXHRyZXR1cm4ge1xyXG5cdFx0Z2V0TmFtZTogZnVuY3Rpb24gKCkge1xyXG5cdFx0XHRyZXR1cm4gJCgnLmNvbnRlbnQgLmNvbnRlbnQtcGFnZScpLmRhdGEoJ21vZHVsZS1uYW1lJyk7XHJcblx0XHR9LFxyXG5cdFx0Ly8gQXV0byBjYWxsIG9uIHJlbG9hZCBwYWdlIG9yIGNvbnRlbnQgcmVwbGFjZWRcclxuXHRcdGluaXQ6IGZ1bmN0aW9uICgpIHtcclxuXHRcdFx0dmFyIG1vZHVsZU5hbWUgPSB0aGlzLmdldE5hbWUoKTtcclxuXHRcdFx0c3dpdGNoKG1vZHVsZU5hbWUpIHtcclxuXHRcdFx0XHRjYXNlICdiYWNrZW5kX3NldHRpbmdfdXNlcic6XHJcblx0XHRcdFx0XHQvLyBzY3JpcHQgbm9kdWxlIHNjb3BlXHJcblx0XHRcdFx0XHRVc2VyTW9kdWxlLmluaXQoKTtcclxuXHRcdFx0XHRicmVhaztcclxuXHRcdFx0XHRjYXNlICdiYWNrZW5kX3NldHRpbmdfcm9sZSc6XHJcblx0XHRcdFx0XHQvLyBzY3JpcHQgbm9kdWxlIHNjb3BlXHJcblx0XHRcdFx0XHRSb2xlTW9kdWxlLmluaXQoKTtcclxuXHRcdFx0XHRicmVhaztcclxuXHRcdFx0fVxyXG5cdFx0fSxcclxuXHRcdC8vIEF1dG8gY2FsbCBvbiByZWxvYWQgcGFnZVxyXG5cdFx0ZG9jdW1lbnRFdmVudDogZnVuY3Rpb24gKCkge1xyXG5cdFx0XHRVc2VyTW9kdWxlLmRvY3VtZW50RXZlbnQoKTtcclxuXHRcdFx0Um9sZU1vZHVsZS5kb2N1bWVudEV2ZW50KCk7XHJcblx0XHR9XHJcblx0fVxyXG59KCk7XHJcblxyXG4vLyB3ZWJwYWNrIHN1cHBvcnRcclxuaWYgKHR5cGVvZiBtb2R1bGUgIT09ICd1bmRlZmluZWQnICYmIHR5cGVvZiBtb2R1bGUuZXhwb3J0cyAhPT0gJ3VuZGVmaW5lZCcpIHtcclxuICAgIG1vZHVsZS5leHBvcnRzID0gTW9kdWxlQXBwO1xyXG59XHJcblxyXG4kKGZ1bmN0aW9uICgpIHtcclxuXHRNb2R1bGVBcHAuaW5pdCgpO1xyXG5cdE1vZHVsZUFwcC5kb2N1bWVudEV2ZW50KCk7XHJcbn0pOyJdLCJuYW1lcyI6WyJ3aW5kb3ciLCJVc2VyTW9kdWxlIiwicmVxdWlyZSIsIlJvbGVNb2R1bGUiLCJNb2R1bGVBcHAiLCJnZXROYW1lIiwiJCIsImRhdGEiLCJpbml0IiwibW9kdWxlTmFtZSIsImRvY3VtZW50RXZlbnQiLCJtb2R1bGUiLCJleHBvcnRzIl0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./resources/assets/src/js/modules/ModuleApp.js\n");

/***/ }),

/***/ "./resources/assets/src/js/modules/setting/role/RoleModule.js":
/*!********************************************************************!*\
  !*** ./resources/assets/src/js/modules/setting/role/RoleModule.js ***!
  \********************************************************************/
/***/ ((module) => {

eval("var RoleModule = function () {\n  var moduleName = 'backend_setting_role';\n  var moduleScope = '[data-module-name=\"backend_setting_role\"]';\n  return {\n    init: function init() {\n      if (ModuleApp.getName() === moduleName) {\n        console.log('Run your script/function module here.'); // Example call base functions\n        // BaseApp.test();\n      }\n    },\n    documentEvent: function documentEvent() {\n      $(document).on('click', moduleScope + ' .btn-test', function () {\n        // Example call base functions\n        BaseApp.test();\n      });\n    }\n  };\n}(); // webpack support\n\n\nif ( true && typeof module.exports !== 'undefined') {\n  module.exports = RoleModule;\n}//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvYXNzZXRzL3NyYy9qcy9tb2R1bGVzL3NldHRpbmcvcm9sZS9Sb2xlTW9kdWxlLmpzLmpzIiwibWFwcGluZ3MiOiJBQUFBLElBQU1BLFVBQVUsR0FBRyxZQUFZO0FBQzlCLE1BQUlDLFVBQVUsR0FBRyxzQkFBakI7QUFDQSxNQUFJQyxXQUFXLEdBQUcsMkNBQWxCO0FBRUEsU0FBTztBQUNOQyxJQUFBQSxJQUFJLEVBQUUsZ0JBQVk7QUFDakIsVUFBSUMsU0FBUyxDQUFDQyxPQUFWLE9BQXdCSixVQUE1QixFQUF3QztBQUN2Q0ssUUFBQUEsT0FBTyxDQUFDQyxHQUFSLENBQVksdUNBQVosRUFEdUMsQ0FFdkM7QUFDQTtBQUNBO0FBQ0QsS0FQSztBQVFOQyxJQUFBQSxhQUFhLEVBQUUseUJBQVk7QUFDMUJDLE1BQUFBLENBQUMsQ0FBQ0MsUUFBRCxDQUFELENBQVlDLEVBQVosQ0FBZSxPQUFmLEVBQXdCVCxXQUFXLEdBQUMsWUFBcEMsRUFBa0QsWUFBWTtBQUM3RDtBQUNBVSxRQUFBQSxPQUFPLENBQUNDLElBQVI7QUFDQSxPQUhEO0FBSUE7QUFiSyxHQUFQO0FBZUEsQ0FuQmtCLEVBQW5CLEMsQ0FxQkE7OztBQUNBLElBQUksU0FBaUMsT0FBT0MsTUFBTSxDQUFDQyxPQUFkLEtBQTBCLFdBQS9ELEVBQTRFO0FBQ3hFRCxFQUFBQSxNQUFNLENBQUNDLE9BQVAsR0FBaUJmLFVBQWpCO0FBQ0giLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvYXNzZXRzL3NyYy9qcy9tb2R1bGVzL3NldHRpbmcvcm9sZS9Sb2xlTW9kdWxlLmpzP2M1NTAiXSwic291cmNlc0NvbnRlbnQiOlsiY29uc3QgUm9sZU1vZHVsZSA9IGZ1bmN0aW9uICgpIHtcclxuXHRsZXQgbW9kdWxlTmFtZSA9ICdiYWNrZW5kX3NldHRpbmdfcm9sZSc7XHJcblx0bGV0IG1vZHVsZVNjb3BlID0gJ1tkYXRhLW1vZHVsZS1uYW1lPVwiYmFja2VuZF9zZXR0aW5nX3JvbGVcIl0nO1xyXG5cdFxyXG5cdHJldHVybiB7XHJcblx0XHRpbml0OiBmdW5jdGlvbiAoKSB7XHJcblx0XHRcdGlmIChNb2R1bGVBcHAuZ2V0TmFtZSgpID09PSBtb2R1bGVOYW1lKSB7XHJcblx0XHRcdFx0Y29uc29sZS5sb2coJ1J1biB5b3VyIHNjcmlwdC9mdW5jdGlvbiBtb2R1bGUgaGVyZS4nKTtcclxuXHRcdFx0XHQvLyBFeGFtcGxlIGNhbGwgYmFzZSBmdW5jdGlvbnNcclxuXHRcdFx0XHQvLyBCYXNlQXBwLnRlc3QoKTtcclxuXHRcdFx0fVxyXG5cdFx0fSxcclxuXHRcdGRvY3VtZW50RXZlbnQ6IGZ1bmN0aW9uICgpIHtcclxuXHRcdFx0JChkb2N1bWVudCkub24oJ2NsaWNrJywgbW9kdWxlU2NvcGUrJyAuYnRuLXRlc3QnLCBmdW5jdGlvbiAoKSB7XHJcblx0XHRcdFx0Ly8gRXhhbXBsZSBjYWxsIGJhc2UgZnVuY3Rpb25zXHJcblx0XHRcdFx0QmFzZUFwcC50ZXN0KCk7XHJcblx0XHRcdH0pO1xyXG5cdFx0fVxyXG5cdH1cclxufSgpO1xyXG5cclxuLy8gd2VicGFjayBzdXBwb3J0XHJcbmlmICh0eXBlb2YgbW9kdWxlICE9PSAndW5kZWZpbmVkJyAmJiB0eXBlb2YgbW9kdWxlLmV4cG9ydHMgIT09ICd1bmRlZmluZWQnKSB7XHJcbiAgICBtb2R1bGUuZXhwb3J0cyA9IFJvbGVNb2R1bGU7XHJcbn0iXSwibmFtZXMiOlsiUm9sZU1vZHVsZSIsIm1vZHVsZU5hbWUiLCJtb2R1bGVTY29wZSIsImluaXQiLCJNb2R1bGVBcHAiLCJnZXROYW1lIiwiY29uc29sZSIsImxvZyIsImRvY3VtZW50RXZlbnQiLCIkIiwiZG9jdW1lbnQiLCJvbiIsIkJhc2VBcHAiLCJ0ZXN0IiwibW9kdWxlIiwiZXhwb3J0cyJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/assets/src/js/modules/setting/role/RoleModule.js\n");

/***/ }),

/***/ "./resources/assets/src/js/modules/setting/user/UserModule.js":
/*!********************************************************************!*\
  !*** ./resources/assets/src/js/modules/setting/user/UserModule.js ***!
  \********************************************************************/
/***/ ((module) => {

eval("var UserModule = function () {\n  var moduleName = 'backend_setting_user';\n  var moduleScope = '[data-module-name=\"backend_setting_user\"]';\n  return {\n    init: function init() {\n      if (ModuleApp.getName() == moduleName) {\n        console.log('Run your script/function module here.');\n      }\n    },\n    documentEvent: function documentEvent() {\n      $(document).on('click', moduleScope + ' .btn-test', function () {\n        // Example call Base functions\n        BaseApp.test();\n      });\n    }\n  };\n}(); // webpack support\n\n\nif ( true && typeof module.exports !== 'undefined') {\n  module.exports = UserModule;\n}//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvYXNzZXRzL3NyYy9qcy9tb2R1bGVzL3NldHRpbmcvdXNlci9Vc2VyTW9kdWxlLmpzLmpzIiwibWFwcGluZ3MiOiJBQUFBLElBQU1BLFVBQVUsR0FBRyxZQUFZO0FBQzlCLE1BQUlDLFVBQVUsR0FBRyxzQkFBakI7QUFDQSxNQUFJQyxXQUFXLEdBQUcsMkNBQWxCO0FBRUEsU0FBTztBQUNOQyxJQUFBQSxJQUFJLEVBQUUsZ0JBQVk7QUFDakIsVUFBSUMsU0FBUyxDQUFDQyxPQUFWLE1BQXVCSixVQUEzQixFQUF1QztBQUN0Q0ssUUFBQUEsT0FBTyxDQUFDQyxHQUFSLENBQVksdUNBQVo7QUFDQTtBQUNELEtBTEs7QUFNTkMsSUFBQUEsYUFBYSxFQUFFLHlCQUFZO0FBQzFCQyxNQUFBQSxDQUFDLENBQUNDLFFBQUQsQ0FBRCxDQUFZQyxFQUFaLENBQWUsT0FBZixFQUF3QlQsV0FBVyxHQUFDLFlBQXBDLEVBQWtELFlBQVk7QUFDN0Q7QUFDQVUsUUFBQUEsT0FBTyxDQUFDQyxJQUFSO0FBQ0EsT0FIRDtBQUlBO0FBWEssR0FBUDtBQWFBLENBakJrQixFQUFuQixDLENBbUJBOzs7QUFDQSxJQUFJLFNBQWlDLE9BQU9DLE1BQU0sQ0FBQ0MsT0FBZCxLQUEwQixXQUEvRCxFQUE0RTtBQUN4RUQsRUFBQUEsTUFBTSxDQUFDQyxPQUFQLEdBQWlCZixVQUFqQjtBQUNIIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vcmVzb3VyY2VzL2Fzc2V0cy9zcmMvanMvbW9kdWxlcy9zZXR0aW5nL3VzZXIvVXNlck1vZHVsZS5qcz83MmNiIl0sInNvdXJjZXNDb250ZW50IjpbImNvbnN0IFVzZXJNb2R1bGUgPSBmdW5jdGlvbiAoKSB7XHJcblx0bGV0IG1vZHVsZU5hbWUgPSAnYmFja2VuZF9zZXR0aW5nX3VzZXInO1xyXG5cdGxldCBtb2R1bGVTY29wZSA9ICdbZGF0YS1tb2R1bGUtbmFtZT1cImJhY2tlbmRfc2V0dGluZ191c2VyXCJdJztcclxuXHRcclxuXHRyZXR1cm4ge1xyXG5cdFx0aW5pdDogZnVuY3Rpb24gKCkge1xyXG5cdFx0XHRpZiAoTW9kdWxlQXBwLmdldE5hbWUoKSA9PSBtb2R1bGVOYW1lKSB7XHJcblx0XHRcdFx0Y29uc29sZS5sb2coJ1J1biB5b3VyIHNjcmlwdC9mdW5jdGlvbiBtb2R1bGUgaGVyZS4nKTtcclxuXHRcdFx0fVxyXG5cdFx0fSxcclxuXHRcdGRvY3VtZW50RXZlbnQ6IGZ1bmN0aW9uICgpIHtcclxuXHRcdFx0JChkb2N1bWVudCkub24oJ2NsaWNrJywgbW9kdWxlU2NvcGUrJyAuYnRuLXRlc3QnLCBmdW5jdGlvbiAoKSB7XHJcblx0XHRcdFx0Ly8gRXhhbXBsZSBjYWxsIEJhc2UgZnVuY3Rpb25zXHJcblx0XHRcdFx0QmFzZUFwcC50ZXN0KCk7XHJcblx0XHRcdH0pO1xyXG5cdFx0fVxyXG5cdH1cclxufSgpO1xyXG5cclxuLy8gd2VicGFjayBzdXBwb3J0XHJcbmlmICh0eXBlb2YgbW9kdWxlICE9PSAndW5kZWZpbmVkJyAmJiB0eXBlb2YgbW9kdWxlLmV4cG9ydHMgIT09ICd1bmRlZmluZWQnKSB7XHJcbiAgICBtb2R1bGUuZXhwb3J0cyA9IFVzZXJNb2R1bGU7XHJcbn0iXSwibmFtZXMiOlsiVXNlck1vZHVsZSIsIm1vZHVsZU5hbWUiLCJtb2R1bGVTY29wZSIsImluaXQiLCJNb2R1bGVBcHAiLCJnZXROYW1lIiwiY29uc29sZSIsImxvZyIsImRvY3VtZW50RXZlbnQiLCIkIiwiZG9jdW1lbnQiLCJvbiIsIkJhc2VBcHAiLCJ0ZXN0IiwibW9kdWxlIiwiZXhwb3J0cyJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/assets/src/js/modules/setting/user/UserModule.js\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./resources/assets/src/js/modules.js");
/******/ 	
/******/ })()
;