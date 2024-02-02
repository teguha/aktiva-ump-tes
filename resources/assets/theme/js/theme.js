// Keenthemes' KTAppSettings
window.KTAppSettings = require('./config.js');

// Keenthemes' plugins
window.KTUtil = require('./components/util.js');
window.KTApp = require('./components/app.js');
window.KTCard = require('./components/card.js');
window.KTCookie = require('./components/cookie.js');
window.KTDialog = require('./components/dialog.js');
window.KTHeader = require('./components/header.js');
window.KTImageInput = require('./components/image-input.js');
window.KTMenu = require('./components/menu.js');
window.KTOffcanvas = require('./components/offcanvas.js');
window.KTScrolltop = require('./components/scrolltop.js');
window.KTToggle = require('./components/toggle.js');
window.KTWizard = require('./components/wizard.js');
require('./components/datatable/core.datatable.js');
require('./components/datatable/datatable.checkbox.js');
require('./components/datatable/datatable.rtl.js');

// Metronic layout base js
window.KTLayoutAside = require('./layout/base/aside.js');
window.KTLayoutAsideMenu = require('./layout/base/aside-menu.js');
window.KTLayoutAsideToggle = require('./layout/base/aside-toggle.js');
window.KTLayoutBrand = require('./layout/base/brand.js');
window.KTLayoutContent = require('./layout/base/content.js');
window.KTLayoutFooter = require('./layout/base/footer.js');
window.KTLayoutHeader = require('./layout/base/header.js');
window.KTLayoutHeaderMenu = require('./layout/base/header-menu.js');
window.KTLayoutHeaderTopbar = require('./layout/base/header-topbar.js');
window.KTLayoutStickyCard = require('./layout/base/sticky-card.js');
window.KTLayoutStretchedCard = require('./layout/base/stretched-card.js');
window.KTLayoutSubheader = require('./layout/base/subheader.js');

// Metronic layout extended js
window.KTLayoutChat = require('./layout/extended/chat.js');
window.KTLayoutDemoPanel = require('./layout/extended/demo-panel.js');
window.KTLayoutExamples = require('./layout/extended/examples.js');
window.KTLayoutQuickActions = require('./layout/extended/quick-actions.js');
window.KTLayoutQuickCartPanel = require('./layout/extended/quick-cart.js');
window.KTLayoutQuickNotifications = require('./layout/extended/quick-notifications.js');
window.KTLayoutQuickPanel = require('./layout/extended/quick-panel.js');
window.KTLayoutQuickSearch = require('./layout/extended/quick-search.js');
window.KTLayoutQuickUser = require('./layout/extended/quick-user.js');
window.KTLayoutScrolltop = require('./layout/extended/scrolltop.js');
window.KTLayoutSearch = window.KTLayoutSearchOffcanvas = require('./layout/extended/search.js');

require('./layout/initialize.js');
