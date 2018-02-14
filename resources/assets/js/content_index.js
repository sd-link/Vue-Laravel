/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vue from 'vue'
import App from './components/content/index/App.vue'
import { Select, Option } from 'element-ui'

import lang from 'element-ui/lib/locale/lang/en'
import locale from 'element-ui/lib/locale'

locale.use(lang)

Object.defineProperty(Vue.prototype,"$bus",{
    get: function() {
        return this.$root.bus;
    }
})

// Vue.component('settings', require('./components/Settings.vue'));
const app = new Vue({
    data: {
      bus: new Vue({}),
    },
    el: '#app',
    components: { App }
});
