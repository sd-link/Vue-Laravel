<script>
    import Vue from 'vue'
    // import moment from 'moment'

    // Slider
    Vue.component('SliderBlock', require('./blocks/slider/SliderBlock.vue'))
    Vue.component('SlideBlock', require('./blocks/slider/SlideBlock.vue'))
    Vue.component('SlideLayer', require('./blocks/slider/SlideLayer.vue'))
    Vue.component('SlideText', require('./blocks/slider/SlideText.vue'))
    Vue.component('SlideImage', require('./blocks/slider/SlideImage.vue'))

    export default {
        components: {
            SliderBlock: 'slider-block',
        }
    }
</script>
