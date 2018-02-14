<script>
    import Vue from 'vue'

    Vue.component('gallery-editor', require('components/media/galleries/editor/GalleryEditor.vue'))
    Vue.component('px', require('components/ui/Px.vue'))

    export default {
        components: {
            GalleryEditor: 'gallery-editor'
        }
    }
</script>
