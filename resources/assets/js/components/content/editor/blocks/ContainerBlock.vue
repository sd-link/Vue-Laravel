<template>
    <div class="container-block">
        <div class="container-block-header">
            <i class="fa fa-arrows" aria-hidden="true" title="Reorder"></i>
            <i class="fa fa-cog" @click="blockSettings()" aria-hidden="true" title="Settings"></i>
            {{ settings.blockTitle }}
            <div class="pull-right">
                <i v-if="settings.showPreview" class="fa fa-eye" @click="settings.showPreview=false" aria-hidden="true" title="Hide Preview"></i>
                <i v-if="!settings.showPreview" class="fa fa-eye-slash" @click="settings.showPreview=true" aria-hidden="true" title="Show Preview"></i>
                <i v-show="showContent" @click="showContent=false" class="fa fa-arrow-circle-o-up" title="Collapse" aria-hidden="true"></i>
                <i v-show="!showContent" @click="showContent=true" class="fa fa-arrow-circle-o-down" title="Expand" aria-hidden="true"></i>
                <i @click="removeContainer" class="fa fa-times" aria-hidden="true" title="Remove"></i>
                <!-- <button v-on:click="" type="button" class="close"><span aria-hidden="true">×</span></button> -->
            </div>
        </div>
        <div v-show="showContent" class="container-block-body">
            <div v-bind:style="containerStyles">
                <draggable
                  v-model="subItems"
                  :options="{group:'sub', handle:'.fa-arrows', chosenClass:'dragging1'}"
                  :style="containerItemsStyles">
                  <!-- :transparent-block-header-background="transparentBlockHeaderBackground" -->
                    <component v-for="block in subItems"
                      :style="{ flex: settings.flexDirection == 'row' ? '1':'' }"
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

        <div v-show="showContent" class="container-block-footer">
            <button v-on:click="addBlock('ContainerBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Container</button>
            <button v-on:click="addBlock('SliderBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Slider</button>
            <button v-on:click="addBlock('TextBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Text</button>
            <button v-on:click="addBlock('InputBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Input</button>
            <button v-on:click="addBlock('HeaderBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Header</button>
            <button v-on:click="addBlock('ImageBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Image</button>
            <button v-on:click="addBlock('ImagesBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Images</button>
            <button v-on:click="addBlock('VideoBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Video</button>
            <button v-on:click="addBlock('ButtonBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Button</button>
            <button v-on:click="addBlock('ColumnsBlock')" type="button" name="button" class="btn btn-primary btn-sm">Add Columns</button>
        </div>

        <modal ref="settingsModal" title="Container Settings"
          v-model="showModal"
          v-bind="state.modalData"
          :draggable="true"
          :defaultWidth="modalDefaults.width"
          :defaultHeight="modalDefaults.height"
        >
            <div class="modal-body" slot="modal-body">
                <div class="form-group">
                    <label>Container Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
                </div>

                <div class="form-group">
                    <label>Container Type</label>
                    <select class="form-control" v-model="settings.containerType">
                        <option value='boxed'>Boxed</option>
                        <option value='fullwidth'>Fullwidth</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Visibility</label>
                    <div class="form-sub-group">
                        <span style="margin-right: 10px;">
                            <input type="checkbox" :id="'visibleDesktop'+uniqueId" v-model="settings.visibleDesktop">
                            <label style="vertical-align: top;" :for="'visibleDesktop'+uniqueId">Desktop</label>
                        </span>
                        <span style="margin-right: 10px;">
                            <input type="checkbox" :id="'visibleTablet'+uniqueId" v-model="settings.visibleTablet">
                            <label style="vertical-align: top;" :for="'visibleTablet'+uniqueId">Tablet</label>
                        </span>
                        <span style="margin-right: 10px;">
                            <input type="checkbox" :id="'visibleMobile'+uniqueId" v-model="settings.visibleMobile">
                            <label style="vertical-align: top;" :for="'visibleMobile'+uniqueId">Mobile</label>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Display</label>
                    <select class="form-control" v-model="settings.display">
                        <option value='block'>Block</option>
                        <option value='flex'>Flex</option>
                    </select>
                </div>

                <div v-if="settings.display =='flex'" class="form-group">
                    <label>Flex Direction</label>
                    <select class="form-control" v-model="settings.flexDirection">
                        <option value='column'>Column</option>
                        <option value='row'>Row</option>
                    </select>
                </div>

                <div v-if="settings.display =='flex' && settings.flexDirection == 'column'" class="form-group">
                    <label>Justify Content</label>
                    <select class="form-control" v-model="settings.justifyContent">
                        <option value='flex-start'>Start</option>
                        <option value='flex-end'>End</option>
                        <option value='center'>Center</option>
                        <option value='space-between'>Space Between</option>
                        <option value='space-around'>Space Around</option>
                        <option value='space-evenly'>Space Evenly</option>
                    </select>
                </div>

                <div v-if="settings.display =='flex'" class="form-group">
                    <label>Align Items</label>
                    <select class="form-control" v-model="settings.alignItems">
                        <option value='flex-start'>Start</option>
                        <option value='center'>Center</option>
                        <option value='flex-end'>End</option>
                    </select>
                </div>

                <div v-if="!settings.heightResponsive" class="form-group">
                    <label>Height</label> <i @click="settings.heightResponsive = !settings.heightResponsive" class="fa fa-desktop" title="Responsive" aria-hidden="true"></i>
                    <px v-model="settings.height"></px>
                </div>

                <div v-if="settings.heightResponsive" class="form-group">
                    <label>Height</label> <i @click="settings.heightResponsive = !settings.heightResponsive" class="fa fa-desktop" title="Responsive" aria-hidden="true"></i>
                    <div v-if="settings.heightResponsive" class="form-sub-group">
                        <div class="form-group">
                            <label>Desktop</label>
                            <px v-model="settings.height"></px>
                        </div>
                        <div style="display: flex;">
                            <div class="form-group" style="flex: 0.5; margin-right: 5px;">
                                <label>Tablet Portrait</label>
                                <px v-model="settings.heightTabletPortrait"></px>
                            </div>
                            <div class="form-group" style="flex: 0.5;">
                                <label>Tablet Landscape</label>
                                <px v-model="settings.heightTabletLandscape"></px>
                            </div>
                        </div>
                        <div style="display: flex;">
                            <div class="form-group" style="flex: 0.5; margin-right: 5px;">
                                <label>Mobile Portrait</label>
                                <px v-model="settings.heightMobilePortrait"></px>
                            </div>
                            <div class="form-group" style="flex: 0.5;">
                                <label>Mobile Landscape</label>
                                <px v-model="settings.heightMobileLandscape"></px>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="!settings.paddingResponsive" class="form-group">
                    <label>Padding</label> <i @click="settings.paddingResponsive = !settings.paddingResponsive" class="fa fa-desktop" title="Responsive" aria-hidden="true"></i>
                    <px v-model="settings.padding"></px>
                </div>

                <div v-if="settings.paddingResponsive" class="form-group">
                    <label>Padding</label> <i @click="settings.paddingResponsive = !settings.paddingResponsive" class="fa fa-desktop" title="Responsive" aria-hidden="true"></i>
                    <div v-if="settings.paddingResponsive" class="form-sub-group">
                        <div class="form-group">
                            <label>Desktop</label>
                            <px v-model="settings.padding"></px>
                        </div>
                        <div style="display: flex;">
                            <div class="form-group" style="flex: 0.5; margin-right: 5px;">
                                <label>Tablet Portrait</label>
                                <px v-model="settings.paddingTabletPortrait"></px>
                            </div>
                            <div class="form-group" style="flex: 0.5;">
                                <label>Tablet Landscape</label>
                                <px v-model="settings.paddingTabletLandscape"></px>
                            </div>
                        </div>
                        <div style="display: flex;">
                            <div class="form-group" style="flex: 0.5; margin-right: 5px;">
                                <label>Mobile Portrait</label>
                                <px v-model="settings.paddingMobilePortrait"></px>
                            </div>
                            <div class="form-group" style="flex: 0.5;">
                                <label>Mobile Landscape</label>
                                <px v-model="settings.paddingMobileLandscape"></px>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="!settings.marginResponsive" class="form-group">
                    <label>Margin</label> <i @click="settings.marginResponsive = !settings.marginResponsive" class="fa fa-desktop" title="Responsive" aria-hidden="true"></i>
                    <px v-model="settings.margin"></px>
                </div>

                <div v-if="settings.marginResponsive" class="form-group">
                    <label>Margin</label> <i @click="settings.marginResponsive = !settings.marginResponsive" class="fa fa-desktop" title="Responsive" aria-hidden="true"></i>
                    <div v-if="settings.marginResponsive" class="form-sub-group">
                        <div class="form-group">
                            <label>Desktop</label>
                            <px v-model="settings.margin"></px>
                        </div>
                        <div style="display: flex;">
                            <div class="form-group" style="flex: 0.5; margin-right: 5px;">
                                <label>Tablet Portrait</label>
                                <px v-model="settings.marginTabletPortrait"></px>
                            </div>
                            <div class="form-group" style="flex: 0.5;">
                                <label>Tablet Landscape</label>
                                <px v-model="settings.marginTabletLandscape"></px>
                            </div>
                        </div>
                        <div style="display: flex;">
                            <div class="form-group" style="flex: 0.5; margin-right: 5px;">
                                <label>Mobile Portrait</label>
                                <px v-model="settings.marginMobilePortrait"></px>
                            </div>
                            <div class="form-group" style="flex: 0.5;">
                                <label>Mobile Landscape</label>
                                <px v-model="settings.marginMobileLandscape"></px>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Background Color</label>
                    <color-picker v-model="settings.backgroundColor"></color-picker>
                </div>

                <div class="form-group">
                    <label>Background Image</label>
                    <input type="text" class="form-control" v-model="settings.backgroundImage">
                </div>

                <div v-if="settings.backgroundImage" class="form-group">
                    <label>Background Style</label>
                    <select class="form-control" v-model="settings.backgroundStyle">
                        <option value="scroll">Scroll</option>
                        <option value="fixed">Fixed</option>
                        <option value="local">Local</option>
                        <option value="initial">Initial</option>
                        <option value="inherit">Inherit</option>
                    </select>
                </div>

                <div v-if="settings.backgroundImage" class="form-group">
                    <label>Background Size</label>
                    <select class="form-control" v-model="settings.backgroundSize">
                        <option value="cover">Cover</option>
                        <option value="contain">Contain</option>
                        <option value="manual">Manual</option>
                        <option value="initial">Initial</option>
                        <option value="inherit">Inherit</option>
                    </select>
                </div>
                <div v-if="settings.backgroundSize=='manual'" class="form-group">
                    <input v-model="settings.backgroundSizeManual"  type="text" class="form-control" placeholder="Set background width and height using % or px">
                </div>

                <div v-if="settings.backgroundImage" class="form-group">
                    <label>Background Position</label>
                    <select class="form-control" v-model="settings.backgroundPosition">
                        <option value="center">Center</option>
                        <option value="left">Left</option>
                        <option value="right">Right</option>
                        <option value="top">Top</option>
                        <option value="bototm">Bottom</option>
                        <option value="initial">Initial</option>
                        <option value="inherit">Inherit</option>
                    </select>
                </div>

                <div v-if="settings.backgroundImage" class="form-group">
                    <label>Background Repeat</label>
                    <select class="form-control" v-model="settings.backgroundRepeat">
                        <option value="no-repeat">No Repeat</option>
                        <option value="repeat">Repeat</option>
                        <option value="repeat-x">Repeat X</option>
                        <option value="repeat-y">Repeat Y</option>
                        <option value="initial">Initial</option>
                        <option value="inherit">Inherit</option>
                    </select>
                </div>

                <div class="form-group">
                    <div @click="previewSettings=!previewSettings" style="cursor: pointer; background: rgba(0,0,0, 0.2); padding: 5px 12px;">
                        <label style="margin: 0;">Preview Settings</label>
                        <span class="pull-right">
                            <i v-if="!previewSettings" class="fa fa-arrow-circle-o-down" title="Show Css Settings" aria-hidden="true"></i>
                            <i v-else class="fa fa-arrow-circle-o-up" title="Hide Css Settings" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div v-if="previewSettings" class="form-sub-group">
                        <div class="form-group">
                            <label>Enable Preview Mode</label>
                            <select class="form-control" v-model="settings.enablePreviewMode">
                                <option v-bind:value='true'>Yes</option>
                                <option v-bind:value='false'>No</option>
                            </select>
                            <small>Show preview of css settings in template and content editors.</small>
                        </div>

                        <div v-if="settings.enablePreviewMode" class="form-group">
                            <label>Show Headers</label>
                            <select class="form-control" v-model="showBlockHeaders">
                                <option v-bind:value='true'>Yes</option>
                                <option v-bind:value='false'>No</option>
                            </select>
                            <small>Show headers of components inside container in preview mode.</small>
                        </div>

                        <!-- <div v-if="!showBlockHeaders" class="form-group">
                            <label>Show labels</label>
                            <select class="form-control" v-model="showBlockLabels">
                                <option v-bind:value='true'>Yes</option>
                                <option v-bind:value='false'>No</option>
                            </select>
                            <small>When headers are hidden, should labels be visible?</small>
                        </div> -->

                        <div v-if="settings.enablePreviewMode" class="form-group">
                            <label>Transparent Input Background</label>
                            <select class="form-control" v-model="transparentInputBackground">
                                <option v-bind:value='true'>Yes</option>
                                <option v-bind:value='false'>No</option>
                            </select>
                            <small>Background of anything that takes in text as input is set transparent.</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" slot="modal-footer">
                <button type="button" class="btn btn-primary" @click="closeModalAction">Close</button>
            </div>
        </modal>
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
            containerType: {type: String, default: 'boxed'},
            enablePreviewMode: {type: Boolean, default: true},
            showPreview: {type: Boolean, default: false},

            visibleDesktop: {type: Boolean, default: true},
            visibleTablet: {type: Boolean, default: true},
            visibleMobile: {type: Boolean, default: true},

            display: {type: String, default: 'block'},
            flexDirection: {type: String, default: 'column'},
            justifyContent: {type: String, default: 'center'},
            alignItems: {type: String, default: 'center'},

            minHeight: {type: String, default: '120px'},
            heightResponsive: {type: Boolean, default: false},
            height: {type: String, default: '400px'},
            heightTabletPortrait: {type: String, default: 'auto'},
            heightTabletLandscape: {type: String, default: 'auto'},
            heightMobilePortrait: {type: String, default: 'auto'},
            heightMobileLandscape: {type: String, default: 'auto'},

            paddingResponsive: {type: Boolean, default: false},
            padding: {type: String, default: '0px 15px'},
            paddingTabletPortrait: {type: String, default: '0px 15px'},
            paddingTabletLandscape: {type: String, default: '0px 15px'},
            paddingMobilePortrait: {type: String, default: '0px 15px'},
            paddingMobileLandscape: {type: String, default: '0px 15px'},

            marginResponsive: {type: Boolean, default: false},
            margin: {type: String, default: '0px auto'},
            marginTabletPortrait: {type: String, default: '0px auto'},
            marginTabletLandscape: {type: String, default: '0px auto'},
            marginMobilePortrait: {type: String, default: '0px auto'},
            marginMobileLandscape: {type: String, default: '0px auto'},

            backgroundImage: {type: String, default: ''},
            backgroundStyle: {type: String, default: 'scroll'},
            backgroundSize: {type: String, default: 'cover'},
            backgroundSizeManual: {type: String, default: '100%'},
            backgroundPosition: {type: String, default: 'initial'},
            backgroundRepeat: {type: String, default: 'repeat'},
            backgroundColor: {type: String, default: 'transparent'},
        },
        computed: {
            showPreview: function() {
                if(this.settings.showPreview && this.settings.enablePreviewMode)
                    return true
                else
                    return false
            },
            containerItemsStyles: function() {
                let styles = ""
                styles = styles + 'min-height: 30px;'
                styles = styles + 'height: 100%;'
                styles = styles + 'display: '+this.settings.display+';'
                if(this.settings.display == 'flex') {
                    styles = styles + 'flex-direction: '+this.settings.flexDirection+';'
                    styles = styles + 'align-items: '+this.settings.alignItems+';'
                    styles = styles + 'justify-content: '+this.settings.justifyContent+';'
                }

                return styles
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
                        // styles = styles + 'background: linear-gradient('+this.settings.backgroundColor+','+this.settings.backgroundColor+');'
                        styles = styles + 'box-shadow: inset 0 0 0 2000px '+this.settings.backgroundColor+';'
                    }

                    styles = styles + 'padding: '+this.settings.padding+';'
                    styles = styles + 'margin: '+this.settings.margin+';'
                    styles = styles + 'minHeight: '+this.settings.minHeight+';'
                    styles = styles + 'height: '+this.settings.height+';'
                    styles = styles + 'overflow: hidden;'
                }
                return styles
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
                // console.log('transparentInputBackground changed inside header Container')
            },
            showHeaders(val, old) {
                this.showBlockHeaders = val
                // console.log('showHeaders: ' + val)
            },
            showContent(val, old) {
                this.showBlockContent = val
                // console.log('showContent: ' + val)
            },
            settings: {
                handler(newVal) {
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            },
            'settings.containerType': {
                handler(val) {
                    if(val == 'fullwidth') {
                        this.settings.padding = '0px'
                        if(this.settings.paddingResponsive) {
                            this.settings.paddingTabletPortrait = '0px'
                            this.settings.paddingTabletLandscape = '0px'
                            this.settings.paddingMobilePortrait = '0px'
                            this.settings.paddingMobileLandscape = '0px'
                        }
                    }
                },
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

            },
            responsiveMargin() {
                this.settings.marginResponsive =! this.settings.marginResponsive
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
        },
        mounted : function() {
            console.log('container mounted')
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
        padding: 10px;
        border: 1px dashed rgba(3, 107, 255, 0.25);
        // background-color: #3c8dbc;
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
