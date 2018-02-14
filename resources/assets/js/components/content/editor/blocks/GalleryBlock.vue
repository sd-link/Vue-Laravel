<template>
    <div class="content-block">
        <div v-show="showHeader" class="content-block-header">
            <i class="fa fa-arrows" aria-hidden="true"></i>
            <i class="fa fa-cog" @click="blockSettings()" aria-hidden="true"></i>
            {{ blockTitle }}
            <button v-on:click="removeBlock" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>

        <div class="content-block-body">
            <label v-show="!showHeader">{{ blockTitle }}</label>
            <div class="images" v-bind:style="{ 'grid-template-columns': templateColumns, 'grid-gap': spaceBetween }">
                <div v-for="image in images">
                    <img :src="image.grid_medium" :class="imgClass" v-bind:style="{'border': imageBorder +' solid '+imageBorderColor}">
                    <div style="clear: both;"></div>
                </div>
            </div>
            <!-- <dropzone
              id="testingDropZone" ref="dropzonetest"
              url="uploadUrl">

                <input type="hidden" name="token" value="xxx">
            </dropzone> -->
        </div>

        <modal ref="settingsModal" :title="modalTitle"
          v-model="showModal"
          v-bind="state.modalData"
          :draggable="true"
          :defaultWidth="modalDefaults.width"
          :defaultHeight="modalDefaults.height">
            <div class="modal-body" slot="modal-body">
                <div class="form-group">
                    <label for="">Block Title</label>
                    <input type="text" class="form-control" v-model="blockTitle">
                </div>

                <div class="form-group">
                    <label for="">Gallery</label>
                    <select class="form-control" v-model="galleriesSelect">
                        <option value='0'>
                            Pick a Gallery
                        </option>
                        <option v-for="gallery in galleries" v-bind:value="gallery.slug">
                            {{ gallery.title }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Preview Amount</label>
                    <input type="text" class="form-control" v-model="previewAmount">
                    <small>Type 0 to show all images, instead of above amount. This is only for admin.</small>
                </div>

                <div class="form-group">
                    <label for="">Columns</label>
                    <select class="form-control" v-model="imageColumns">
                        <option v-for="option in imageColumnsOptions" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Space Between</label>
                    <input type="text" class="form-control" v-model="spaceBetween">
                </div>

                <div class="form-group">
                    <label for="">Border</label>
                    <input type="text" class="form-control" v-model="imageBorder">
                </div>

                <div class="form-group">
                    <label for="">Border Color</label>
                    <div :id="'colorpicker-'+this._uid" class="input-group colorpicker-component">
                        <input type="text" v-model="imageBorderColor" class="form-control"/>
                        <span class="input-group-addon"><i></i></span>
                    </div>
                </div>


            </div>
            <div class="modal-footer" slot="modal-footer">
                <button type="button" class="btn btn-default" @click="cancelAction">Cancel</button>
                <button type="button" class="btn btn-primary" @click="saveAction">Save</button>
            </div>
        </modal>
    </div>
</template>

<script>
    import { EventBus } from 'components/eventbus.js'
    import Modal from 'components/ui/Modal.vue'
    import Dropzone from 'components/ui/Dropzone.vue'

    export default {
        data: function data() {
            return {
                uniqueId: this.id,
                showHeader: this.initShowHeader,
                modalDefaults: {
                    width: 600,
                    height: 522
                },
                state: {
                  modalData: {
                    top: 0,
                    left: 0,
                    width: null,
                    height: null
                  }
                },
                showModal: true,
                modalTitle: 'Gallery Settings',
                blockTitle: 'Gallery Block',
                spaceBetween: '0px',
                imageBorder: '0px',
                imageBorderColor: 'white',
                templateColumns: '1fr 1fr 1fr',
                imgClass: 'img-responsive img-center',
                allGalleriesUrl: '/backend/media/galleries/all',
                libraryLatestUrl: '/backend/media/galleries/images',
                order: 'newest',
                galleries: null,
                galleriesSelect: '0',
                gallery: '0',
                images: null,
                imageColumns: '3',
                previewAmount: 9,
                imageColumnsOptions: [
                    { text: '1', value: 1 },
                    { text: '2', value: 2 },
                    { text: '3', value: 3 },
                    { text: '4', value: 4 },
                    { text: '5', value: 5 },
                    { text: '6', value: 6 },
                ],
            }
        },
        props: {
            id: {type: String, default: null},
            initShowHeader: {type: Boolean, default: true}
        },
        watch: {
            initShowHeader (val, old) {
                if (val !== old) this.showHeader = val
            },
            previewAmount(val, old) {
                this.formData = {}
                console.log('val: ' + val)
                if(val != '') {
                    console.log('val: ' + val)
                    this.formData = {order: this.order, filter: 'galleries', search: this.gallery, limit: val}
                    this.loadImages()
                } else {
                    this.images = null
                }
            },
            imageColumns(val, old) {
                this.templateColumns = this.getTemplateColumns(val)
            },
            galleriesSelect(val, old) {
                this.formData = {}
                this.gallery = val
                if(val != '0') {
                    console.log('calling loadImages')
                    this.formData = {order: this.order, filter: 'galleries', search: val, limit: this.previewAmount}
                    this.loadImages()
                } else {
                    this.images = null
                }
            },

        },
        methods: {
            getTemplateColumns(imageColumns) {
                return "repeat("+imageColumns+",1fr)";
            },
            loadGalleries() {
                // console.log('loading galleries')
                axios.get(this.allGalleriesUrl)
                .then((response) => {
                    // console.log('loaded galleries')
                    this.galleries = response.data.galleries
                    // console.log(this.galleries)
                    // console.log(response.data.galleries)
                    // this.images = response.data.images.data
                    // console.log('total: ' + response.data.total)
                })
                .catch((error) => {
                    console.log(error)
                })
            },
            loadImages() {
                // console.log('loadImages')
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

                    console.log('images: ')
                    console.log(this.images)
                })
                .catch((error) => {
                    console.log(error)
                })
            },
            removeBlock() {
                this.$emit('content-block-remove', this.uniqueId);
            },
            saveAction() {
                // this.saveData()
                this.showModal = false
            },
            cancelAction() {
                this.showModal = false
            },
            blockSettings() {
                this.showModal = true
            }
        },
        mounted : function() {
            $('html, body').scrollTop( $(document).height() )

            console.log('mounted galleries')

            this.loadGalleries()
        },
        components: {
            Modal,
            Dropzone
        }
    }
</script>

<style scoped lang="scss">
    .content-block-body textarea {
        min-height: 150px;
    }

    .images {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }
</style>
