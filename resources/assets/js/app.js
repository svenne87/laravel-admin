
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('profile-form', require('./components/client/profile/ProfileForm.vue'));
Vue.component('profile-details', require('./components/client/profile/ProfileDetails.vue'));
Vue.component('slideout-menu', require('./components/shared/SlideoutMenu.vue'));
//Vue.component('paginate', require('vuejs-paginate'));

import VueInternalization from 'vue-i18n'
import Locales from './vue-i18n-locales.generated.js'
import FormError from './components/shared/FormError.vue'
import BootstrapVue from 'bootstrap-vue'
import VueRouter from 'vue-router'

const VueResource = require('vue-resource');
const VueProgressBar = require('vue-progressbar');
const options = {
    color: '#3cba54',
    failedColor: '#db3236',
    thickness: '2px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
}
const VueResourceProgressBarInterceptor = require('vue-resource-progressbar-interceptor');

Vue.use(VueResource)
Vue.use(VueRouter)
Vue.use(VueProgressBar, options)
Vue.use(VueResourceProgressBarInterceptor)
Vue.use(VueInternalization)
Vue.use(BootstrapVue)

// Language
Vue.config.lang = 'en';

console.log(permissions);
const i18n = new VueInternalization({
    locale: 'en', // set locale
    messages: Locales // set locale messages
});

 // Intercept global error
axios.interceptors.response.use((response) => {
        return response;
    }, 
    function (error) {
        if (error.response.status === 401) { 
            // store.dispatch('authfalse') 
            console.log("Logged out");
        } else if (error.response.status === 403) {
            console.log("Unauthorized");
        }
        return Promise.reject(error);
    }
);

// Router 
const routes = [
    { path: '/', name: 'home', component: require('./components/admin/dashboard/Dashboard.vue') },
    { path: '/users', name: 'users', component: require('./components/admin/users/UsersTable.vue') },
    { path: '/users/:id', name: 'user-edit', component: require('./components/admin/users/UserForm.vue') },
    { path: '/users/create', name: 'user-create', component: require('./components/admin/users/UserForm.vue') },
    { path: '/roles', name: 'roles', component: require('./components/admin/roles/RolesTable.vue') },
    { path: '/roles/:id', name: 'role-edit', component: require('./components/admin/roles/RoleForm.vue') },
    { path: '/roles/create', name: 'role-create', component: require('./components/admin/roles/RoleForm.vue') },
    { path: '/pages', name: 'pages', component: require('./components/admin/pages/PagesTable.vue') },
    { path: '/pages/:id', name: 'page-edit', component: require('./components/admin/pages/PageForm.vue') },
    { path: '/pages/create', name: 'page-create', component: require('./components/admin/pages/PageForm.vue') },
];

const router = new VueRouter({
    base: '/admin-cp/',
    routes: routes,
});

const app = new Vue({
    el: '#app',
    i18n,
    router,
    data: {
        permissions : permissions
    },
    mounted () {
        this.$Progress.finish()
    },
    created () {
        this.$Progress.start()
        //  hook the progress bar to start before we move router-view
        this.$router.beforeEach((to, from, next) => {
            
            console.log(this.permissions)

            //  start the progress bar
            this.$Progress.start()
            //  continue to next page
            next()
        })
        //  hook the progress bar to finish after we've finished moving router-view
        this.$router.afterEach((to, from) => {
            //  finish the progress bar
            this.$Progress.finish()
        })
    }
});

