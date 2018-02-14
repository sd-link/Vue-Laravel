<template>
    <div>
        <div class="column-block-body">
            <div v-bind:style="columnStyles">
                <draggable
                    @end="onEnd"
                    v-model="subItems"
                    style="min-height: 120px;"
                    :options="{handle:'.fa-arrows', chosenClass:'dragging1'}">
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
    </div>
</template>
<script>
    import GeneralMixin from '../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'
    import draggable from 'vuedraggable'
    import { getComponentByName, processSettingsConfig } from 'utils/helpers.js'

    export default {
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Column'},
            columnClass: {type: String, default: ''},
            width: {type: String, default: '20%'},

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
            }
        },
        props: {
            transparentInputBackground: {type: Boolean, default: false},
            width: {type: String, default: '50%'},
        },
        computed: {
            modalTitle: function () {
                return this.blockTitle + ' Settings'
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
                        styles = styles + 'box-shadow: inset 0 0 0 2000px '+this.settings.backgroundColor+';'
                    }
                    styles = styles + 'padding: '+this.settings.padding+';'
                    styles = styles + 'margin: '+this.settings.margin+';'
                }
                return styles
            },
        },
        watch: {
            settings: {
                handler(newVal) {
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            },
            width(val, old){
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
            addBlock(compName = undefined) {
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
            onEnd(evt) {
                // for (var i = 0; i < this.blockList.length; i++) {
                //     this.blockList[i].order = (i+1)
                // }
            },
            openBlocksModal() {
                let params = new Object
                params.mode = 'standard'
                params.cb = (block) => {
                    console.log('callback: ' + block)
                    this.addBlock(block)
                }

                this.$bus.$emit('open-blocks-modal', params)
            },
            removeBlock(uniqueId) {
                console.log('block to remove: ' + uniqueId)
            },
        },
        beforeDestroy: function() {
            console.log('removing: ' + this.uniqueId)
        },
        mounted : function() {
            this.settings.width = this.width
            console.log('mounted: ' + this.width)
        //     this.handlerMethod = (columnId, block) => {
        //         console.log('add block: ' + block)
        //         if(columnId == this.uniqueId)
        //             this.addBlock(block)
        //     }
        //
        //     this.$bus.$on('column-add-block', this.handlerMethod)
        }
    }
</script>
<style lang="less" scoped>
    .column-block-body {
        padding: 5px;
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
