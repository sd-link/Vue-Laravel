<template>
    <div class="container-block">
        <div class="container-block-header">
            <label>{{ settings.blockTitle }}</label>
            <div class="pull-right">
                <i v-if="settings.showPreview" class="fa fa-eye" @click="settings.showPreview=false" aria-hidden="true" title="Hide Preview"></i>
                <i v-if="!settings.showPreview" class="fa fa-eye-slash" @click="settings.showPreview=true" aria-hidden="true" title="Show Preview"></i>
            </div>
        </div>
        <div v-show="showContent" class="container-block-body">
            <div v-bind:style="containerStyles">
                <div style="min-height: 120px;">
                    <draggable
                      v-model="subItems"
                      :options="{group:'sub', handle:'.fa-arrows', chosenClass:'dragging1'}"
                      style="min-height: 30px;">
                      <!-- :transparent-block-header-background="transparentBlockHeaderBackground" -->
                        <component v-for="block in subItems"
                          v-bind:is="block.type"
                          :uniqueId="block.uniqueId"
                          v-bind="block.settings"
                          :container-preview="settings.showPreview"
                          :show-headers="showBlockHeaders"
                          :show-content="showBlockContent"
                          :transparent-input-background="transparentInputBackground"
                          v-on:remove="removeBlock(block.uniqueId)"
                          :key="block.uniqueId">
                        </component>
                    </draggable>
                </div>
            </div>
        </div>

        <div v-show="showContent" class="container-block-footer">
            <button v-on:click="addBlock('TextBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Text</button>
            <button v-on:click="addBlock('InputBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Input</button>
            <button v-on:click="addBlock('HeaderBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Header</button>
            <button v-on:click="addBlock('ImageBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Image</button>
            <button v-on:click="addBlock('ImagesBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Images</button>
            <button v-on:click="addBlock('ColumnsBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Columns</button>
        </div>
    </div>
</template>

<script>
    import GeneralMixin from '../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'
    import draggable from 'vuedraggable'
    import Modal from 'components/ui/Modal.vue'
    import { getComponentByName, processSettingsConfig } from 'utils/helpers.js'

    export default {
        mixins: [GeneralMixin],
        components: {
            Modal,
            draggable
        },
        data () {
            return {
                modalDefaults: {
                    width: 600,
                    height: 600
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
                cssSettingsOpen: false,
                previewSettings: false,
                enablePreviewMode: true,
                showComponentHeaders: false,
                showContent: true,
                transparentInputBackground: true,
                showBlockHeaders: true,
                showBlockContent: true,
                showBlockLabels: false,
            }
        },
        customSettings: {
            blockTitle: {type: String, default: 'Container'},
            containerType: {type: String, default: 'normal'},
            contentRenderStyle: {type: String, default: 'fluid'},
            enableSettingsEditing: {type: Boolean, default: true},
            enablePreviewMode: {type: Boolean, default: true},
            showPreview: {type: Boolean, default: true},

            background: {type: String, default: ''},
            backgroundImage: {type: String, default: ''},
            backgroundStyle: {type: String, default: 'scroll'},
            backgroundSize: {type: String, default: 'cover'},
            backgroundSizeManual: {type: String, default: ''},
            backgroundPosition: {type: String, default: 'initial'},
            backgroundRepeat: {type: String, default: 'repeat'},
            backgroundColor: {type: String, default: 'transparent'},
            paddingResponsive: {type: Boolean, default: false},
            padding: {type: String, default: '0px'},
            paddingTablet: {type: String, default: '0px'},
            paddingMobile: {type: String, default: '0px'},

            marginResponsive: {type: Boolean, default: false},
            margin: {type: String, default: '0px'},
            marginTablet: {type: String, default: '0px'},
            marginMobile: {type: String, default: '0px'},
        },
        computed: {
            showPreview: function() {
                if(this.settings.showPreview && this.settings.enablePreviewMode)
                    return true
                else
                    return false
            },
            containerStyles: function() {
                let styles = ""

                if(this.showPreview) {
                    if(this.settings.backgroundImage) {
                        // styles = styles + 'background: linear-gradient('+this.settings.backgroundColor+','+this.settings.backgroundColor+'), url('+this.settings.backgroundImage+');'
                        styles = styles + 'background-image: url('+this.settings.backgroundImage+');'
                        styles = styles + 'background-attachment: '+this.settings.backgroundStyle+';'
                        styles = styles + 'background-position: '+this.settings.backgroundPosition+';'
                        styles = styles + 'background-repeat: '+this.settings.backgroundRepeat+';'
                        styles = styles + 'box-shadow: inset 0 0 0 2000px '+this.settings.backgroundColor+';'
                        switch (this.settings.backgroundSize) {
                            case 'manual':
                                styles = styles + 'background-size: '+this.settings.backgroundSizeManual+';'
                            break;
                            default:
                                styles = styles + 'background-size: '+this.settings.backgroundSize+';'
                        }
                    }
                    else {
                        styles = styles + 'background: linear-gradient('+this.settings.backgroundColor+','+this.settings.backgroundColor+');'
                    }

                    styles = styles + 'padding: '+this.settings.padding+';'
                    styles = styles + 'margin: '+this.settings.margin+';'
                }
                return styles
            },
            backgroundImage: function() {
                // if(this.settings.backgroundImage) {
                //     console.log('abcd')
                //     return 'linear-gradient('+this.settings.backgroundColor+','+this.settings.backgroundColor+'), url('+this.settings.backgroundImage+')'
                // }
            },
            containerType: function () {
                switch (this.settings.containerType) {
                    case 'normal':
                        return 'Normal'
                    break;
                    case 'wide':
                        return 'Full Width'
                    break;
                }
            },
            settings: {
                get() {
                    return this.$store.getters.itemSettings(this.uniqueId)
                }
            },
            subItems: {
                get() {
                    return this.$store.getters.items(this.uniqueId)
                },
                set(object) {
                    this.updateItemsList({list: _.map(object, 'uniqueId'), id: this.uniqueId})
                }
            }
        },
        watch: {
            showPreview(val, old) {
                if(!val)
                    this.transparentInputBackground = false
                else
                    this.transparentInputBackground = true
            },
            transparentInputBackground(val, old) {
                console.log('transparentInputBackground changed inside header Container')
            },
            showHeaders(val, old) {
                this.showBlockHeaders = val
                console.log('showHeaders: ' + val)
            },
            showContent(val, old) {
                this.showBlockContent = val
                console.log('showContent: ' + val)
            },
            settings: {
                handler(newVal) {
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            }
        },
        methods: {
            ...mapActions([
                'addItem',
                'removeItem',
                'updateItemsList',
                'updateItemSettings',
            ]),
            onEnd(evt) {
            },
            responsivePadding() {
                this.settings.paddingResponsive =! this.settings.paddingResponsive

                if(this.settings.paddingResponsive) {
                    this.settings.paddingTablet = this.settings.padding
                    this.settings.paddingMobile = this.settings.padding
                }
            },
            responsiveMargin() {
                this.settings.marginResponsive =! this.settings.marginResponsive

                if(this.settings.marginResponsive) {
                    this.settings.marginTablet = this.settings.margin
                    this.settings.marginMobile = this.settings.margin
                }
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
            removeContainer() {
                this.$emit('remove')
            },
            removeBlock(blockId) {
                this.removeItem({
                    id: blockId,
                    parentId: this.uniqueId
                })
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
            }
        }
    }
</script>

<style lang="less" scoped>
    label {
        margin-right: 8px;
    }

    .form-sub-group {
        padding: 8px 12px;
        border: 1px solid rgba(0,0,0,0.1);
    }

    .btn-primary {
        background-color: rgba(87, 113, 128, 0.09);
        border-color: rgba(54, 127, 169, 0);
    }

    .btn-primary:hover, .btn-primary:active, .btn-primary.hover {
        background-color: rgba(103, 137, 157, 0.19);
    }

    .btn-primary:hover {
        color: #fff;
        background-color: rgba(40, 96, 144, 0.42);
        border-color: rgba(32, 77, 116, 0.17);
    }

    .container-block-header {
        padding: 2px;
        border: 1px dashed rgba(3, 107, 255, 0.25);
        border-top: 0;
        border-left: 0;
        border-right: 0;
        padding-left: 0;
    }

    .container-block-body {
        border: 1px dashed rgba(3, 107, 255, 0.25);
        border-top: 0;
        border-bottom: 0;
        padding: 5px;
    }

    .container-block-footer {
        border: 1px dashed rgba(3, 107, 255, 0.25);
    }

    .container-block {
        // border: 1px dashed rgba(0,0,0,0.1);
        margin-bottom: 10px;
    }
</style>
