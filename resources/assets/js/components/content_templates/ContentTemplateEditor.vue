<template>
    <div id="templateEditor" class="row">
        <div :class="(this.wideLayout == true) ? 'col-md-12' : 'col-md-9'">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{modeText}} Content Template</h3>
                    <button v-if="this.wideLayout" @click="saveBtn()" :disabled='saveTemplateBlocks' style="width: 70px;" class="btn btn-primary pull-right" type="submit">{{saveBtnText}}</button>
                </div>
                <div class="box-body">
                    <div class="form-group title-group">
                        <label for="title">Name</label>
                        <input v-model="templateName"
                            v-validate="'required'"
                            :class="{'form-control':true, 'input': true, 'is-danger': errors.has('name') }"
                            name="name"
                            type="text"
                            placeholder="Enter name">
                        <span v-show="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>
                    </div>

                    <div class="form-group type-group">
                        <label>Make Default</label>
                        <select v-model="defaultTemplate" v-validate="'required'" name="default" :class="{'form-control':true, 'input': true, 'is-danger': errors.has('default') }">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                        </select>
                        <span v-show="errors.has('default')" class="help is-danger">{{ errors.first('default') }}</span>
                    </div>

                    <div v-if="defaultTemplate" class="form-group type-group">
                        <label>Content Type</label>
                        <select v-model="contentTypeId" class="form-control">
                            <option value=0>Select Content Type</option>
                            <option v-for="contentType in contentTypes" :value="contentType.id">{{ contentType.name }}</option>
                        </select>
                        <span>Select content type this template is default for.</span>
                    </div>

                    <div id="blocks">
                        <draggable
                            :move="onMove"
                            @end="onEnd"
                            v-model="containerList"
                            :options="{handle:'.fa-arrows', chosenClass:'dragging1'}"
                            style="min-height: 30px;">
                            <component v-for="container in containerList"
                                v-bind:is="container.type"
                                :uniqueId="container.uniqueId"
                                v-bind="container.settings"
                                :show-headers="showHeaders"
                                :show-content="showContent"
                                v-on:remove="removeContainer(container.uniqueId)"
                                :key="container.uniqueId">
                            </component>
                        </draggable>
                    </div>

                    <div id="buttons">
                        <!-- <button v-on:click="addBlock('taxonomy-block-template')" type="button" name="button" class="btn btn-primary btn-sm">Add Taxonomy</button> -->
                        <button @click="addContainer('ContainerBlockTemplate')" type="button" name="button" class="btn btn-primary btn-sm">Add Container</button>

                        <!-- <button v-on:click="addBlock('video')" type="button" name="button" class="btn btn-primary btn-sm">Add Video</button> -->
                        <!-- <button v-on:click="addBlock('gallery')" type="button" name="button" class="btn btn-primary btn-sm">Add Gallery</button> -->
                    </div>
                </div>
            </div>
        </div>

        <div v-if="!wideLayout" class="col-md-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Save Options</h3>
                    <i class="fa fa-info-circle pull-right" style="cursor: pointer;" aria-hidden="true"></i>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        <button @click="saveBtn()" :disabled='saveTemplateBlocks' style="width: 70px;" class="btn btn-primary pull-right" type="submit">{{saveBtnText}}</button>
                    </div>
                </div>

            </div>
        </div>
        <content-blocks-popup></content-blocks-popup>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import { processSettings, processSettingsConfig, getComponentByName } from 'utils/helpers.js'
    import draggable from 'vuedraggable'
    import Modal from 'components/ui/Modal.vue'
    import ContentBlocksPopup from 'components/ui/ContentBlocksPopup.vue'

    export default {
        components: {
            Modal,
            ContentBlocksPopup,
            draggable
        },
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
                contentTypes: this.initContentTypes,

                showHeaders: true,
                showContent: true,

                saveTemplateBlocks: false,

                wideLayout: this.initWideLayout,
                minimalLayout: false,
            }
        },
        props: {
            blocks: {type: Array},
            initTemplateId: {type: Number, default: 0},
            initTemplateName: {type: String, default: ''},
            initEditorMode: {type: String, default: 'create'},
            initContentTypes: {type: Array, default: null},
            initContentTypeId: {type: Number, default: 0},
            initWideLayout: {type: Boolean, default: false},
            initDefaultTemplate: {type: Boolean, default: false}
        },
        computed: {
            containerList: {
                get() {
                    return this.$store.getters.rootItems
                },
                set(object) {
                    // console.log('set order for blocks', object)
                    this.updateItemsList({list: _.map(object, 'uniqueId')})
                }
            },
            editorMode: {
                get() {
                    return this.$store.getters.editorMode
                },
                set(value) {
                    this.setEditorMode(value)
                }
            },
            templateId: {
                get() {
                    return this.$store.getters.templateId
                },
                set(value) {
                    this.setTemplateId(value)
                }
            },
            templateName: {
                get() {
                    return this.$store.getters.templateName
                },
                set(value) {
                    this.setTemplateName(value)
                }
            },
            defaultTemplate: {
                get() {
                    return this.$store.getters.templateData.defaultTemplate
                },
                set(value) {
                    let payload = new Object
                    payload.defaultTemplate = value
                    this.setTemplateData(payload)
                }
            },
            contentTypeId: {
                get() {
                    return this.$store.getters.contentTypeId
                },
                set(value) {
                    this.setContentTypeId(value)
                }
            },
            saveBtnText: function() {
                if(this.saveTemplateBlocks)
                    return 'Saving'
                else {
                    return 'Save'
                }
            },
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
            showHeaders(val, old) {
                console.log('editor showHeaders: ' + val)
            },
            showContent(val, old) {
                console.log('editor showContent: ' + val)
            },
        },
        methods: {
            ...mapActions([
                'addItem',
                'removeItem',
                'setContentTypeId',
                'setEditorMode',
                'setTemplateData',
                'setTemplateName',
                'setTemplateId',
                'updateItemsList',
                'fetchAll'
            ]),
            addContainer(compName = undefined) {
                let comp = getComponentByName(compName)
                if (!comp) {
                    alert('Wrong component\'s name')
                    return false
                }

                let customSettings = processSettingsConfig(compName)

                this.addItem({
                    type: compName,
                    settingsConfig: customSettings,
                    settings: _.mapValues(customSettings, object => (object.default === undefined)? null : object.default)
                })
            },
            titleSettings() {
                this.showModal = true
            },
            onEnd(evt) {

            },
            onMove(evt) {
                return !evt.related.classList.contains('item_disabled')
            },
            saveBtn() {
                this.$validator.validateAll().then( result => {
                    if (result) {
                        this.save()
                    }
                })
            },
            save() {
                this.saveTemplateBlocks = false
                this.$store.dispatch('saveAll')
            },
            removeContainer(containerId) {
                this.removeItem({
                    id: containerId
                })
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

            if (this.initEditorMode == 'edit') {
                this.setEditorMode('edit')
                let payload = new Object()
                payload.templateId = this.initTemplateId
                payload.templateName = this.initTemplateName
                payload.contentTypeId = this.initContentTypeId
                payload.defaultTemplate = this.initDefaultTemplate
                this.setTemplateData(payload)
                this.fetchAll({templateId: this.initTemplateId})
            }

            // window.addEventListener("popstate", function(e) {
            //     var path = location.pathname
            //     path = path.replace(/create\b/g, "")
            //     // console.log('path: ' + )
            //     console.log('pop state')
            //     window.location = window.location.protocol + "//" + window.location.host + path
            //
            // })

            window.addEventListener('keydown', function(event) {
                let key = event.keyCode
                // console.log(key)
                if(event.ctrlKey) {
                    switch (key) {
                        // char M pressed switch to minimal layout
                        case 77:
                            event.preventDefault()
                            self.minimalLayout = !self.minimalLayout
                        break;
                        // char L pressed switch to wide layout
                        case 76:
                            event.preventDefault()
                            self.wideLayout = !self.wideLayout

                            let formData = {
                                wideLayout: {type: 'boolean', value: self.wideLayout}
                            }

                            axios.post(route('backend.content.templates.settings.save'), formData)

                        break;
                        case 72:
                            event.preventDefault()
                            console.log('headers flipped')
                            self.showHeaders = !self.showHeaders
                        break;
                        // char G pressed
                        case 71:
                            // console.log('hide content')
                            event.preventDefault()
                            console.log('content flipped')
                            self.showContent = !self.showContent
                        break;
                    }
                }
            })

        },
    }
</script>

<style>
    .fa-arrows, .fa-cog, .fa-eye, .fa-columns, .fa-eye-slash, .fa-caret-square-o-down, .fa-times, .fa-arrow-circle-o-down, .fa-arrow-circle-o-up {
        cursor: pointer;
        font-size: 16px;
        margin-right: 8px;
        color: #b8c7ce !important;
    }

    .fa-arrows {
        cursor: move;
    }

    .content-block {
        margin-bottom: 10px;
    }

    .content-block-header {
        color: #b8c7ce !important;
        padding: 10px;
        background: rgba(0,0,0, 0.1)
    }


</style>
