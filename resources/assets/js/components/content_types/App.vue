<script>
    import Vue from 'vue'
    import BootstrapColorPicker from 'bootstrap-colorpicker'

    Vue.component('content-type-editor', require('components/content_types/ContentTypeEditor.vue'))
    Vue.component('content-type-options', require('components/content_types/ContentTypeOptions.vue'))
    Vue.component('color-picker', require('components/ui/ColorPicker.vue'))
    Vue.component('px', require('components/ui/Px.vue'))

    export default {
        components: {
            ContentTypeEditor: 'content-type-editor',
            ContentTypeOptions: 'content-type-options'
        }
    }
</script>
