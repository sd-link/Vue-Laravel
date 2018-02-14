<template>
    <div>
        <div v-show="showHeaders" class="content-block-header" style="padding: 2px; padding-left: 10px;">
            <i class="fa fa-cog" @click="blockSettings()" style="cursor: pointer; font-size: 16px; margin-right: 8px;" aria-hidden="true"></i>
            {{ settings.blockTitle }}
        </div>
        <div class="column-block-body">
            <div v-bind:style="columnStyles">
                <draggable
                    @end="onEnd"
                    v-model="subItems"
                    style="min-height: 90px;"
                    :options="{group:'in-column-block', handle:'.fa-arrows', chosenClass:'dragging1'}">
                    <component v-for="block in subItems"
                      v-bind:is="block.type"
                      :uniqueId="block.uniqueId"
                      v-bind="block.settings"
                      :container-preview="containerPreview"
                      :show-headers="showHeaders"
                      :show-content="showContent"
                      :transparent-input-background="transparentInputBackground"
                      v-on:remove="removeBlock(block.uniqueId)"
                      :key="block.uniqueId">
                    </component>
                </draggable>
                <div class="text-center" style="padding: 7px;">
                    <button @click="openBlocksModal" type="button" name="button" class="btn btn-primary btn-sm" style="padding: 2px 15px;">Add</button>
                </div>
            </div>
        </div>

        <modal ref="settingsModal" :title="modalTitle"
          v-model="showModal"
          v-bind="state.modalData"
          :draggable="true"
          :defaultWidth="modalDefaults.width"
          :defaultHeight="modalDefaults.height">
            <div class="modal-body" slot="modal-body">
                <!-- <div class="form-group">
                    <label>Block Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
                </div> -->

                <div class="form-group">
                    <label>Column Class</label>
                    <input type="text" class="form-control" v-model="settings.columnClass">
                    <small>Add your own class based customization to this column.</small>
                </div>

                <div class="form-group">
                    <div @click="cssSettingsOpen=!cssSettingsOpen" style="cursor: pointer; background: rgba(0,0,0, 0.2); padding: 5px 12px;">
                        <label style="margin: 0;">Css Settings</label>
                        <span class="pull-right">
                            <i v-if="!cssSettingsOpen" class="fa fa-arrow-circle-o-down" title="Show Css Settings" aria-hidden="true"></i>
                            <i v-else class="fa fa-arrow-circle-o-up" title="Hide Css Settings" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div v-if="cssSettingsOpen" class="form-sub-group">
                        <!-- <div class="form-group">
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
                        </div> -->

                        <div v-if="!settings.paddingResponsive" class="form-group">
                            <label>Padding</label> <i @click="responsivePadding()" class="fa fa-desktop" title="Responsive" aria-hidden="true"></i>
                            <px v-model="settings.padding"></px>
                        </div>

                        <div v-if="settings.paddingResponsive" class="form-group">
                            <label>Padding</label> <i @click="responsivePadding()" class="fa fa-desktop" title="Responsive" aria-hidden="true"></i>
                            <div v-if="settings.paddingResponsive" class="form-sub-group">
                                <div class="form-group">
                                    <label>Desktop</label>
                                    <px v-model="settings.padding"></px>
                                </div>
                                <div class="form-group">
                                    <label>Tablet</label>
                                    <px v-model="settings.paddingTablet"></px>
                                </div>
                                <div class="form-group">
                                    <label>Mobile</label>
                                    <px v-model="settings.paddingMobile"></px>
                                </div>
                            </div>
                        </div>

                        <div v-if="!settings.marginResponsive" class="form-group">
                            <label>Margin</label> <i @click="responsiveMargin()" class="fa fa-desktop" title="Responsive" aria-hidden="true"></i>
                            <px v-model="settings.margin"></px>
                        </div>

                        <div v-if="settings.marginResponsive" class="form-group">
                            <label>Margin</label> <i @click="responsiveMargin()" class="fa fa-desktop" title="Responsive" aria-hidden="true"></i>
                            <div v-if="settings.marginResponsive" class="form-sub-group">
                                <div class="form-group">
                                    <label>Desktop</label>
                                    <px v-model="settings.margin"></px>
                                </div>
                                <div class="form-group">
                                    <label>Tablet</label>
                                    <px v-model="settings.marginTablet"></px>
                                </div>
                                <div class="form-group">
                                    <label>Mobile</label>
                                    <px v-model="settings.marginMobile"></px>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Columns Spacing</label>
                            <px v-model="settings.columnSpacing"></px>
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
                    </div>
                </div>

            </div>
            <div class="modal-footer" slot="modal-footer">
                <button type="button" class="btn btn-primary" @click="closeAction">Close</button>
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
        customSettings: {
            blockTitle: {type: String, default: 'Column'},
            columnClass: {type: String, default: ''},
            width: {type: String, default: '50%'},

            visibleDesktop: {type: Boolean, default: true},
            visibleTablet: {type: Boolean, default: true},
            visibleMobile: {type: Boolean, default: true},

            paddingResponsive: {type: Boolean, default: false},
            padding: {type: String, default: '0px'},
            paddingTablet: {type: String, default: '0px'},
            paddingMobile: {type: String, default: '0px'},

            marginResponsive: {type: Boolean, default: false},
            margin: {type: String, default: '0px'},
            marginTablet: {type: String, default: '0px'},
            marginMobile: {type: String, default: '0px'},

            backgroundImage: {type: String, default: ''},
            backgroundStyle: {type: String, default: 'scroll'},
            backgroundSize: {type: String, default: 'cover'},
            backgroundSizeManual: {type: String, default: ''},
            backgroundPosition: {type: String, default: 'center'},
            backgroundRepeat: {type: String, default: 'repeat'},
            backgroundColor: {type: String, default: 'transparent'},
        },
        components: {
            Modal,
            draggable,
        },
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
                    height: 500
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
                showBlockHeaders: true,
                showBlockContent: true,
                showPreview: true,
                cssSettingsOpen: true,
            }
        },
        props: {
            transparentInputBackground: {type: Boolean, default: false},
            width: {type: String, default: '100%'},
        },
        computed: {
            modalTitle: function () {
                return this.settings.blockTitle + ' Settings'
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
            },
            columnStyles: function() {
                let styles = ""
                if(this.containerPreview) {
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

                    // styles = styles + 'color: '+this.settings.fontColor+';'
                    // styles = styles + 'font-size: '+this.settings.fontSize+';'
                }
                return styles
            },
            columnWidth: function() {
                return this.width
            }
        },
        watch: {
            settings: {
                handler(newVal) {
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            },
            columnWidth(val, old) {
                this.settings.width = val
            },
        },
        methods: {
            ...mapActions([
                'addItem',
                'removeItem',
                'updateItemsList',
                'updateItemSettings',
            ]),
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
                    parentId: this.uniqueId,
                    settingsConfig: customSettings,
                    settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                })
            },
            onEnd(evt) {
                // for (var i = 0; i < this.blockList.length; i++) {
                //     this.blockList[i].order = (i+1)
                // }
            },
            openBlocksModal() {
                let params = new Object
                params.mode = 'template'
                console.log('openBlocksModal')
                params.cb = (block) => {
                    console.log('callback: ' + block)
                    this.addBlock(block)
                }

                this.$bus.$emit('open-blocks-modal', params)
            },
            closeAction() {
                // this.saveData()
                this.showModal = false
            },
            blockSettings() {
                this.showModal = true
            },
            removeBlock(blockId) {
                this.removeItem({
                    id: blockId,
                    parentId: this.uniqueId
                })
            },
        },
        beforeDestroy: function() {
        },
        mounted : function() {
            this.settings.width = this.width
        }
    }
</script>

<style lang="less" scoped>
    .column-block-body {
        padding: 1px;
        border: 1px dashed rgba(0,0,0, 0.1);
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
</style>
