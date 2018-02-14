<template>
    <div class="content-block">
        <div v-show="showContent" class="columns-block-body">
            <div class="form-group">
                <!-- <label>{{ settings.blockTitle }}</label> -->
                <div class="column-container" v-bind:style="columnsStyles">
                    <component
                        v-for="column in columns"
                        class="column-item"
                        v-bind:is="column.type"
                        v-bind="column.settings"
                        :width="column.settings.width"
                        v-bind:style="{'width': column.settings.width, 'margin':'0 ' + settings.columnSpacing}"
                        :uniqueId="column.uniqueId"
                        :show-headers="showHeaders"
                        :container-preview="containerPreview"
                        :transparent-input-background="transparentInputBackground"
                        v-on:pick-block-modal="pickBlockModal"
                        :key="column.uniqueId">
                    </component>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>

    </div>
</template>

<script>
    import GeneralMixin from '../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'
    import { getComponentByName, processSettingsConfig } from 'utils/helpers.js'

    export default {
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Columns'},
            columnsClass: {type: String, default: ''},

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
        },
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
                    height: 820
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
                if(this.containerPreview) {
                    if(this.settings.backgroundImage) {
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
                        styles = styles + 'box-shadow: inset 0 0 0 2000px '+this.settings.backgroundColor+';'
                    }
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
                    console.log(this.$store.getters.items(this.uniqueId))
                    return this.$store.getters.items(this.uniqueId)
                },
                set(object) {
                    console.log('something is getting set')
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
                console.log('ever here?')
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

                this.columnsCompatibleList = []
                for (var i = 0; i < this.columnsCompatibleTypes.length; i++) {
                    let columns = new Object()
                    columns.type = this.columnsCompatibleTypes[i]
                    switch (columns.type) {
                        case 'zero':
                            columns.list = []
                        break
                        case 'one-whole':
                            columns.list = []
                            this.makeCompatibleColumns(1, '100%', '1/1', columns)
                        break

                        case 'two-equal':
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
            },
            setColumnsWidths(columnType) {
                console.log('colType: ' + columnType)
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
            },
            pickBlockModal(columnId) {
                let params = new Object
                params.mode = "template"

                params.cb = (block) => {
                    this.$bus.$emit('column-add-block', columnId, block)
                }

                this.$bus.$emit('open-blocks-modal', params)
            },
            removeColumn(blockId) {
                this.removeItem({
                    id: blockId,
                    parentId: this.uniqueId
                })
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

                for (var i = 0; i < this.columns.length; i++) {
                    var column = this.columns[i]
                    column.text = columnType
                }
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
        },
        mounted : function() {
            this.setColumnsWidths(this.settings.selectedColumns)
            this.renderCompatibleColumnVariations(this.columns.length)
            console.log(this.columns)
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
    //   width: 50%;
      border: 1px solid  rgba(0,0,0, 0.15);
      margin: 0px;
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
