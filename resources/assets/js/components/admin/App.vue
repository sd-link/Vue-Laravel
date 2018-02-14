<!-- <template>
  <div id="app" class="wrapper" v-cloak>
    <slot></slot>
  </div>
</template> -->

<script>
import Vue from 'vue'
import BootstrapColorPicker from 'bootstrap-colorpicker'

Vue.component('sidebar', require('./interface/Sidebar.vue'))
Vue.component('content-data', require('./interface/ContentData.vue'))
Vue.component('admin-main-menu-item', require('./settings/AdminMenuItem.vue'))
Vue.component('admin-sub-menu-item', require('./settings/AdminMenuItem.vue'))
Vue.component('color-picker', require('components/ui/ColorPicker.vue'))
Vue.component('number-input', require('components/ui/NumberInput.vue'))
Vue.component('modal', require('../ui/Modal.vue'))

// import AdminMainMenuItem from './settings/AdminMenuItem.vue'
// import AdminSubMenuItem from './settings/AdminMenuItem.vue'
// import Modal from './settings/AdminMenuItem.vue'

export default {
    components: {
        Sidebar: 'sidebar',
        ContentData: 'content-data',
        AdminMainMenuItem: 'admin-main-menu-item',
        AdminSubMenuItem: 'admin-sub-menu-item',
        ColorPicker: 'color-picker',
        Modal: 'modal'
  }
}
</script>
