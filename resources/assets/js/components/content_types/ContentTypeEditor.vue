<template>
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{modeText}} Content Type</h3>
            </div>
            <div class="box-body">
                <div class="form-group title-group">
                    <label for="title">Name (plural)</label>
                    <input v-model="typeName"
                        v-validate="'required'"
                        :disabled="typeId != 0"
                        :class="{'form-control':true, 'input': true, 'is-danger': errors.has('name plural') }"
                        name="name plural"
                        type="text"
                        placeholder="Enter plural">
                    <span v-show="errors.has('name plural')" class="help is-danger">{{ errors.first('name plural') }}</span>
                    <!-- <small>No numbers, no special characters.</small> -->
                </div>

                <div class="form-group title-group">
                    <label for="title">Name (singular)</label>
                    <input v-model="typeNameSingular"
                        v-validate="'required'"
                        :disabled="typeId != 0"
                        name="name singular"
                        :class="{'form-control':true, 'input': true, 'is-danger': errors.has('typeName') }"
                        type="text"
                        placeholder="Enter singular">
                    <span v-show="errors.has('name singular')" class="help is-danger">{{ errors.first('name singular') }}</span>
                    <!-- <small>No numbers, no special characters.</small> -->
                </div>

                <div class="form-group">
                    <label>Display Slug</label>
                    <select class="form-control" v-model="displayContentSlug">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Title Minimum Characters Required</label>
                    <el-input-number v-model="titleMinCharactersRequired" :min="2" :max="6"></el-input-number>
                </div>

                <div class="form-group">
                    <label>Title Max Characters Allowed</label>
                    <el-input-number v-model="titleMaxCharactersAllowed" :min="7" :max="180"></el-input-number>
                </div>


                <div id="blocks">
                    <draggable
                        :move="onMove"
                        @end="onEnd"
                        v-model="taxonomiesList"
                        :options="{handle:'.fa-arrows', chosenClass:'dragging1'}"
                    >
                        <component v-for="taxonomy in taxonomiesList"
                            is="taxonomy"
                            :uniqueId="taxonomy.uniqueId"
                            :dbid="taxonomy.dbid"
                            :init-taxonomy-name="taxonomy.name"
                            :init-taxonomy-name-singular="taxonomy.name_singular"
                            :order="taxonomy.order"
                            :init-new-taxonomy="taxonomy.newBlock"
                            v-bind="taxonomy.settings"
                            :save-taxonomy="saveTaxonomies"
                            :init-show-header="showHeaders"
                            :init-show-content="showContent"
                            v-on:content-taxonomy-remove="removeTaxonomy"
                            v-on:content-taxonomy-save="contentTaxonomySave"
                            :key="taxonomy.uniqueId">
                        </component>
                    </draggable>
                </div>

                <div id="buttons">
                    <button v-on:click="addTaxonomy('taxonomy')" type="button" name="button" class="btn btn-primary btn-sm">Create Taxonomy</button>
                </div>
            </div>
        </div>

        <modal ref="settingsModal" title="Title Settings"
          v-model="showModal"
          v-bind="state.modalData"
          :draggable="true"
          :defaultWidth="modalDefaults.width"
          :defaultHeight="modalDefaults.height"
        >
            <div class="modal-body" slot="modal-body">




            </div>
            <div class="modal-footer" slot="modal-footer">
                <button type="button" class="btn btn-primary" @click="saveAction">Close</button>
            </div>
        </modal>

    </div>
</template>

<script>
    import { EventBus } from 'components/eventbus.js'
    import Taxonomy from './Taxonomy.vue'
    import Modal from 'components/ui/Modal.vue'
    import draggable from 'vuedraggable'

    export default {
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
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
                showModal: false,
                taxonomyCount: 1,
                taxonomiesList: [],
                taxonomiesSettingsArray: [],
                taxonomiesDataArray: [],
                myArray: [],
                typeId: this.initId,
                typeName: this.initName,
                typeNameSingular: this.initNameSingular,
                typeDisplayName: this.initDisplayName,
                showHeaders: true,
                showContent: true,
                saveTaxonomies: false,
                taxonomySavedCount: 0,
                displayContentSlug: this.initDisplayContentSlug,
                mode: this.initMode,
                typeSettings: null,

                titleMinCharactersRequired: this.initTitleMinCharactersRequired,
                titleMaxCharactersAllowed: this.initTitleMaxCharactersAllowed,
            }
        },
        props: {
            blocks: {type: Array},
            initId: {type: Number, default: 0},
            initName: {type: String, default: ''},
            initNameSingular: {type: String, default: ''},
            initDisplayName: {type: String, default: ''},
            initMode: {type: String, default: 'create'},
            initDisplayContentSlug: {type: Boolean, default: false},

            initTitleMinCharactersRequired: {type: Number, default: 2},
            initTitleMaxCharactersAllowed: {type: Number, default: 120}
        },
        computed: {
            modeText: {
                get: function () {
                    if(this.mode == 'create')
                        return 'Create'
                    else if(this.mode == 'edit')
                        return 'Edit'
                },
                set: function (newValue) {
                    if(this.mode == 'create')
                        return 'Create'
                    else if(this.mode == 'edit')
                        return 'Edit'
                }
            }
        },
        watch: {
            mode(val, old) {
                this.modeText = 'update'
            },
        },
        methods: {
            titleSettings() {
                this.showModal = true
            },
            saveAction() {
                // this.saveData()
                this.showModal = false
            },
            onEnd(evt) {
                for (var i = 0; i < this.taxonomiesList.length; i++) {
                    this.taxonomiesList[i].order = (i+1)
                }
            },
            onMove(evt) {
                return !evt.related.classList.contains('item_disabled')
            },
            save() {
                console.log('save method')
                let typeData = {
                    name: this.typeName,
                    name_singular: this.typeNameSingular,
                    display_name: this.typeDisplayName
                }

                let typeSettings = this.typeSettings

                let displayContentSlug = new Object()
                displayContentSlug.value = this.displayContentSlug
                displayContentSlug.type = 'boolean'
                typeSettings.displayContentSlug = displayContentSlug

                self = this

                // let blocksData = { blocks: this.taxonomiesSettingsArray }
                // console.log(blocksData)
                let formData = {
                    id: this.typeId,
                    typeData: typeData,
                    typeSettings: typeSettings,
                    taxonomiesData: this.taxonomiesDataArray,
                    taxonomiesSettings: this.taxonomiesSettingsArray
                }

                return axios.post(route('backend.content.types.save'), formData)
                    .then((response) => {

                        // update new blocks with database id
                        for (var i = 0; i < self.taxonomiesList.length; i++) {
                            self.taxonomiesList[i].dbid = response.data.contentType.taxonomies[i].id
                        }

                        // update url
                        if (history.pushState && this.typeId == 0) {
                            console.log('updating url')
                            var path = window.location.pathname
                            path = path.replace(/create\b/g, "edit/")
                            var newurl = window.location.protocol + "//" + window.location.host + path + response.data.contentType.id
                            window.history.pushState({path:newurl},'',newurl)
                        }

                        this.typeId = response.data.contentType.id
                        self.saveTaxonomies = false
                        this.taxonomiesSettingsArray = []
                        this.taxonomiesDataArray = []
                        // blocksData = null
                        this.mode = 'edit'

                        EventBus.$emit('content-type-save-done')
                    })
                    .catch((error) => {
                        console.log('we got error')
                        console.log(error)
                    })
            },
            contentTaxonomySave(taxonomyData, taxonomySettings) {
                console.log('contentTaxonomySave')
                this.taxonomySavedCount = this.taxonomySavedCount + 1

                if(this.taxonomySavedCount == 1) {
                    this.taxonomiesSettingsArray = []
                    this.taxonomiesDataArray = []
                    console.log('first taxonomy')
                }

                // console.log(blockId)
                this.taxonomiesDataArray.push(taxonomyData)
                this.taxonomiesSettingsArray.push(taxonomySettings)

                if(this.taxonomySavedCount == this.taxonomiesList.length) {
                    // console.log(this.taxonomiesSettingsArray)
                    this.taxonomySavedCount = 0
                    console.log('last taxonomy')
                    this.save()
                }
            },
            addTaxonomy(tax = null, settings = new Object(), newBlock = true) {
                let taxonomy = new Object()

                if(tax) {
                    taxonomy.dbid = tax.id
                    taxonomy.name = tax.name
                    taxonomy.name_singular = tax.name_singular
                }
                else {
                    taxonomy.dbid = 0
                    taxonomy.name = 'tadaa'
                    taxonomy.name_singular = 'tada'
                }
                taxonomy.uniqueId = name + '-' + this.taxonomyCount
                taxonomy.newBlock = newBlock
                taxonomy.settings = settings
                taxonomy.order = this.taxonomyCount
                this.taxonomiesList.push(taxonomy)

                // console.log(name)

                this.taxonomyCount = this.taxonomyCount + 1
            },
            removeTaxonomy(uniqueId) {
                console.log('we get here')
                let blockToDelete

                for (var i = 0; i < this.taxonomiesList.length; i++) {
                    if (this.taxonomiesList[i].uniqueId == uniqueId) {
                        blockToDelete = this.taxonomiesList[i]
                        break
                    }
                }

                if(blockToDelete.dbid) {
                    let formData = {id: blockToDelete.dbid}
                    return axios.post(route('backend.content.types.delete_block'), formData).then((response) => {
                        console.log('block deleted: ' + blockToDelete)
                        this.taxonomiesList = $.grep(this.taxonomiesList, function(block){
                            return block.uniqueId != uniqueId;
                        })
                        // EventBus.$emit('content-type-save-done')
                    })
                    .catch((error) => {
                        console.log('we got an error')
                        console.log(error)
                    })
                } else {
                    console.log('block deleted: ' + blockToDelete)
                    this.taxonomiesList = $.grep(this.taxonomiesList, function(block){
                        return block.uniqueId != uniqueId;
                    })
                }

            },

            stringToBoolean: function(string){
                switch(string.toLowerCase().trim()){
                    case "true": case "yes": case "1": return true;
                    case "false": case "no": case "0": case null: return false;
                    default: return Boolean(string);
                }
            },
        },
        mounted: function() {
            let self = this

            if(this.typeId) {
                // console.log('id: ' + this.typeId)
                axios.get(route('backend.content.types.taxonomies', {id: this.typeId}))
                .then((response) => {
                    let taxonomies = response.data.taxonomies
                    for (var i = 0; i < taxonomies.length; i++) {

                        let taxonomySettings = taxonomies[i].settings
                        // console.log('dbId: ' +taxonomies[i].id)
                        let settings = {}
                        for (var j = 0; j < taxonomySettings.length; j++) {
                            let key = taxonomySettings[j].key
                            let initProp = 'init' + key.charAt(0).toUpperCase() + key.slice(1)
                            if(taxonomySettings[j].type == 'boolean') {
                                settings[initProp] = this.stringToBoolean(taxonomySettings[j].value)
                                // console.log(initProp+': '+this.stringToBoolean(taxonomySettings[j].value))
                            }
                            else if(taxonomySettings[j].type == 'integer') {
                                settings[initProp] = Number(taxonomySettings[j].value)
                                // console.log(initProp+': ' + Number(taxonomySettings[j].value))
                            }
                            else {
                                settings[initProp] = taxonomySettings[j].value
                                // console.log(initProp+': ' + taxonomySettings[j].value)
                            }
                        }

                        this.addTaxonomy(taxonomies[i], settings, false)
                    }
                })
                .catch((error) => {
                    console.log(error)
                })
            }

            window.addEventListener("popstate", function(e) {
                var path = location.pathname
                path = path.replace(/create\b/g, "")
                // console.log('path: ' + )
                console.log('pop state')
                window.location = window.location.protocol + "//" + window.location.host + path
            });

            EventBus.$on('content-type-save', function(settings) {
                console.log('content type save')
                self.$validator.validateAll().then( result => {
                    if (result) {
                        console.log('validated')
                        self.typeSettings = settings
                        if(self.taxonomiesList.length){
                            self.saveTaxonomies = true
                            console.log('taxonomies list full')
                        }
                        else {
                            self.save()
                        }

                    } else {
                        console.log('validation failed')
                    }

                })
            })

            window.addEventListener('keydown', function(event) {
                let key = event.keyCode
                console.log(key)
                if(event.ctrlKey) {
                    switch (key) {
                        // case 80:
                        //     event.preventDefault()
                        //     self.markdownPreview = !self.markdownPreview
                        // break;
                        // char H pressed
                        case 72:
                            event.preventDefault()
                            self.showHeaders = !self.showHeaders
                        break;
                        // char G pressed
                        case 71:
                            // console.log('hide content')
                            event.preventDefault()
                            self.showContent = !self.showContent
                        break;
                    }
                }
            })

        },
        components: {
            Modal,
            Taxonomy,
            draggable
        }
    }
</script>

<style scoped>
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

<style>
    .fa-arrows {
        cursor: move;
        font-size: 16px;
        margin-right: 8px;
    }

    .fa-cog, .fa-eye {
        cursor: pointer;
        font-size: 16px;
        margin-right: 8px;
    }

    .content-block {
        margin-bottom: 10px;
    }

    .content-block-header {
        padding: 10px;
        background: rgba(0,0,0, 0.1)
    }
</style>
