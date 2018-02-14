<!-- <template>
    <div id="app" class="wrapper">
        <slot></slot>
    </div>
</template> -->

<script>
    import Vue from 'vue'
    import BootstrapColorPicker from 'bootstrap-colorpicker'
    import moment from 'moment'

    // Vue.component('content-buttons', require('./ContentButtons.vue'))
    Vue.component('content-template-editor', require('components/content_templates/ContentTemplateEditor.vue'))
    // Vue.component('featured-image', require('components/media/FeaturedImage.vue'))
    Vue.component('media-library', require('components/media/MediaLibrary.vue'))
    Vue.component('color-picker', require('components/ui/ColorPicker.vue'))
    Vue.component('px', require('components/ui/Px.vue'))
    Vue.use(require('vue-shortkey'))

    Vue.component('ContainerBlockTemplate', require('components/content_templates/blocks/ContainerBlock.vue'))
    Vue.component('TextBlockTemplate', require('components/content_templates/blocks/TextBlock.vue'))
    Vue.component('InputBlockTemplate', require('components/content_templates/blocks/InputBlock.vue'))
    Vue.component('HeaderBlockTemplate', require('components/content_templates/blocks/HeaderBlock.vue'))
    Vue.component('ImageBlockTemplate', require('components/content_templates/blocks/ImageBlock.vue'))
    Vue.component('ImagesBlockTemplate', require('components/content_templates/blocks/ImagesBlock.vue'))
    Vue.component('ColumnsBlockTemplate', require('components/content_templates/blocks/ColumnsBlock.vue'))
    Vue.component('ColumnBlockTemplate', require('components/content_templates/blocks/ColumnBlock.vue'))

    export default {
        components: {
            ContentTemplateEditor: 'content-template-editor',
            MediaLibrary: 'media-library',
        }
    }
</script>
