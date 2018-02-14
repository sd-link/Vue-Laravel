<template>
    <div id="medialib">
        <modal ref="settingsModal" :title="modalTitle"
            v-model="showModal"
            v-bind="state.modalData"
            :large="true"
            :defaultWidth="modalDefaults.width"
            :defaultHeight="modalDefaults.height">

            <div class="modal-body" slot="modal-body">
                <div>
                    <!-- <button type="button" class="btn btn-default" @click="activeTab = 0">Set 1 tab</button>
                    <button type="button" class="btn btn-default" @click="activeTab = 1">Set 2 tab</button>
                    <br>
                    <br> -->
                    <tabs v-model="activeTab" @active="handleTabChange" nav-style="tabs">
                        <tab header="Library">
                            <div class="search" style="margin: 10px; margin-left: 0px;">
                                <select class="form-control" v-model="order">
                                    <option v-for="option in orderOptions" v-bind:value="option.value">
                                        {{ option.text }}
                                    </option>
                                </select>

                                <select class="form-control" v-model="galleriesDefault">
                                    <option v-for="option in galleriesOptions" v-bind:value="option.value">
                                        {{ option.text }}
                                    </option>
                                    <option v-for="gallery in galleries" v-bind:value="gallery.slug">
                                        {{ gallery.title }}
                                    </option>
                                </select>

                                <select class="form-control" v-model="galleryTagsDefault">
                                    <option v-for="option in galleryTagsOptions" v-bind:value="option.value">
                                        {{ option.text }}
                                    </option>
                                    <option v-for="tag in galleryTags" v-bind:value="tag.slug">
                                        {{ tag.title }}
                                    </option>
                                </select>

                                <!-- <select class="form-control" v-model="imageTagsDefault">
                                    <option v-for="option in imageTagsOptions" v-bind:value="option.value">
                                        {{ option.text }}
                                    </option>
                                    <option v-for="tag in imageTags" v-bind:value="tag.slug">
                                        {{ tag.title }}
                                    </option>
                                </select> -->

                                <div style="clear: both;">
                                </div>
                            </div>
                            <div class="images">
                                <div class="image" v-for="image in images" :key="image.key">
                                    <img @click="selectImage(image.id)" :src="image.thumb" v-bind:class="['img-responsive', { 'selected': selectedImage == image.id }]">
                                </div>

                                <div style="clear: both;">

                                </div>
                            </div>
                        </tab>
                        <tab header="Upload">
                            <dropzone
                              id="MediaLibraryDropzone" ref="dropzonetest"
                              :url="uploadUrl"
                              :headers="header"
                              height="495px"
                              v-on:vdropzone-sending="uploadStarted"
                              v-on:vdropzone-queue-complete="allUploaded"
                              v-on:vdropzone-success="fileUploaded"
                              v-on:vdropzone-error="fileNotUploaded"
                              v-bind:preview-template="template">
                            <input type="hidden" name="token" value="xxx">
                            </dropzone>
                        </tab>
                    </tabs>
                    <div v-if="filesAdded">
                        <span>Uploaded: {{ this.uploaded }}</span> <span v-if="failed > 0">Failed: {{ this.failed }}</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer" slot="modal-footer">
                <button type="button" class="btn btn-primary" @click="insertImage" :disabled="selectedImage == null ? true : false">Insert Image</button>
                <button type="button" class="btn btn-default" @click="cancel">Cancel</button>
            </div>
        </modal>
    </div>
</template>

<script>
    import Vue from 'vue'
    import Modal from 'components/ui/Modal.vue'
    import Tabs from 'components/ui/Tabs.vue'
    import Tab from 'components/ui/Tab.vue'
    import Dropzone from 'components/ui/Dropzone.vue'

    // import { EventBus } from 'components/eventbus.js'

    export default {
        data: function data() {
            return {
                modalDefaults: {
                  width: '84%',
                  height: 720
                },
                tempData: {
                    video: null
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
                modalTitle: 'Media Library',
                mode: null,
                activeTab: 0,
                uploadUrl: this.initUploadUrl,
                libraryLatestUrl: '/backend/media/latest',
                allGalleriesUrl: '/backend/media/galleries/all',
                allGalleryTagsUrl: '/backend/media/tags/gallery/all',
                allImageTagsUrl: '/backend/media/tags/image/all',
                imageId: null,
                selectedImage: null,
                images: null,
                order: 'newest',
                orderOptions: [
                    { text: 'Newest', value: 'newest' },
                    { text: 'Oldest', value: 'oldest' }
                ],
                galleries: null,
                galleriesDefault: 'all',
                galleriesOptions: [
                    { text: 'All Galleries', value: 'all' }
                ],
                galleryTags: null,
                galleryTagsDefault: 'all',
                galleryTagsOptions: [
                    { text: 'All Gallery Tags', value: 'all' }
                ],
                imageTags: null,
                imageTagsDefault: 'all',
                imageTagsOptions: [
                    { text: 'All Image Tags', value: 'all' }
                ],
                formData: {},
                filter: null,
                search: null,
                filesAdded: false,
                uploaded: 0,
                failed: 0,
                header: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                callingComponentId: null,
                callback: null
            }
        },
        props: {
            initUploadUrl: {type: String, default: '/backend/media/upload'},
        },
        computed: {

        },
        watch: {
            showModal(val, old) {
                if(!val && old) {
                    let currentModalPosition = this.$refs.settingsModal.currentPositionData
                    this.state.modalData = Object.assign({}, this.state.modalData, currentModalPosition)
                }
            },
            order(val, old) {
                this.formData = {}
                this.formData = {order: val, filter: this.filter, search: this.search}
                this.loadLibrary()
            },
            galleriesDefault(val, old) {
                this.formData = {}
                if(val != 'all') {
                    this.galleryTagsDefault = 'all'
                    this.imageTagsDefault = 'all'
                    this.formData = {order: this.order, filter: 'galleries', search: val}
                    this.loadLibrary()
                } else if (val == 'all' && this.galleryTagsDefault == 'all' && this.imageTagsDefault == 'all') {
                    this.loadLibrary()
                }
            },
            galleryTagsDefault(val, old) {
                this.formData = {}
                if(val != 'all') {
                    this.galleriesDefault = 'all'
                    this.imageTagsDefault = 'all'
                    this.formData = {order: this.order, filter: 'gallerytag', search: val}
                    this.loadLibrary()
                } else if (val == 'all' && this.galleriesDefault == 'all' && this.imageTagsDefault == 'all') {
                    this.loadLibrary()
                }
            },
            imageTagsDefault(val, old) {
                this.formData = {}
                if(val != 'all') {
                    this.galleriesDefault = 'all'
                    this.galleryTagsDefault = 'all'
                    this.formData = {order: this.order, filter: 'imagetag', search: val}
                    this.loadLibrary()
                } else if (val == 'all' && this.galleriesDefault == 'all' && this.galleryTagsDefault == 'all') {
                    this.loadLibrary()
                }
            }
        },
        methods: {
            'allUploaded': function (file) {
            //   console.log('All files were successfully uploaded')
              if(this.failed == 0)
                this.activeTab = 0
            },
            'uploadStarted': function (files) {
                this.filesAdded = true
            },
            'fileUploaded': function (file) {
                this.uploaded = this.uploaded + 1
                // console.log('file uploaded')
            },
            'fileNotUploaded': function (file) {
                this.failed = this.failed + 1
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
            selectImage(imageId) {
                this.selectedImage = imageId
            },
            insertImage() {
                this.showModal = false
                if (this.callback) this.callback( _.find(this.images, {id: this.selectedImage}) )
            },
            cancel() {
                // console.log('are we here?')
                this.showModal = false
            },
            openedAction () {
                // console.log('Opened Action')
            },
            closedAction () {
                // console.log('Closed Action')
            },
            handleTabChange (tabIndex) {
                if(tabIndex == 0) {
                    // this.failed = 0
                    this.$refs.dropzonetest.removeAllFiles()
                    this.loadLibrary()
                }
                if(tabIndex == 1) {
                    this.uploaded = 0
                    this.failed = 0
                    this.filesAdded = false
                }
            },
            loadLibrary() {
                // this.selectedImage = this.imageId
                // let formData = {imageId: this.imageId, filter: 'galleries'}
                axios.get(this.libraryLatestUrl, {params: this.formData})
                .then((response) => {
                    this.filter = response.data.append.filter
                    this.search = response.data.append.search
                    if(response.data.images)
                        this.images = response.data.images.data
                    else
                        this.images = null

                    // console.log('total: ' + response.data.total)
                })
                .catch((error) => {
                    console.log(error)
                })
            },
            loadGalleries() {
                axios.get(this.allGalleriesUrl)
                .then((response) => {
                    this.galleries = response.data.galleries
                    // console.log(response.data.galleries)
                    // this.images = response.data.images.data
                    // console.log('total: ' + response.data.total)
                })
                .catch((error) => {
                    console.log(error)
                })
            },
            loadGalleryTags() {
                axios.get(this.allGalleryTagsUrl)
                .then((response) => {
                    this.galleryTags = response.data.tags
                    // console.log(response.data.tags)
                    // this.images = response.data.images.data
                    // console.log('total: ' + response.data.total)
                })
                .catch((error) => {
                    console.log(error)
                })
            },
            loadImageTags() {
                // console.log('loading image tags')
                axios.get(this.allImageTagsUrl)
                .then((response) => {
                    this.imageTags = response.data.tags
                    // console.log(response.data.tags)
                })
                .catch((error) => {
                    console.log(error)
                })
            }
        },
        mounted : function() {
            // console.log('token:' + $('meta[name="csrf-token"]').attr('content'))
            // this.token = $('meta[name="csrf-token"]').attr('content')
            // console.log('lib token: ' + this.token)
            let self = this
            self.loadLibrary() // preload library images
            // self.loadGalleries()
            // self.loadGalleryTags()
            // self.loadImageTags()

            this.$bus.$on('open-media-modal', function(mode, params) {
                self.mode = mode
                self.showModal = true
                self.selectImage(params.imageId)
                self.callback = (params.cb) ? params.cb : null
                // self.callingComponentId = params.id
            })
        },
        components: {
            Modal,
            Tabs,
            Tab,
            Dropzone
        }
    }
</script>

<style scoped lang="scss">

    .nav-tabs-custom {
        margin-bottom: 5px;
    }

    .modal-body {
        overflow: hidden;
    }

    select {
        float: left;
        width: 150px;
        margin-right: 5px;
    }

    .images {
        // display: flex;
        width: 100%;
        overflow-y: auto;
        height: 450px;
        // flex-wrap: wrap;
    }

    .image {
        width: 140px;
        padding: 1px;
        float: left;
    }

    .selected {
        border: 2px solid #00c0ef;
        padding: 0px;
    }
</style>
