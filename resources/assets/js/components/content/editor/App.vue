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
    Vue.component('content-editor', require('./ContentEditor.vue'))
    // Vue.component('content-blocks', require('components/content/ContentBlocks.vue'))
    Vue.component('featured-image', require('components/media/FeaturedImage.vue'))
    Vue.component('media-library', require('components/media/MediaLibrary.vue'))
    Vue.component('color-picker', require('components/ui/ColorPicker.vue'))
    Vue.component('px', require('components/ui/Px.vue'))

    // Template Blocks
    Vue.component('ContainerBlockTemplate', require('components/content/editor/template_blocks/ContainerBlock.vue'))
    Vue.component('TextBlockTemplate', require('components/content/editor/template_blocks/TextBlock.vue'))
    Vue.component('InputBlockTemplate', require('components/content/editor/template_blocks/InputBlock.vue'))
    Vue.component('HeaderBlockTemplate', require('components/content/editor/template_blocks/HeaderBlock.vue'))
    Vue.component('ImageBlockTemplate', require('components/content/editor/template_blocks/ImageBlock.vue'))
    Vue.component('ImagesBlockTemplate', require('components/content/editor/template_blocks/ImagesBlock.vue'))
    Vue.component('ColumnsBlockTemplate', require('components/content/editor/template_blocks/ColumnsBlock.vue'))
    Vue.component('ColumnBlockTemplate', require('components/content/editor/template_blocks/ColumnBlock.vue'))

    // Normal Blocks
    Vue.component('ContainerBlock', require('components/content/editor/blocks/ContainerBlock.vue'))
    Vue.component('TextBlock', require('components/content/editor/blocks/TextBlock.vue'))
    Vue.component('InputBlock', require('components/content/editor/blocks/InputBlock.vue'))
    Vue.component('HeaderBlock', require('components/content/editor/blocks/HeaderBlock.vue'))
    Vue.component('ImageBlock', require('components/content/editor/blocks/ImageBlock.vue'))
    Vue.component('ImagesBlock', require('components/content/editor/blocks/ImagesBlock.vue'))
    Vue.component('ColumnsBlock', require('components/content/editor/blocks/ColumnsBlock.vue'))
    Vue.component('ColumnBlock', require('components/content/editor/blocks/ColumnBlock.vue'))
    Vue.component('VideoBlock', require('components/content/editor/blocks/VideoBlock.vue'))
    Vue.component('ButtonBlock', require('components/content/editor/blocks/ButtonBlock.vue'))

    // Slider
    Vue.component('SliderBlock', require('components/content/editor/blocks/SliderBlock.vue'))
    Vue.component('SlideBlock', require('components/content/editor/blocks/slider/SlideBlock.vue'))
    Vue.component('SlideLayer', require('components/content/editor/blocks/slider/SlideLayer.vue'))
    Vue.component('SlideText', require('components/content/editor/blocks/slider/SlideText.vue'))
    Vue.component('SlideImage', require('components/content/editor/blocks/slider/SlideImage.vue'))

    export default {
        components: {
            ContentEditor: 'content-editor',
            FeaturedImage: 'featured-image',
            MediaLibrary: 'media-library',
        }
    }
</script>
