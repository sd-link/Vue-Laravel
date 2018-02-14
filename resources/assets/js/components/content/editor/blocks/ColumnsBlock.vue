<template>
    <div :id="uniqueId" class="content-block">
        <div v-show="showHeaders" class="content-block-header">
            <i class="fa fa-arrows" style="cursor: move; font-size: 16px; margin-right: 8px;" aria-hidden="true"></i>
            <i class="fa fa-cog" @click="blockSettings()" style="cursor: pointer; font-size: 16px; margin-right: 8px;" aria-hidden="true"></i>
            <!-- <i class="fa fa-columns" @click="editColumns()" style="cursor: pointer; font-size: 16px; margin-right: 8px;" aria-hidden="true"></i> -->
            {{ settings.blockTitle }}
            <button v-on:click="removeColumns" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>

        <div v-show="showContent" class="columns-block-body">

                <!-- <label v-show="!showHeaders && !containerPreview">{{ settings.blockTitle }}</label> -->
                <div class="column-container" v-bind:style="columnsStyles">
                    <component
                        v-for="column in columns"
                        class="column-item"
                        v-bind:is="column.type"
                        v-bind="column.settings"
                        :width="column.width"
                        v-bind:style="{'width': column.width, 'margin':'0 ' + settings.columnSpacing}"
                        :uniqueId="column.uniqueId"
                        :show-headers="showHeaders"
                        :container-preview="containerPreview"
                        :transparent-input-background="transparentInputBackground"
                        v-on:pick-block-modal="pickBlockModal"
                        :key="column.uniqueId">
                    </component>
                </div>

        </div>

        <modal ref="settingsModal" :title="modalTitle"
          v-model="showModal"
          v-bind="state.modalData"
          :draggable="true"
          :defaultWidth="modalDefaults.width"
          :defaultHeight="modalDefaults.height">
            <div class="modal-body" slot="modal-body">
                <div class="form-group">
                    <label>Block Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
                </div>

                <div class="form-group">
                    <label>Columns Class</label>
                    <input type="text" class="form-control" v-model="settings.columnsClass">
                    <small>Add your own custom classes to this block.</small>
                </div>


                <div class="form-group">
                    <label>Columns</label>
                    <div v-for="column in columns">
                        <div class="form-group" style="margin-bottom: 4px; padding: 5px; border: 1px solid rgba(0,0,0, 0.15);">
                            <span style="padding-left: 12px; font-weight: bold;">{{ column.text }}</span>
                            <span @click="removeColumn(column.uniqueId)" style="cursor: pointer;" class="pull-right">x</span>
                        </div>
                    </div>
                    <div v-show="columns.length < 6" class="text-center">
                        <button type="button" class="btn btn-primary" style="padding: 2px 12px;"@click="addColumn">+</button>
                    </div>
                </div>

                <div v-show="settings.selectedColumns != 'zero'" class="form-group">
                    <div>
                        <label>Compatible Variations</label>
                    </div>
                    <div style="display: flex; flex-wrap: wrap;">
                        <div @click="setColumnsWidths(columns.type)" v-for="columns in columnsCompatibleList"
                        :class="['column-selector-container',
                        {
                            'selected-columns': settings.selectedColumns == columns.type,
                            'one-whole': settings.selectedColumns == 'one-whole',
                            'four-equal': settings.selectedColumns == 'four-equal',
                            'five-equal': settings.selectedColumns == 'five-equal',
                            'six-equal': settings.selectedColumns == 'six-equal'
                        }]">
                            <div v-for="column in columns.list" class="column-item" v-bind:style="{'padding': '12px', 'text-align': 'center', 'width': column.width}">
                                {{column.text}}
                            </div>
                        </div>
                    </div>
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
                        <div style="clean: both;">

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
            <div class="modal-footer" slot="modal-footer">
                <button type="button" class="btn btn-primary" @click="closeAction">Close</button>
            </div>
        </modal>
    </div>
</template>

<script>
    import GeneralMixin from '../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'
    import Modal from 'components/ui/Modal.vue'
    import { getComponentByName, processSettingsConfig } from 'utils/helpers.js'

    export default {
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Columns'},
            columnsClass: {type: String, default: ''},

            visibleDesktop: {type: Boolean, default: true},
            visibleTablet: {type: Boolean, default: true},
            visibleMobile: {type: Boolean, default: true},

            heightResponsive: {type: Boolean, default: false},
            height: {type: String, default: 'auto'},
            heightTabletPortrait: {type: String, default: 'auto'},
            heightTabletLandscape: {type: String, default: 'auto'},
            heightMobilePortrait: {type: String, default: 'auto'},
            heightMobileLandscape: {type: String, default: 'auto'},

            paddingResponsive: {type: Boolean, default: false},
            padding: {type: String, default: '0px'},
            paddingTabletPortrait: {type: String, default: '0px'},
            paddingTabletLandscape: {type: String, default: '0px'},
            paddingMobilePortrait: {type: String, default: '0px'},
            paddingMobileLandscape: {type: String, default: '0px'},

            marginResponsive: {type: Boolean, default: false},
            margin: {type: String, default: '0px auto'},
            marginTabletPortrait: {type: String, default: '0px auto'},
            marginTabletLandscape: {type: String, default: '0px auto'},
            marginMobilePortrait: {type: String, default: '0px auto'},
            marginMobileLandscape: {type: String, default: '0px auto'},

            columnSpacing: {type: String, default: '2px'},
            selectedColumns: {type: String, default: 'two-equal'},

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
        },
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
                    height: 620
                },
                editColumnsModalDefaults: {
                    width: 600,
                    height: 300
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
                showBlocksModal:  false,

                columnsDefinitionList: [],
                columnsCompatibleTypes: [],
                columnsCompatibleList: [],
                columnList: [],
                initingBlock: true,
                cssSettingsOpen: false,
                columnsSettingsOpen: true,
                showPreview: true,
            }
        },
        props: {
            transparentInputBackground: {type: Boolean, default: false}
        },
        computed: {
            modalTitle: function () {
                return this.settings.blockTitle + ' Settings'
            },
            columnsStyles: function() {
                let styles = ""
                // console.log('container styles')
                if(this.containerPreview) {
                    if(this.settings.backgroundImage) {
                        console.log('bkg: ' + this.settings.backgroundImage)
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
                    styles = styles + 'height: '+this.settings.height+';'
                    styles = styles + 'padding: '+this.settings.padding+';'
                    styles = styles + 'margin: '+this.settings.margin+';'
                }
                return styles
            },
            settings: {
                get() {
                    return this.$store.getters.itemSettings(this.uniqueId)
                }
            },
            columns: {
                get() {
                    return this.$store.getters.items(this.uniqueId)
                },
                set(object) {
                    this.updateItemsList({list: _.map(object, 'uniqueId'), id: this.uniqueId})
                }
            }
        },
        watch: {
            settings: {
                handler(newVal) {
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            },
            columns(val, old) {
                if(val.length != old.length) {
                    switch (val.length) {
                        case 0:
                            this.settings.selectedColumns = 'zero'
                        break;
                        case 1:
                            this.settings.selectedColumns = 'one-whole'
                        break;
                        case 2:
                            this.settings.selectedColumns = 'two-equal'
                        break;
                        case 3:
                            this.settings.selectedColumns = 'three-equal'
                        break;
                        case 4:
                            this.settings.selectedColumns = 'four-equal'
                        break;
                        case 5:
                            this.settings.selectedColumns = 'five-equal'
                        break;
                        case 6:
                            this.settings.selectedColumns = 'six-equal'
                        break;
                        default:
                    }
                }
                this.renderCompatibleColumnVariations(val.length)
                this.setColumnsWidths(this.settings.selectedColumns)
            }
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
            makeCompatibleColumns(loops, width, text, columns) {
                for (var i = 0; i < loops; i++) {
                    var column = new Object()
                    column.width = width
                    column.text = text
                    columns.list.push(column)
                }
            },
            renderCompatibleColumnVariations(length) {
                switch (length) {
                    case 0:
                        this.columnsCompatibleTypes = ['zero']
                    break;
                    case 1:
                        this.columnsCompatibleTypes = ['one-whole']
                    break;
                    case 2:
                        this.columnsCompatibleTypes = ['two-equal', 'two-thirds-one-third', 'one-third-two-thirds', 'one-fourth-three-fourth', 'three-fourth-one-fourth']
                    break;
                    case 3:
                        this.columnsCompatibleTypes = ['three-equal', 'one-half-one-fourth-one-fourth', 'one-fourth-one-fourth-one-half', 'one-fourth-one-half-one-fourth']
                    break;
                    case 4:
                        this.columnsCompatibleTypes = ['four-equal']
                    break;
                    case 5:
                        this.columnsCompatibleTypes = ['five-equal']
                    break;
                    case 6:
                        this.columnsCompatibleTypes = ['six-equal']
                    break;
                    default:
                }

                // console.log('render compatible variations')

                this.columnsCompatibleList = []
                for (var i = 0; i < this.columnsCompatibleTypes.length; i++) {
                    let columns = new Object()
                    columns.type = this.columnsCompatibleTypes[i]
                    switch (columns.type) {
                        case 'zero':
                            // console.log('two-equal')
                            columns.list = []
                        break
                        case 'one-whole':
                            // console.log('two-equal')
                            columns.list = []
                            this.makeCompatibleColumns(1, '100%', '1/1', columns)
                        break

                        case 'two-equal':
                            // console.log('two-equal')
                            columns.list = []
                            this.makeCompatibleColumns(2, '50%', '1/2', columns)
                        break

                        case 'three-equal':
                            columns.list = []
                            this.makeCompatibleColumns(3, '33.33%', '1/3', columns)
                        break

                        case 'four-equal':
                            columns.list = []
                            this.makeCompatibleColumns(4, '25%', '1/4', columns)
                        break

                        case 'five-equal':
                            columns.list = []
                            this.makeCompatibleColumns(5, '20%', '1/5', columns)
                        break

                        case 'six-equal':
                            columns.list = []
                            this.makeCompatibleColumns(6, '16.66%', '1/6', columns)
                        break

                        case 'two-thirds-one-third':
                            // console.log('two-thirds-one-third')
                            columns.list = []
                            var column = new Object()
                            column.width = '60%'
                            column.text = '2/3'
                            columns.list.push(column)
                            column = new Object()
                            column.width = '40%'
                            column.text = '1/3'
                            columns.list.push(column)
                        break


                        case 'one-third-two-thirds':
                            // console.log('one-third-two-thirds')
                            columns.list = []
                            var column = new Object()
                            column.width = '40%'
                            column.text = '1/3'
                            columns.list.push(column)
                            column = new Object()
                            column.width = '60%'
                            column.text = '2/3'
                            columns.list.push(column)
                        break

                        case 'one-fourth-three-fourth':
                            // console.log('one-fourth-three-fourth')
                            columns.list = []
                            var column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)
                            column = new Object()
                            column.width = '75%'
                            column.text = '3/4'
                            columns.list.push(column)
                        break

                        case 'three-fourth-one-fourth':
                            // console.log('three-fourth-one-fourth')
                            columns.list = []
                            var column = new Object()
                            column.width = '75%'
                            column.text = '3/4'
                            columns.list.push(column)
                            column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)
                        break

                        case 'one-half-one-fourth-one-fourth':
                            // console.log('one-half-one-fourth-one-fourth')
                            columns.list = []
                            var column = new Object()
                            column.width = '50%'
                            column.text = '1/2'
                            columns.list.push(column)

                            column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)

                            column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)
                        break

                        case 'one-fourth-one-fourth-one-half':
                            // console.log('one-fourth-one-fourth-one-half')
                            columns.list = []
                            var column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)

                            column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)

                            column = new Object()
                            column.width = '50%'
                            column.text = '1/2'
                            columns.list.push(column)
                        break

                        case 'one-fourth-one-half-one-fourth':
                            // console.log('one-fourth-one-half-one-fourth')
                            columns.list = []
                            var column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)

                            column = new Object()
                            column.width = '50%'
                            column.text = '1/2'
                            columns.list.push(column)

                            column = new Object()
                            column.width = '25%'
                            column.text = '1/4'
                            columns.list.push(column)
                        break

                        default:
                    }
                    this.columnsCompatibleList.push(columns)
                }
                // console.log(this.columnsDefinitionList)
            },
            setColumnsWidths(columnType) {
                // console.log('---------------------setColumnsWidths: ' + columnType)
                this.settings.selectedColumns = columnType
                for (var i = 0; i < this.columns.length; i++) {
                    var column = this.columns[i]

                    switch (this.settings.selectedColumns) {
                        case 'zero':
                            column.width = '0%'
                            column.text = '0'
                        break
                        case 'one-whole':
                            column.width = '100%'
                            column.text = '1/1'
                        break

                        case 'two-equal':
                            column.width = '50%'
                            column.text = '1/2'
                        break

                        case 'three-equal':
                            column.width = '33.33%'
                            column.text = '1/3'
                        break

                        case 'four-equal':
                            column.width = '25%'
                            column.text = '1/4'
                        break

                        case 'five-equal':
                            column.width = '20%'
                            column.text = '1/5'
                        break

                        case 'six-equal':
                            column.width = '16.66%'
                            column.text = '1/6'
                        break

                        // variations of two
                        case 'two-thirds-one-third':
                            switch (i+1) {
                                case 1:
                                    column.width = '66.5%'
                                    column.text = '2/3'
                                break

                                case 2:
                                    column.width = '33.5%'
                                    column.text = '1/3'
                                break
                            }
                        break

                        case 'one-third-two-thirds':
                            switch (i+1) {
                                case 1:
                                    column.width = '33.5%'
                                    column.text = '1/3'
                                break

                                case 2:
                                    column.width = '66.5%'
                                    column.text = '2/3'
                                break
                            }
                        break

                        case 'one-fourth-three-fourth':
                            switch (i+1) {
                                case 1:
                                    column.width = '25%'
                                    column.text = '1/3'
                                break

                                case 2:
                                    column.width = '75%'
                                    column.text = '2/3'
                                break
                            }
                        break

                        case 'three-fourth-one-fourth':
                            switch (i+1) {
                                case 1:
                                    column.width = '75%'
                                    column.text = '1/3'
                                break

                                case 2:
                                    column.width = '25%'
                                    column.text = '2/3'
                                break
                            }
                        break

                        // variations of 3
                        case 'one-half-one-fourth-one-fourth':
                            switch (i+1) {
                                case 1:
                                    column.width = '50%'
                                    column.text = '1/2'
                                break

                                case 2:
                                    column.width = '25%'
                                    column.text = '1/4'
                                break

                                case 3:
                                    column.width = '25%'
                                    column.text = '1/4'
                                break
                            }
                        break

                        case 'one-fourth-one-fourth-one-half':
                            switch (i+1) {
                                case 1:
                                    column.width = '25%'
                                    column.text = '1/4'
                                break

                                case 2:
                                    column.width = '25%'
                                    column.text = '1/4'
                                break

                                case 3:
                                    column.width = '50%'
                                    column.text = '1/2'
                                break
                            }
                        break

                        case 'one-fourth-one-half-one-fourth':
                            switch (i+1) {
                                case 1:
                                    column.width = '25%'
                                    column.text = '1/4'
                                break

                                case 2:
                                    column.width = '50%'
                                    column.text = '1/4'
                                break

                                case 3:
                                    column.width = '25%'
                                    column.text = '1/2'
                                break
                            }
                        break

                        default:
                    }
                }
                this.initingBlock = false
            },
            pickBlockModal(columnId) {
                let params = new Object
                console.log('params mode: ' + params.mode)
                params.cb = (block) => {
                    // console.log('column: ' + columnId)
                    // console.log('user picked: ' + block)
                    this.$bus.$emit('column-add-block', columnId, block)
                }

                this.$bus.$emit('open-blocks-modal', params)
            },
            addColumn() {
                if(this.columns.length < 6) {
                    let compName = 'ColumnBlock'
                    let comp = getComponentByName(compName)
                    if (!comp) {
                        alert('Wrong component\'s name')
                        return false
                    }

                    let customSettings = processSettingsConfig(compName)

                    this.addItem({
                        type: compName,
                        title: 'Column',
                        parentId: this.uniqueId,
                        settingsConfig: customSettings,
                        settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                    })

                    this.updateColumnType()
                }
            },
            updateColumnType() {

                var columnType = '1/1'

                switch (this.columns.length) {
                    case 1:
                        columnType = '1/1'
                    break

                    case 2:
                        columnType = '1/2'
                    break

                    case 3:
                        columnType = '1/3'
                    break

                    case 4:
                        columnType = '1/4'
                    break

                    default:
                }

                console.log('updateColumnType: ' + columnType)

                for (var i = 0; i < this.columns.length; i++) {
                    var column = this.columns[i]
                    column.text = columnType
                }
            },
            removeColumns() {
                this.$emit('remove')
            },
            removeColumn(blockId) {
                this.removeItem({
                    id: blockId,
                    parentId: this.uniqueId
                })
            },
            closeAction() {
                // this.saveData()
                this.showModal = false
            },
            pickColumnsAction() {
                this.showColumnsEditModal = false
            },
            blockSettings() {
                this.showModal = true
            },
            editColumns() {
                this.showColumnsEditModal = true
            }
        },
        beforeDestroy() {
          //do something before destroying vue instance
            console.log('columns block before destroy')
        },
        mounted : function() {
            // console.log('mounted:' + this.settings.selectedColumns)
            this.setColumnsWidths(this.settings.selectedColumns)
            this.renderCompatibleColumnVariations(this.columns.length)

            if(this.columns.length == 0) {
                this.addColumn()
                this.addColumn()
            }
        }
    }
</script>

<style scoped lang="less">
    .border {
        border: 1px solid rgba(0,0,0, 0.15);
        padding: 10px;
        text-align: center;
        height: 62px;
    }

    .form-sub-group {
        padding: 8px 12px;
        border: 1px solid rgba(0,0,0,0.1);
    }

    .columns-block-body {
    }

    .no-padding {
        padding: 0px;
    }

    .column-bkg {
        background: rgba(255,255,255, 0.01);
        padding: 10px;
        text-align: center;
        border: 1px solid  rgba(0,0,0, 0.15);
    }

    .column-bkg:not(:first-child) {
        border-left: 0;
    }

    .columns {
        margin-bottom: 15px;
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

    .column-container {
        padding: 0;
        margin: 0;
        list-style: none;

        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        border: 1px solid transparent;
        width: 100%;
        // -webkit-flex-flow: row wrap;
        // justify-content: space-around;
    }

    .column-selector-container {
        padding: 0;
        margin: 0;
        list-style: none;

        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        padding: 2px 2px;
        border: 1px solid transparent;
        cursor: pointer;
        width: 33.3%;
    }

    .one-whole, .four-equal, .five-equal, .six-equal {
        width: 100%;
    }

    .selected-columns {
        border: 1px solid rgba(33, 144, 254, 0.4)
    }

    .column-item {
        background: rgba(255,255,255, 0.01);
        width: 50%;
        border: 1px solid  rgba(0,0,0, 0.15);
        margin: 0px;
        padding: 4px;
    }

    .column-item:first-child {
        margin-left: 0px !important;                       /*  on all but the first column  */
    }

    .column-item:last-child {
        margin-right: 0px !important;                      /*  on all but the last column  */
    }

    // .column-item:not(:first-child) {
    //     border-left: 0;
    // }
</style>
