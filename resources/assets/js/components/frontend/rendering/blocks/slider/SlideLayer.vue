<template>
    <div class="layer" v-bind:style="layerStyles">
        <component v-for="block in subBlocks"
          :style="layerFlex"
          v-bind:is="block.type"
          :content="block.content"
          v-bind="settings(block.settings)"
          :key="block.unique_id">
        </component>
    </div>
</template>

<script>
    import { processSettings } from 'utils/helpers.js'
    export default {
        data: function data() {
            return {
            }
        },
        props: {
            blockTitle: {type: String, default: 'SlideLayer'},
            display: {type: String, default: 'flex'},
            flexDirection: {type: String, default: 'column'},
            justifyContent: {type: String, default: 'center'},
            alignItems: {type: String, default: 'center'},
            paddingResponsive: {type: Boolean, default: false},
            padding: {type: String, default: '0px'},
            paddingTablet: {type: String, default: '0px'},
            paddingMobile: {type: String, default: '0px'},
            subBlocks: {type: Array, default: null},
        },
        computed: {
            layerStyles: function() {
                let styles = ""
                styles = styles + 'display: ' +this.display+';'
                styles = styles + 'flex-direction: ' +this.flexDirection+';'
                styles = styles + 'justify-content: ' +this.justifyContent+';'
                styles = styles + 'align-items: ' +this.alignItems+';'
                styles = styles + 'padding: '+this.padding+';'

                return styles
            },
            layerFlex: function() {
                if(this.subBlocks.length > 1 && this.flexDirection == 'row')
                    return 'flex: 1;'
            }
        },
        methods: {
            settings(settings) {
                return processSettings(settings)
            },
        },
        mounted : function() {

        }
    }
</script>

<style scoped lang="less">
    .layer {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>
