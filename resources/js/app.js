/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));


Vue.component('dashboard_nav',require('./components/dashboard/navbar.vue').default);
Vue.component('dashboard_sidebar',require('./components/dashboard/sidebar.vue').default);

Vue.component("dashboard_users",require('./components/dashboard/users.vue').default);
Vue.component("dashboard_editUser",require('./components/dashboard/users/editUser.vue').default);


Vue.component("dashboard_medicines",require('./components/dashboard/medicines.vue').default);
Vue.component("dashboard_batches",require('./components/dashboard/batches.vue').default);
Vue.component("dashboard_sales",require('./components/dashboard/sales.vue').default);


Vue.component("dashboard_suppliers",require('./components/dashboard/suppliers.vue').default);
Vue.component("dashboard_purchases",require('./components/dashboard/purchases.vue').default);


Vue.component("dashboard_customers",require('./components/dashboard/customers.vue').default);







Vue.component("home",require('./components/home/home.vue').default);
Vue.component("home_navbar",require('./components/home/navbar.vue').default);
Vue.component("home_filter",require('./components/home/filter.vue').default);


Vue.component("geoCoordinates",require('./components/maps/geo-feature.vue').default);


Vue.component("register",require('./components/geo-coord_register.vue').default);




/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data:{
        
    },
    mounted: function() {
        $('[data-tooltip="tooltip"]').tooltip();
      }
    
});




