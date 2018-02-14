
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
import App from './components/content_types/App.vue'
import { InputNumber, Button, Select, Option, DatePicker, Popover } from 'element-ui'
import marked from 'marked'
import lang from 'element-ui/lib/locale/lang/en'
import locale from 'element-ui/lib/locale'

locale.use(lang)

import VeeValidate from 'vee-validate'

Vue.use(VeeValidate)
Vue.use(InputNumber)
Vue.use(Select)
Vue.use(Option)
Vue.use(DatePicker)
Vue.use(Popover)
Vue.use(Button)


// Vue.component('modal-test', require('./components/content/ModalTest.vue'))

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
