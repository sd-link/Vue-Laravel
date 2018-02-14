<template>
    <div :id="uniqueId" class="content-block">
        <div v-show="showHeader" class="content-block-header">
            <i class="fa fa-cog" @click="blockSettings()" aria-hidden="true"></i>
            {{ taxonomyName }} <required v-if="required=='1'" class="required"></required>
            <button v-on:click="removeBlock" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>

        <div class="content-block-body" style="margin-top: 1px;">
            <div class="form-group">
                <label v-show="!showHeader">{{ taxonomyName }}</label> <required v-if="!showHeader && required=='1'" class="required"></required>
                <div class="">
                    <component v-for="block in selectList"
                        ref="currentComponent"
                        :is="block.component"
                        v-model="selected"
                        :multiple='allowMultiple'
                        :filterable='allowFilterable'
                        :allow-create='allowCreate'
                        :placeholder='placeholder'
                        :key="block.uniqueId"
                        :default-first-option="true"
                        v-on:change="onValueChanged">
                        <el-option
                            v-for="item in exampleOptions"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                            :class="{'-sub-item': item.isChild}">
                        </el-option>
                    </component>
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
                <div class="form-group">
                    <label for="">Taxonomy Name (plural)</label>
                    <input type="text" class="form-control" v-model="taxonomyName" :disabled='dbid != 0'>
                </div>

                <div class="form-group">
                    <label for="">Taxonomy Name (singular)</label>
                    <input type="text" class="form-control" v-model="taxonomyNameSingular" :disabled='dbid != 0'>
                </div>

                <div class="form-group">
                    <label for="">Can Have Children</label>
                    <select class="form-control" v-model="canHaveChildren" :disabled='dbid != 0'>
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Allow Multiple</label>
                    <select class="form-control" v-model="allowMultiple">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>

                <div v-show="allowMultiple" class="form-group">
                    <label for="">Max Multiple</label>
                    <el-input-number v-model="maxAllowed" :min="1" :max="3"></el-input-number>
                </div>

                <div class="form-group">
                    <label for="">Allow Create in place</label>
                    <select class="form-control" v-model="allowCreate">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Allow Filterable</label>
                    <select class="form-control" v-model="allowFilterable">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>

                <!-- <div class="form-group">
                    <label for="">Placeholder</label>
                    <input type="text" class="form-control" v-model="placeholder">
                </div> -->

                <div v-show="1==0" class="form-group">
                    <label for="">Visible on Index Page</label>
                    <select class="form-control" v-model="visibleOnIndexPage">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer" slot="modal-footer">
                <button type="button" class="btn btn-primary" @click="saveAction">Close</button>
            </div>
        </modal>
    </div>
</template>

<script>
    import Vue from 'vue'
    import { EventBus } from 'components/eventbus.js'
    import Modal from 'components/ui/Modal.vue'

    export default {
        data: function data() {
            return {
                selectList: [],
                modalDefaults: {
                    width: 600,
                    height: 650
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
                taxonomyName: this.initTaxonomyName,
                taxonomyNameSingular: this.initTaxonomyNameSingular,
                showHeader: this.initShowHeader,
                // placeholder: this.initPlaceholder,
                required: this.initRequired,
                maxAllowed: this.initMaxAllowed,
                allowMultiple: this.initAllowMultiple,
                allowCreate: this.initAllowCreate,
                allowFilterable: this.initAllowFilterable,
                canHaveChildren: this.initCanHaveChildren,
                visibleOnIndexPage: this.initVisibleOnIndexPage,
                exampleOptions: [
                    {
                        id: 1,
                        value: 'example1',
                        label: 'Parent'
                    },
                    {
                        id: 2,
                        value: 'example2',
                        label: 'Child 1',
                        isChild: true
                    },
                    {
                        id: 3,
                        value: 'example3',
                        label: 'Child 2',
                        isChild: true
                    },
                    {
                        id: 4,
                        value: 'example4',
                        label: 'Example4'
                    }
                ],
                selected: []
            }
        },
        props: {
            uniqueId: {type: String, default: null},
            saveTaxonomy:{type: Boolean, default: false},

            order: {type: Number, default: 0},
            dbid: {type: Number, default: 0},

            initShowHeader: {type: Boolean, default: true},

            initTaxonomyName: {type: String, default: 'Taxonomy'},
            initTaxonomyNameSingular: {type: String, default: 'Taxonomy'},
            // initPlaceholder: {type: String, default: 'Select Taxonomy'},
            initRequired: {type: Boolean, default: false},
            initMaxAllowed: {type: Number, default: 2},
            initAllowMultiple: {type: Boolean, default: true},
            initAllowCreate: {type: Boolean, default: true},
            initAllowFilterable: {type: Boolean, default: true},
            initCanHaveChildren: {type: Boolean, default: false},
            initVisibleOnIndexPage: {type: Boolean, default: false},
        },
        computed: {
            modalTitle: function () {
                return this.taxonomyName + ' Settings'
            },
            placeholder: function() {
                if(this.allowMultiple)
                    return 'Select ' + this.taxonomyName
                else
                    return 'Select ' + this.taxonomyNameSingular
            }
        },
        watch: {
            saveTaxonomy(val, old) {
                if(val == true) {
                    this.saveTaxonomyAction()
                }
            },
            initShowHeader (val, old) {
                if (val !== old) this.showHeader = val
            },
            allowMultiple(val, old) {
                this.removeSelect()
                this.createSelect()
            },
            allowCreate(val, old) {
                this.removeSelect()
                this.createSelect()
                this.selected = []
            },
            allowFilterable(val, old) {
                this.removeSelect()
                this.createSelect()
            },
        },
        methods: {
            createSelect() {
                let select = new Object()
                // select.uniqueId = name + 'el-select'
                select.uniqueId = _.uniqueId() + 'el-select'
                select.component = 'el-select'
                this.selectList.push(select)
                // this.selectCount = this.selectCount + 1
            },
            removeSelect() {
                this.selectList = []
            },
            removeBlock() {
                console.log('removing ' + this.uniqueId)
                this.selectList = []
                this.$emit('content-taxonomy-remove', this.uniqueId)
            },
            saveTaxonomyAction() {
                console.log('saveTaxonomyAction')
                let taxonomyData = {
                    dbid: this.dbid,
                    order: this.order,
                    taxonomyName: this.taxonomyName,
                    taxonomyNameSingular: this.taxonomyNameSingular,
                }

                let taxonomySettings = {
                    required: {type: 'boolean', value: this.required},
                    maxAllowed: {type: 'integer', value: this.maxAllowed},
                    allowMultiple: {type: 'boolean', value: this.allowMultiple},
                    allowCreate: {type: 'boolean', value: this.allowCreate},
                    allowFilterable: {type: 'boolean', value: this.allowFilterable},
                    canHaveChildren: {type: 'boolean', value: this.canHaveChildren},
                    visibleOnIndexPage: {type: 'boolean', value: this.visibleOnIndexPage},
                }
                this.$emit('content-taxonomy-save', taxonomyData, taxonomySettings)
            },
            saveAction() {
                // this.saveData()
                this.showModal = false
            },
            blockSettings() {
                this.showModal = true
            },
            onValueChanged() {
                if (this.$refs.currentComponent.length) {
                    this.$refs.currentComponent[0].handleClose()
                } else {
                    this.$refs.currentComponent.handleClose()
                }
            }
        },
        mounted : function() {
            console.log('mounted taxonomy')

            if(this.initNewBlock) {
                this.showModal = true
                $('html, body').scrollTop( $(document).height() )
            }

            // initialise select box
            this.createSelect()

        },
        destroyed : function() {
            // do something after destroying vue instance
            console.log('deleted taxonomy-block')
            this.removeSelect()
        },
        components: {
            Modal
        }
    }
</script>

<style scoped lang="scss">
    .header {
        opacity: 1
    }

    .el-select-dropdown__item {
        &.-sub-item {
            margin-left: 10px;
        }
    }
</style>
