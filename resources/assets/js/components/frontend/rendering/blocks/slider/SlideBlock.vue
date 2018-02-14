<template>
    <div class="slide" :style="slideStyles">
        <div :class="[container, containerResponsive]">
            <div :class="layers">
                <component v-for="layer in subBlocks"
                  v-bind:is="layer.type"
                  v-bind="settings(layer.settings)"
                  :subBlocks="layer.subBlocks"
                  :key="layer.unique_id">
                </component>
            </div>
        </div>
    </div>
</template>

<script>
    import resize from 'vue-resize-directive'
    import { processSettings } from 'utils/helpers.js'
    export default {
        directives: {
            resize,
        },
        data: function data() {
            return {
                image: null,
                currentIndex: null,
                imageHeight: 0,
            }
        },
        props: {
            slideId: {type: Number, default: ''},
            visible: {type: Boolean, default: false},
            content: {type: String, default: ''},
            blockTitle: {type: String, default: 'Slide'},
            subBlocks: {type: Array, default: null},
            contentRenderStyle: {type: String, default: 'boxed'},
            deviceType: {type: String, default: 'mobile'},
        },
        computed: {
            containerResponsive: function() {
                if(this.deviceType == 'mobile' || this.deviceType == 'tablet')
                    return 'container-responsive'
            },
            container: function() {
                if(this.contentRenderStyle == 'boxed')
                    return 'container'
                else
                    return 'fluid'
            },
            layers: function() {
                if(this.contentRenderStyle == 'boxed')
                    return 'layers-boxed layers'
                else
                    return 'layers-fluid layers'
            },
            slideStyles: function() {
                let styles = ""
                styles = styles + 'background-image: url("' +this.image+'");'
                styles = styles + 'background-size: cover;'
                styles = styles + 'background-position: center;'

                return styles
            }
        },
        methods: {
            onResize(el) {
                console.log('onResize', el.clientHeight)
            },
            settings(settings) {
                return processSettings(settings)
            },
            calculateHeight() {
                this.imageHeight = this.$refs.image.clientHeight;
            }
        },
        mounted : function() {
            this.image = this.content
        }
    }
</script>

<style scoped lang="less">
    .container {
        position: relative;
        height: 100%;
    }

    .container-responsive {
        width: 100%;
    }

    .slide {
        position: absolute;
        overflow: hidden;
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 150px;
        z-index: 10;
    }

    .layers-fluid {
        position: absolute;
    }

    .layers-boxed {
        position: relative;
    }
</style>
