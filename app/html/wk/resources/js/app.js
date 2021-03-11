/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require("./bootstrap");

window.Vue = require("vue");

import Vue from "vue";
import router from "@/router";
import vuetify from "@/vuetify";
import breadcrumb from "@/breadcrumb";
import store from "@/store";

import MyFrameLayout from "@/Layouts/MyFrameLayout";
import MyProfileComponent from "@/components/MyProfileComponent";
import FooterLayout from "@/Layouts/FooterLayout";
import SnackbarComponent from "@/components/SnackbarComponent";

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('my-frame-component', MyFrameLayout);
Vue.component('footer-component', FooterLayout);
Vue.component('snackbar-component', SnackbarComponent);
Vue.component('my-profile-component', MyProfileComponent);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router: router,
    vuetify: vuetify,
    breadcrumb: breadcrumb,
    store: store,
});
