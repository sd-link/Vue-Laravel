<template>
    <div class="row">
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{modeText}} Gallery</h3>
                </div>
                <div class="box-body">
                    <div class="form-group title-group">
                        <label>Gallery Title</label>
                        <input v-model="galleryTitle"
                            v-validate="'required'"
                            :class="{'form-control':true, 'input': true, 'is-danger': errors.has('title') }"
                            name="title"
                            type="text"
                            placeholder="Enter title">
                        <span v-show="errors.has('title')" class="help is-danger">{{ errors.first('title') }}</span>
                        <!-- <small>No numbers, no special characters.</small> -->
                    </div>

                    <div v-show="enableDescription" class="form-group">
                        <label>Description</label>
                        <span v-if="editor=='markdown-editor'">
                            <markdown
                                uniqueId="markdown"
                                v-model="description">
                            </markdown>
                        </span>

                        <span v-if="editor=='vue-text-editor'">
                            <vue-editor id="textarea" v-model="description"></vue-editor>
                        </span>
                    </div>

                    <div class="form-group">
                        <dropzone
                          id="GalleryDropzone" ref="gallerydropzone"
                          :url="uploadUrl"
                          :headers="header"
                          v-on:vdropzone-sending="uploadStarted"
                          v-on:vdropzone-queue-complete="allUploaded"
                          v-on:vdropzone-success="fileUploaded"
                          v-on:vdropzone-error="fileNotUploaded"
                          v-bind:preview-template="template">
                            <!-- Optional parameters if any! -->
                            <input type="hidden" name="id" v-model="galleryId">
                        </dropzone>
                    </div>

                    <div id="images">
                        <div class="images">
                            <div class="image" v-for="(image, index) in imageList"
                                v-bind:style="{'display': 'flex', 'flex-direction': 'column', 'width': imageColumnWidth, 'margin': spaceBetween}">
                                <img :src="image.url" class="img-responsive img-center"
                                    v-bind:style="{'cursor': 'pointer'}">
                                <button style="color: black;" @click="removeImage(image)" type="button" class="remove-image" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </div>
                        <!-- <draggable
                            :move="onMove"
                            @end="onEnd"
                            v-model="imageList"
                            :options="{handle:'.fa-arrows', chosenClass:'dragging1'}"
                        >
                            <image-component v-for="image in imageList"
                                :uniqueId="image.uniqueId"
                                :dbid="image.dbid"
                                :init-image-title="image.title"
                                :init-image-caption="image.caption"
                                :order="image.order"
                                v-bind="image.settings"
                                :save-image="saveTaxonomies"
                                :init-show-header="showHeaders"
                                :init-show-content="showContent"
                                v-on:content-image-remove="removeImage"
                                v-on:content-image-save="contentTaxonomySave"
                                :key="image.uniqueId">
                            </image-component>
                        </draggable> -->
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Gallery Settings</h3>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        <label>Enable Description</label>
                        <select class="form-control" v-model="enableDescription">
                            <option v-bind:value='true'>Yes</option>
                            <option v-bind:value='false'>No</option>
                        </select>
                    </div>

                    <div v-show="enableDescription" class="form-group">
                        <label>Description Position</label>
                        <select class="form-control" v-model="descriptionPosition">
                            <option value='above'>Above Gallery</option>
                            <option value='below'>Below Gallery</option>
                        </select>
                        <small>Position of description on the frontend.</small>
                    </div>

                    <!-- <div class="form-group">
                        <label>Show Image Title</label>
                        <select class="form-control" v-model="displayImageTitle">
                            <option v-bind:value='true'>Yes</option>
                            <option v-bind:value='false'>No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Show Image Caption</label>
                        <select class="form-control" v-model="displayImageCaption">
                            <option v-bind:value='true'>Yes</option>
                            <option v-bind:value='false'>No</option>
                        </select>
                    </div> -->
                </div>

                <div class="box-footer">
                    <button @click="save()" :disabled='saving' style="width: 70px;" class="btn btn-primary pull-right" type="submit">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from 'components/eventbus.js'
    import { processSettings } from 'utils/helpers.js'
    import Modal from 'components/ui/Modal.vue'
    import draggable from 'vuedraggable'
    import Dropzone from 'components/ui/Dropzone.vue'

    import Markdown from 'components/ui/editors/markdown.vue'

    export default {
        data: function data() {
            return {
                showModal: false,
                imageCount: 1,
                imageList: [],
                galleryId: this.initId,
                galleryTitle: this.initTitle,
                mode: this.initMode,

                imageColumns: this.initImageColumns,
                spaceBetween: this.initSpaceBetween,
                enableDescription: this.initEnableDescription,
                descriptionPosition: this.initDescriptionPosition,

                description: this.initDescription,

                editor: this.initTextEditor,

                saving: false,

                uploadUrl: route('backend.media.galleries.upload'),

                header: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }
        },
        props: {
            blocks: {type: Array},
            initId: {type: Number, default: 0},
            initTitle: {type: String, default: ''},
            initMode: {type: String, default: 'create'},
            initTextEditor: {type: String, default: 'markdown-editor'},

            initImageColumns: {type: Number, default: 5},
            initSpaceBetween: {type: String, default: '1px'},
            initEnableDescription: {type: Boolean, default: false},
            initDescriptionPosition: {type: String, default: 'above'},
            initDescription: {type: String, default: ''},
        },
        computed: {
            imageColumnWidth: function () {
                let offset = this.spaceBetween.replace(/[^0-9.]/g,"") * 2
                return "calc(calc(100% / "+this.imageColumns+") - "+offset+"px)"
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
        },
        methods: {
            'allUploaded': function (files) {
                if(this.mode == 'create') {

                    return axios.get(route('backend.media.galleries.session.images'))
                        .then((response) => {
                            this.imageList = []
                            for (var i = 0; i < response.data.images.length; i++) {
                                this.initImage(response.data.images[i])
                            }
                        })
                        .catch((error) => {
                            console.log('we got error')
                            console.log(error)
                        })
                }
                else {
                    return axios.get(route('backend.media.galleries.images', {id: this.galleryId}))
                        .then((response) => {
                            this.imageList = []
                            for (var i = 0; i < response.data.images.length; i++) {
                                this.initImage(response.data.images[i])
                            }
                        })
                        .catch((error) => {
                            console.log('we got error')
                            console.log(error)
                        })
                }
            },
            'uploadStarted': function (files) {
                // this.filesAdded = true
            },
            'fileUploaded': function (file) {
                // this.uploaded = this.uploaded + 1
            },
            'fileNotUploaded': function (file) {
                // this.failed = this.failed + 1
            },
            'template':function() {
                return `
                        <div class="dz-preview dz-file-preview">
                            <div class="dz-image" style="width: 172px;height: 172px">
                                <img data-dz-thumbnail /></div>
                            <div class="dz-details">
                              <div class="dz-size"><span data-dz-size></span></div>
                              <div class="dz-filename"><span data-dz-name></span></div>
                            </div>
                            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                            <div class="dz-error-message"><span data-dz-errormessage></span></div>
                            <div class="dz-success-mark"><i class="fa fa-check"></i></div>
                            <div class="dz-error-mark"><i class="fa fa-close"></i></div>
                        </div>
                    `;
            },


            titleSettings() {
                this.showModal = true
            },
            saveAction() {
                // this.saveData()
                this.showModal = false
            },
            onEnd(evt) {
                for (var i = 0; i < this.images.length; i++) {
                    this.images[i].order = (i+1)
                }
            },
            onMove(evt) {
                return !evt.related.classList.contains('item_disabled')
            },
            save() {
                this.saving = true
                console.log('save method')

                let gallerySettings = {
                    imageColumns: {type: 'integer', value: this.imageColumns},
                    spaceBetween: {type: 'string', value: this.spaceBetween},
                    enableDescription: {type: 'string', value: this.enableDescription},
                    descriptionPosition: {type: 'string', value: this.descriptionPosition},
                }

                let self = this

                // let blocksData = { blocks: this.taxonomiesSettingsArray }
                // console.log(blocksData)
                let formData = {
                    id: this.galleryId,
                    title: this.galleryTitle,
                    description: this.description,
                    settings: gallerySettings,
                }

                return axios.post(route('backend.media.galleries.save'), formData)
                    .then((response) => {
                        this.saving = false
                        // update new blocks with database id
                        // for (var i = 0; i < this.imageList.length; i++) {
                        //     this.imageList[i].dbid = response.data.gallery.images[i].id
                        // }

                        // update url
                        if (history.pushState && this.galleryId == 0) {
                            console.log('updating url')
                            var path = window.location.pathname
                            path = path.replace(/create\b/g, "edit/")
                            var newurl = window.location.protocol + "//" + window.location.host + path + response.data.gallery.id
                            window.history.pushState({path:newurl},'',newurl)
                        }

                        this.galleryId = response.data.gallery.id

                        this.mode = 'edit'
                    })
                    .catch((error) => {
                        this.saving = false
                        console.log('we got error')
                        console.log(error)
                    })
            },
            initImage(image) {
                let newImage = new Object()
                newImage.uniqueId = 'image-' + this.imageList.length
                newImage.id = image.id
                newImage.title = image.title
                newImage.caption = image.caption
                newImage.url = image.thumb
                this.imageList.push(newImage)
            },
            removeImage(imageToRemove) {
                let formData = {
                    id: imageToRemove.id
                }
                return axios.post(route('backend.media.images.delete'), formData)
                    .then((response) => {
                        this.imageList = $.grep(this.imageList, function(image){
                            return image.uniqueId != imageToRemove.uniqueId;
                        })

                        for (var i = 0; i < this.imageList.length; i++) {
                            this.imageList[i].uniqueId = 'image-' + i
                        }
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            },
        },
        mounted: function() {
            let self = this

            if(this.mode == 'edit' && this.galleryId) {
                return axios.get(route('backend.media.galleries.images', {id: this.galleryId}))
                    .then((response) => {
                        for (var i = 0; i < response.data.images.length; i++) {
                            this.initImage(response.data.images[i])
                        }
                    })
                    .catch((error) => {
                        console.log('we got error')
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

        },
        components: {
            Modal,
            Dropzone,
            Markdown,
            VueEditor,
            draggable
        }
    }
</script>

<style scoped lang="less">
    .images {
        display: flex;
        flex-wrap: wrap;
        flex-direction: row;
    }

    .remove-image {
        padding: 0;
        border: 0;
        position: absolute;
        right: 5px;
        top: 10px;
        opacity: 0;
    }

    .remove-image span {
        opacity: 1;
        background: rgb(151, 26, 26);
        padding: 0px 7px;
        font-size: 18px;
        border: 0px;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-shadow: 0 0 0;
    }

    .image {
        display: inline-block;
        position: relative;

        &:hover {
            .remove-image {
                opacity: 1;
                background: transparent;
            }
        }
    }
</style>
