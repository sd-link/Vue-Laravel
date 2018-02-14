<template>
    <div class="slider-pro">
        <div class="sp-slides">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    // Import this component
    import SliderPro from 'slider-pro'

    // Import editor css
    import 'slider-pro/dist/css/slider-pro.min.css'

    export default {
        props: {
            width: {type: String, default: '100%'},
            height: {type: String, default: '600'},
            arrows: {type: Boolean, default: true},
            buttons: {type: Boolean, default: false},
            waitForLayers: {type: Boolean, default: true},
            fade: {type: Boolean, default: true},
            autoplay: {type: Boolean, default: false},
            autoScaleLayers: {type: Boolean, default: false},
            imageScaleMode: {type: String, default: 'cover'},
            loop: {type: Boolean, default: true}
        },
        data() {
            return {
                color: this.value
            }
        },
        mounted: function() {
            console.log('pro slider mounted')
            // Store DOM
            console.log('hmmm')
            console.log(this.$el)
            this.el = jQuery(this.$el)
            // console.log(this.el)
            this.el.sliderPro({
            	width: this.width,
            	height: this.height,
            	arrows: this.arrows,
            	buttons: this.buttons,
            	waitForLayers: this.waitForLayers,
            	fade: this.fade,
            	autoplay: this.autoplay,
            	autoScaleLayers: this.autoScaleLayers,
                imageScaleMode: this.imageScaleMode,
                loop: this.loop,
            })

        },
        beforeDestroy: function() {
            // $(this.$el).colorpicker('hide').colorpicker('destroy')
        }
    }
</script>
