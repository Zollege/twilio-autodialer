
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/*
  NPM Packages
 */


/*
  Light Bootstrap Dashboard
 */
require('./lbd/jquery-ui.min.js');
require('./lbd/jquery.validate.min.js');
require('./lbd/bootstrap-selectpicker.js');
require('./lbd/bootstrap-checkbox-radio-switch-tags.js');
require('./lbd/chartist.min.js');
require('./lbd/bootstrap-notify.js');
require('./lbd/sweetalert2.js');
require('./lbd/jquery-jvectormap.js');
require('./lbd/jquery.bootstrap.wizard.min.js');
require('./lbd/bootstrap-table.js');
require('./lbd/light-bootstrap-dashboard.js');
require('./lbd/bootstrap-show-password.min');

// NPM Packages
Vue.component('vuetable', require('vuetable-2/src/components/Vuetable'));
Vue.component('vuetable-pagination', require('vuetable-2/src/components/VuetablePagination'));
Vue.component('vuetable-pagination-info', require('vuetable-2/src/components/VuetablePaginationInfo'));

// Welcome Screen
Vue.component('Blink', require('vue-blink'));
Vue.component('WelcomeScreen', require('./components/WelcomeScreen.vue'));
Vue.component('BulkProcessTable', require('./components/BulkProcessTable.vue'));

// Vuetable-2 Filter Bar
Vue.component('vue-table-filter-bar', require('./components/VueTableFilterBar.vue'));

// Users Pages
Vue.component('UsersTable', require('./components/Users/UsersTable.vue'));
Vue.component('UsersTableActions', require('./components/Users/UsersTableActions.vue'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data: {
        selected: 'voice'
    }
});

/*
 UC Insight JS
 */
require('./loginForm');
require('./twilioSecret');
require('./fbSdk');
require('./dropzone');
require('./jquery-ujs');
require('./searchable-select');
require('./bulk-dialer');
