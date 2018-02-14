
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
import { store } from './components/content/editor/vuex/store'
import App from './components/content/editor/App.vue'
import { InputNumber, Button, Select, Option, Scrollbar, Carousel, CarouselItem } from 'element-ui'

import 'element-ui/packages/theme-default/lib/date-picker.css'
import DatePicker from 'components/ui/element-ui-extended/date-picker'

import lang from 'element-ui/lib/locale/lang/en'
import locale from 'element-ui/lib/locale'

locale.use(lang)

import VeeValidate from 'vee-validate'

Vue.use(VeeValidate)
Vue.use(InputNumber)
Vue.use(Select)
Vue.use(Option)
Vue.use(Carousel)
Vue.use(CarouselItem)
Vue.component(DatePicker.name, DatePicker)
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
    store, // inject store to all children
    el: '#app',
    components: { App }
});
