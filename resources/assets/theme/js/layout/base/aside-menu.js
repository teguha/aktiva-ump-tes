"use strict";

var KTLayoutAsideMenu = function() {
    // Private properties
    var _body;
    var _element;
    var _menuObject;

	// Initialize
	var _init = function() {
		var menuDesktopMode = (KTUtil.attr(_element, 'data-menu-dropdown') === '1' ? 'dropdown' : 'accordion');
        var scroll;

		if (KTUtil.attr(_element, 'data-menu-scroll') === '1') {
			scroll = {
				rememberPosition: false, // remember position on page reload
				height: function() { // calculate available scrollable area height
					var height = parseInt(KTUtil.getViewPort().height);

					if (KTUtil.isBreakpointUp('lg')) {
						height = height - KTLayoutBrand.getHeight();
					}

					height = height - (parseInt(KTUtil.css(_element, 'marginBottom')) + parseInt(KTUtil.css(_element, 'marginTop')));

					return height;
				}
			};
		}

		_menuObject = new KTMenu(_element, {
			// Vertical scroll
			scroll: scroll,

			// Submenu setup
			submenu: {
				desktop: menuDesktopMode,
				tablet: 'accordion', // menu set to accordion in tablet mode
				mobile: 'accordion' // menu set to accordion in mobile mode
			},

			// Accordion setup
			accordion: {
				expandAll: false // allow having multiple expanded accordions in the menu
			}
		});
	}

    var _initHover = function() {
        // Handle Minimized Aside Hover
		if (KTUtil.hasClass(_body, 'aside-fixed') && KTUtil.hasClass(_body, 'aside-minimize-hoverable')) {
			var insideTm;
			var outsideTm;

            // Handle Aside Hover Mode
			KTUtil.addEvent(_element, 'mouseenter', function(e) {
				e.preventDefault();

				if (KTUtil.isBreakpointUp('lg') === false) {
					return;
				}

				if (outsideTm) {
					clearTimeout(outsideTm);
					outsideTm = null;
				}

                if (insideTm) {
					clearTimeout(insideTm);
					insideTm = null;
				}

				insideTm = setTimeout(function() {
					if (KTUtil.hasClass(_body, 'aside-minimize') && KTUtil.isBreakpointUp('lg')) {
						// Hover class
						KTUtil.addClass(_body, 'aside-minimize-hover');

						KTLayoutAsideMenu.getMenu().scrollUpdate();
						KTLayoutAsideMenu.getMenu().scrollTop();
					}
				}, 50);
			});

			KTUtil.addEvent(KTLayoutAside.getElement(), 'mouseleave', function(e) {
				e.preventDefault();

				if (KTUtil.isBreakpointUp('lg') === false) {
					return;
				}

				if (insideTm) {
					clearTimeout(insideTm);
					insideTm = null;
				}

                if (outsideTm) {
					clearTimeout(outsideTm);
					outsideTm = null;
				}

				outsideTm = setTimeout(function() {
				    if (KTUtil.hasClass(_body, 'aside-minimize-hover') && KTUtil.isBreakpointUp('lg')) {
					    KTUtil.removeClass(_body, 'aside-minimize-hover');

						// Hover class
                        KTLayoutAsideMenu.getMenu().scrollUpdate();
						KTLayoutAsideMenu.getMenu().scrollTop();
					}
				}, 100);
			});
		}
	}

	var _initCustomMenu = function () {
		// Click Menu
		$(document).on('click', '.custom-menu .nav > .has-sub > a', function() {
			var target = $(this).next('.sub-menu');
			var otherMenu = $('.custom-menu .nav > li.has-sub > .sub-menu').not(target);
			
			otherMenu.closest('li').addClass('closing')
			otherMenu.slideUp(250, function() {
				otherMenu.closest('li').addClass('closed').removeClass('expand closing');
			});

			if (target.is(':visible')) {
				target.closest('li').addClass('closing').removeClass('expand');
			} else {
				target.closest('li').addClass('expanding').removeClass('closed');
			}
			target.slideToggle(250, function() {
				var targetLi = $(this).closest('li');
				if (!target.is(':visible')) {
					targetLi.addClass('closed').removeClass('expand expanding closing');
				} else {
					targetLi.addClass('expand').removeClass('closed expanding closing');
				}
			});
		});

		// Click Sub Menu
		$(document).on('click', '.custom-menu .nav > .has-sub .sub-menu li.has-sub > a', function() {
			var target = $(this).next('.sub-menu');
			if (target.is(':visible')) {
				target.closest('li').addClass('closing').removeClass('expand');
			} else {
				target.closest('li').addClass('expanding').removeClass('closed');
			}
			target.slideToggle(250, function() {
				var targetLi = $(this).closest('li');
				if (!target.is(':visible')) {
					targetLi.addClass('closed').removeClass('expand expanding closing');
				} else {
					targetLi.addClass('expand').removeClass('closed expanding closing');
				}
			});
		});
	}

    // Public methods
	return {
		init: function(id) {
            _body = KTUtil.getBody();
            _element = KTUtil.getById(id);

            if (!_element) {
                return;
            }

            // Initialize menu
            _init();
            _initHover();
            _initCustomMenu();
		},

		getElement: function() {
			return _element;
		},

        getMenu: function() {
			return _menuObject;
		},

        pauseDropdownHover: function(time) {
			if (_menuObject) {
				_menuObject.pauseDropdownHover(time);
			}
		},

		closeMobileOffcanvas: function() {
			if (_menuObject && KTUtil.isMobileDevice()) {
				_menuObject.hide();
			}
		}
	};
}();

// Webpack support
if (typeof module !== 'undefined') {
	module.exports = KTLayoutAsideMenu;
}
