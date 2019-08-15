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

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data:{
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    }
    
});




