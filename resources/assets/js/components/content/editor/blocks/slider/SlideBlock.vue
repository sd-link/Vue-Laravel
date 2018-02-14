<template>
    <div class="slide" :style="slideStyles">
        <div :class="container">
            <div class="layers">
                <component v-for="layer in layers"
                  v-bind:is="layer.type"
                  :uniqueId="layer.uniqueId"
                  v-bind="layer.settings"
                  :editing-slide="editingSlide"
                  :container-preview="containerPreview"
                  :show-headers="showHeaders"
                  :show-content="showContent"
                  v-on:remove="removeBlock(layer.uniqueId)"
                  :key="layer.uniqueId">
                </component>
            </div>
        </div>

        <modal ref="slideModal" title="Slide Settings"
          v-model="showSlideModal"
          v-bind="state.modalData"
          :draggable="true"
          :defaultWidth="modalDefaults.width"
          :defaultHeight="modalDefaults.height">
            <div class="modal-body" slot="modal-body">

                <!-- Slide SETTINGS -->
                <div class="form-group">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" v-model="settings.blockTitle"/>
                    </div>

                    <div v-if="!settings.paddingResponsive" class="form-group">
                        <label>Padding</label> <i @click="settings.paddingResponsive = !settings.paddingResponsive" class="fa fa-desktop" title="Responsive On"></i>
                        <px v-model="settings.padding"></px>
                    </div>

                    <div v-if="settings.paddingResponsive" class="form-group">
                        <label>Padding</label> <i @click="settings.paddingResponsive = !settings.paddingResponsive" class="fa fa-desktop" title="Responsive Off"></i>
                        <div v-if="settings.paddingResponsive" class="form-sub-group">
                            <div class="form-group">
                                <label>Desktop</label>
                                <px v-model="settings.padding"></px>
                            </div>
                            <div class="form-group">
                                <label>Tablet Portrait</label>
                                <px v-model="settings.paddingTabletPortrait"></px>
                            </div>
                            <div class="form-group">
                                <label>Tablet Landscape</label>
                                <px v-model="settings.paddingTabletLandscape"></px>
                            </div>
                            <div class="form-group">
                                <label>Mobile Portrait</label>
                                <px v-model="settings.paddingMobilePortrait"></px>
                            </div>
                            <div class="form-group">
                                <label>Mobile Landscape</label>
                                <px v-model="settings.paddingMobileLandscape"></px>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" slot="modal-footer">
                <button type="button" class="btn btn-primary" @click="closeSlideModal">Close</button>
            </div>
        </modal>

        <modal ref="layerModal" :title="layerModalTitle"
          v-model="showLayerModal"
          v-bind="state.modalData"
          :draggable="true"
          :defaultWidth="modalDefaults.width"
          :defaultHeight="modalDefaults.height">
            <div class="modal-body" slot="modal-body">
                <!-- LAYERS -->
                <div v-if="layersView" class="form-group">
                    <label>Layers</label>
                    <div class="slide-layers">
                        <div v-for="(layer, index) in layers">
                            <div class="slide-layer" :class="{ 'layer-active': activeLayer(layer.uniqueId) }">
                                <span style="margin-right: 10px;">{{ layer.settings.blockTitle }} <span v-if="layer.settings.blockTitle == 'Layer'">{{ index }}</span></span>
                                <i class="fa fa-file-text" title="Add Text" @click="addLayerItem(layer.uniqueId, 'SlideText')"></i>
                                <i class="fa fa-image" title="Add Image" @click="addLayerItem(layer.uniqueId, 'SlideImage')"></i>
                                <i class="fa fa-times pull-right" style="margin-right: 0;" @click="removeLayer(layer.uniqueId)"></i>
                                <i class="fa fa-cog pull-right" @click="editLayerSettings(layer.uniqueId, index)"></i>
                            </div>
                            <div v-for="component in layer.components" class="slide-layer-block">
                                {{ getComponentTitle(component) }}
                                <i class="fa fa-times pull-right" style="margin-right: 0;" @click="removeLayerItem(component, layer.uniqueId)"></i>
                                <i class="fa fa-cog pull-right" @click="editLayerItemSettings(component)"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LAYER SETTINGS -->
                <div v-else class="form-group">
                    <div v-if="settingsType =='Layer'">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" v-model="layerSettings.blockTitle"/>
                        </div>

                        <div class="form-group">
                            <label>Display</label>
                            <select class="form-control" v-model="layerSettings.display">
                                <option value='block'>Block</option>
                                <option value='flex'>Flex</option>
                            </select>
                        </div>

                        <div v-if="layerSettings.display =='flex'" class="form-group">
                            <label>Flex Direction</label>
                            <select class="form-control" v-model="layerSettings.flexDirection">
                                <option value='column'>Column</option>
                                <option value='row'>Row</option>
                            </select>
                        </div>

                        <div v-if="layerSettings.display =='flex' && layerSettings.flexDirection == 'column'" class="form-group">
                            <label>Justify Content</label>
                            <select class="form-control" v-model="layerSettings.justifyContent">
                                <option value='flex-start'>Start</option>
                                <option value='flex-end'>End</option>
                                <option value='center'>Center</option>
                                <option value='space-between'>Space Between</option>
                                <option value='space-around'>Space Around</option>
                                <option value='space-evenly'>Space Evenly</option>
                            </select>
                        </div>

                        <div v-if="layerSettings.display =='flex'" class="form-group">
                            <label>Align Items</label>
                            <select class="form-control" v-model="layerSettings.alignItems">
                                <option value='flex-start'>Start</option>
                                <option value='center'>Center</option>
                                <option value='flex-end'>End</option>
                            </select>
                        </div>

                        <div v-if="!layerSettings.paddingResponsive" class="form-group">
                            <label>Padding</label> <i @click="layerSettings.paddingResponsive = !layerSettings.paddingResponsive" class="fa fa-desktop" title="Responsive On"></i>
                            <px v-model="layerSettings.padding"></px>
                        </div>

                        <div v-if="layerSettings.paddingResponsive" class="form-group">
                            <label>Padding</label> <i @click="layerSettings.paddingResponsive = !layerSettings.paddingResponsive" class="fa fa-desktop" title="Responsive Off"></i>
                            <div v-if="layerSettings.paddingResponsive" class="form-sub-group">
                                <div class="form-group">
                                    <label>Desktop</label>
                                    <px v-model="layerSettings.padding"></px>
                                </div>
                                <div class="form-group">
                                    <label>Tablet Portrait</label>
                                    <px v-model="layerSettings.paddingTabletPortrait"></px>
                                </div>
                                <div class="form-group">
                                    <label>Tablet Landscape</label>
                                    <px v-model="layerSettings.paddingTabletLandscape"></px>
                                </div>
                                <div class="form-group">
                                    <label>Mobile Portrait</label>
                                    <px v-model="layerSettings.paddingMobilePortrait"></px>
                                </div>
                                <div class="form-group">
                                    <label>Mobile Landscape</label>
                                    <px v-model="layerSettings.paddingMobileLandscape"></px>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ITEMS SETTINGS -->
                    <div v-if="settingsType == 'SlideText'">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" v-model="layerItemSettings.blockTitle"/>
                        </div>
                        <div class="form-group">
                            <label>Class</label>
                            <input type="text" class="form-control" v-model="layerItemSettings.contentClass">
                        </div>
                    </div>
                    <div v-if="settingsType == 'SlideImage'">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" v-model="layerItemSettings.blockTitle"/>
                        </div>
                        <div class="form-group">
                            <label>Responsive</label>
                            <select class="form-control" v-model="layerItemSettings.imageResponsive">
                                <option v-bind:value='true'>Yes</option>
                                <option v-bind:value='false'>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Position</label>
                            <select class="form-control" v-model="layerItemSettings.imagePosition">
                                <option value="flex-start">Left</option>
                                <option value="center">Center</option>
                                <option value="flex-end">Right</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Class</label>
                            <input type="text" class="form-control" v-model="layerItemSettings.contentClass">
                            <small>Add any additional classes here.</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" slot="modal-footer">
                <button v-if="!layersView" type="button" class="btn btn-primary" @click="goBack()">Back</button>
                <button v-if="layersView" type="button" class="btn btn-primary" @click="addLayer()">Add Layer</button>
                <button type="button" class="btn btn-primary" @click="closeLayerModal">Close</button>
            </div>
        </modal>
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
            blockTitle: {type: String, default: 'Slide'},
            display: {type: String, default: 'block'},
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
                    width: 400,
                    height: 400
                },
                state: {
                  modalData: {
                    top: 0,
                    left: 0,
                    width: null,
                    height: null
                  }
                },
                showLayerModal: false,
                showSlideModal: false,
                image: null,
                editingSlide: false,
                currentLayer: 0,
                currentIndex: null,
                layersView: true,
                layerSettings: null,
                settingsType: 'Layer'
            }
        },
        props: {
            visible: {type: Boolean, default: false},
            slider: {type: Object, default: null},
            contentRenderStyle: {type: String, default: 'boxed'},
        },
        computed: {
            container: function() {
                if(this.contentRenderStyle == 'boxed')
                    return 'container'
                else
                    return 'fluid'
            },
            slideStyles: function() {
                // console.log('image: ' + this.image)
                let styles = ""
                if(this.image) {
                    styles = styles + 'background-image: url("' +this.image+'");'
                    styles = styles + 'background-size: cover;'
                    styles = styles + 'background-position: center;'
                    styles = styles + 'background-position: center;'
                }
                styles = styles + 'padding: ' + this.settings.padding

                return styles
            },
            layerModalTitle: function () {
                if(this.layersView)
                    if(this.settingsType == 'Layer')
                        return 'Layer ' + this.currentIndex + ' Settings'
                    else {
                        return 'Item Settings'
                    }
                else {
                    return 'Layers'
                }
            },
            settings () {
                return this.$store.getters.itemSettings(this.uniqueId)
            },
            layers: {
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
            openMediaModalSwitch(val, old) {
                if(val == true) {
                    this.openMediaModal()
                    // console.log('opening media modal')
                }
            },
            layerSettings: {
                handler(val) {
                    this.updateItemSettings({settings: val, uniqueId: this.currentLayer})
                },
                deep: true
            },
            layerItemSettings: {
                handler(val) {
                    this.updateItemSettings({settings: val, uniqueId: this.currentLayerItem})
                },
                deep: true
            },
            settings: {
                handler(val) {
                    this.updateItemSettings({settings: val, uniqueId: this.uniqueId})
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
            goBack() {
                this.layersView = true
                this.currentIndex = null
                this.currentLayer = 0
                this.currentLayerItem = 0
            },
            editLayerSettings(uniqueId, index) {
                this.layersView = false
                this.settingsType = 'Layer'
                this.currentIndex = index
                this.currentLayer = uniqueId
                this.layerSettings = this.getComponentSettings(uniqueId)
            },
            editLayerItemSettings(uniqueId) {
                this.layersView = false
                this.settingsType = this.getComponentType(uniqueId)
                this.currentLayerItem = uniqueId
                this.layerItemSettings = this.getComponentSettings(uniqueId)
            },
            getComponentTitle(uniqueId) {
                let component = this.$store.getters.item(uniqueId)

                return component.settings.blockTitle
            },
            getComponentType(uniqueId) {
                let component = this.$store.getters.item(uniqueId)

                return component.type
            },
            getComponentSettings(uniqueId) {
                let component = this.$store.getters.item(uniqueId)
                return component.settings
            },
            activeLayer(val) {
                return val == this.currentLayer
            },
            setActiveLayer(uniqueId) {
                // console.log('active: ' + uniqueId)
                this.currentLayer = uniqueId
            },
            ...mapActions([
                'addItem',
                'removeItem',
                'updateItemsList',
                'updateItemContent',
                'updateItemSettings',
            ]),
            responsiveLayerSwitch(val) {
                this.layerSettings[val] =! this.layerSettings[val]
            },
            responsiveSwitch(val) {
                this.settings[val] = !this.settings[val]
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
            addLayer(compName = 'SlideLayer') {
                let comp = getComponentByName(compName)

                if (!comp) {
                    alert('Wrong component\'s name')
                    return false
                }

                let customSettings = processSettingsConfig(compName)

                this.addItem({
                    type: compName,
                    title: comp.options.customSettings.blockTitle.default,
                    parentId: this.uniqueId,
                    settingsConfig: customSettings,
                    settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                })
            },
            addLayerItem(layerId, compName = undefined) {
                let comp = getComponentByName(compName)

                if (!comp) {
                    alert('Wrong component\'s name')
                    return false
                }

                let customSettings = processSettingsConfig(compName)

                this.addItem({
                    type: compName,
                    title: comp.options.customSettings.blockTitle.default,
                    parentId: layerId,
                    settingsConfig: customSettings,
                    settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                })
            },
            removeLayer(uniqueId) {
                this.removeItem({
                    id: uniqueId,
                    parentId: this.uniqueId
                })
            },
            removeLayerItem(uniqueId, layerId) {
                this.removeItem({
                    id: uniqueId,
                    parentId: layerId
                })
            },
            removeBlock() {
                this.$emit('remove', this.uniqueId)
            },
            closeLayerModal() {
                // this.saveData()
                this.showLayerModal = false
            },
            layersModal() {
                this.showLayerModal = true
            },
            closeSlideModal() {
                // this.saveData()
                this.showSlideModal = false
            },
            slideModal() {
                this.showSlideModal = true
            },
            processContent(content) {
                this.inputContent = new Object()
            }
        },
        mounted : function() {
            this.image = this.content
        }
    }
</script>

<style scoped lang="less">
    .header {
        opacity: 1
    }

    .fa-cog {
        margin: 0
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
    }

    .zindex10 {
    }

    .layers {
        position: absolute;
        left:0;
        top: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .add-layer-container {
        top: 20px;
        left: 0;
        right: 0;
        margin-left: auto;
        margin-right: auto;
        position: absolute;
        text-align: center;
        pointer-events: none;
    }

    .add-layer {
        border: 1px solid rgba(173, 216, 230, 0.5);
        padding: 5px 10px;
        margin: 0;
        cursor: pointer;
        border-radius: 0%;
        background-color: rgba(43, 92, 147, 0.65);
        color: #b8c7ce;

        pointer-events: all;

        &:hover {
            background-color: rgba(43, 92, 147, 0.85);
            border: 1px solid rgba(173, 216, 230, 0.8);
        }
    }

    .slide-layers {
        font-size: 13px;
    }

    .slide-layer {
        padding: 5px 10px;
        background-color: rgba(54, 112, 176, 0.15);
        border: 1px solid rgba(0, 0, 0, 0.25);
        cursor: pointer;
        margin-bottom: 2px;
    }

    .slide-layer-block {
        padding: 5px 10px;
        margin-left: 20px;
        margin-bottom: 2px;
        background-color: rgba(54, 112, 176, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.2);
        cursor: pointer;
    }

    .layer-active {
        background-color: rgba(54, 112, 176, 0.45);
        border: 1px solid rgba(0, 0, 0, 0.45);
    }

    .slide-layer-buttons {

        position: absolute;
        right: 10px;
        width: 120px;
    }

    .slide-layer-button {
        background-color: rgba(54, 112, 176, 0.2);
        border: 1px solid rgba(0, 0, 0, 0.25);
        cursor: pointer;
        padding: 5px 22px;
        padding-left: 5px;
        margin-bottom: 2px;
    }

    .fa-file-text {
        margin-right: 5px;
        font-size: 14px;
    }


</style>
