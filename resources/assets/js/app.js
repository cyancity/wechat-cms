
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import http from 'plugins/http'
import VueRouter from 'vue-router'
import store from './vuex'
import VueI18n from 'vue-i18n'
import 'vue-multiselect/dist/vue-multiselect.min.css'

import routes from './routes'
import locales from 'lang'

import App from './App.vue'

window.toastr = require('toastr/build/toastr.min')
window.innerHeight = 800

window.toastr.options = {
    positionClass: "toast-bottom-right",
    showDuration: "300",
    hideDutation: "1000",
    timeOut: "5000",
    extendedTimeOUt: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
}

window.Vue = require('vue');


Vue.use(http)
Vue.use(VueI18n)
Vue.use(VueRouter)

Vue.config.lang = window.Language

const i18n = new VueI18n({
    locale: Vue.config.lang,
    messages: locales
})

Vue.component('vue-table-pagination', require('components/dashboard/TablePagination.vue'))
Vue.component('vue-table', require('component/dashboard/Table.vue'))
Vue.component('vue-form', require('components/dashboard/Form.vue'))

const router = new VueRouter({
    mode: 'history',
    base: __dirname,
    linkActiveClass: 'active',
    routes: routes
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

new Vue({
    router,
    store,
    i18n,
    render: (h) => h(App)
}).$mount('#app')