<template>
    <div class="layer">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" v-bind:style="layerStyles">
            <component v-for="block in subItems"
              :style="layerFlex"
              v-bind:is="block.type"
              :uniqueId="block.uniqueId"
              v-bind="block.settings"
              :editing-slide="editingSlide"
              :container-preview="containerPreview"
              :show-headers="showHeaders"
              :show-content="showContent"
              v-on:remove="removeBlock(block.uniqueId)"
              :key="block.uniqueId">
            </component>
        </div>
    </div>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'
    import { getComponentByName, processSettingsConfig } from 'utils/helpers.js'
    import Modal from 'components/ui/Modal.vue'

    export default {
        components: {
            Modal
        },
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Layer'},
            display: {type: String, default: 'flex'},
            flexDirection: {type: String, default: 'column'},
            justifyContent: {type: String, default: 'center'},
            alignItems: {type: String, default: 'center'},
            paddingResponsive: {type: Boolean, default: false},
            padding: {type: String, default: '0px'},
            paddingTabletPortrait: {type: String, default: '0px'},
            paddingTabletLandscape: {type: String, default: '0px'},
            paddingMobilePortrait: {type: String, default: '0px'},
            paddingMobileLandscape: {type: String, default: '0px'},
        },
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
                    height: 380
                },
                state: {
                  modalData: {
                    top: 0,
                    left: 0,
                    width: null,
                    height: null
                  }
                },
                showModal: false,
            }
        },
        props: {
            editingSlide: {type: Boolean, default: false},
        },
        computed: {
            layerStyles: function() {
                let styles = ""
                styles = styles + 'display: ' +this.settings.display+';'
                styles = styles + 'flex-direction: ' +this.settings.flexDirection+';'
                styles = styles + 'justify-content: ' +this.settings.justifyContent+';'
                styles = styles + 'align-items: ' +this.settings.alignItems+';'
                styles = styles + 'padding: '+this.settings.padding+';'

                return styles
            },
            layerFlex: function() {
                if(this.subItems.length > 1 && this.settings.flexDirection == 'row')
                    return 'flex: 1;'
            },
            modalTitle: function () {
                return this.settings.blockTitle + ' Settings'
            },
            settings () {
                return this.$store.getters.itemSettings(this.uniqueId)
            },
            subItems: {
                get() {
                    return this.$store.getters.items(this.uniqueId)
                },
                set(object) {
                    this.updateItemsList({list: _.map(object, 'uniqueId'), id: this.uniqueId})
                }
            },
            content () {
                return this.$store.getters.itemContent(this.uniqueId)
            },
        },
        watch: {
            settings: {
                handler(newVal) {
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            },
            image: {
                handler(val) {
                    this.updateItemContent({content: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
        },
        methods: {
            ...mapActions([
                'addItem',
                'removeItem',
                'updateItemsList',
                'updateItemContent',
                'updateItemSettings',
            ]),
            responsivePadding() {
                this.settings.paddingResponsive =! this.settings.paddingResponsive

                if(this.settings.paddingResponsive) {
                    this.settings.paddingTablet = this.settings.padding
                    this.settings.paddingMobile = this.settings.padding
                }
            },
            editSlide(val) {
                this.editingSlide = val
                this.$emit('edit-slide', this.editingSlide)
            },
            openMediaModal() {
                var mode = 'insertImage'
                var params = new Object
                // params.imageId = this.imageId
                params.id = this._uid

                params.cb = (image) => {
                    this.image = image.original
                }

                this.$bus.$emit('open-media-modal', mode, params)
            },
            addBlock(compName = undefined) {
                let comp = getComponentByName(compName)
                if (!comp) {
                    alert('Wrong component\'s name')
                    return false
                }

                let customSettings = processSettingsConfig(compName)

                this.addItem({
                    type: compName,
                    title: compName,
                    title: comp.options.customSettings.blockTitle.default,
                    parentId: this.uniqueId,
                    settingsConfig: customSettings,
                    settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                })
            },
            removeBlock() {
                this.$emit('remove', this.uniqueId)
            },
            closeModalAction() {
                // this.saveData()
                this.showModal = false
            },
            cancelAction() {
                this.showModal = false
            },
            blockSettings() {
                this.showModal = true
            },
            processContent(content) {
                this.inputContent = new Object()
            }
        },
        mounted : function() {
            console.log('slide layer mounted')
            this.image = this.content
        }
    }
</script>

<style scoped lang="less">
    .header {
        opacity: 1
    }

    .fa-cog {
        margin: 0;
    }

    .button {
        border: 1px solid rgba(173, 216, 230, 0.5);
        padding: 8px 13px;
        margin: 0;
        cursor: pointer;
        border-radius: 50%;
        background-color: rgba(43, 92, 147, 0.65);
        color: #b8c7ce;
        position: absolute;
        top: 10px;
        z-index: 10;
        text-align: center;
        font-size: 15px;

        &:hover {
            background-color: rgba(43, 92, 147, 0.85);
            border: 1px solid rgba(173, 216, 230, 0.8);
        }

    }

    .edit, .done {
        right: 15px;
    }

    .settings {
        right: 65px;
    }

    .set-image {
        right: 117px;
    }

    .slide {
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .add-buttons-container {
        bottom: 20px;
        left: 0;
        right: 0;
        margin-left: auto;
        margin-right: auto;
        position: absolute;
        text-align: center;
    }

    .add-text {
        border: 1px solid rgba(173, 216, 230, 0.5);
        padding: 5px 10px;
        margin: 0;
        cursor: pointer;
        border-radius: 0%;
        background-color: rgba(43, 92, 147, 0.65);
        color: #b8c7ce;
        z-index: 10;
        pointer-events: all;

        &:hover {
            background-color: rgba(43, 92, 147, 0.85);
            border: 1px solid rgba(173, 216, 230, 0.8);
        }
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s
    }

    .fade-enter, .fade-leave-to {
        opacity: 0
    }
</style>
