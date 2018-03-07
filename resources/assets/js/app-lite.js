
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
//Vue.component('paginate', require('vuejs-paginate'));

import VueInternalization from 'vue-i18n'
import Locales from './vue-i18n-locales.generated.js'
import FormError from './components/shared/FormError.vue'
import BootstrapVue from 'bootstrap-vue'

const VueResource = require('vue-resource');

Vue.use(VueResource)
Vue.use(VueInternalization)
Vue.use(BootstrapVue)

// Language
Vue.config.lang = 'en';

const i18n = new VueInternalization({
    locale: 'en', // set locale
    messages: Locales // set locale messages
});

const app = new Vue({
    el: '#app',
    i18n,
});

