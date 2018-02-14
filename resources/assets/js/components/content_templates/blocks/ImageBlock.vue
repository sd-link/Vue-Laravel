<template>
    <div class="content-block">
        <div v-show="showHeaders" class="content-block-header">
            <i class="fa fa-arrows" aria-hidden="true"></i>
            <i class="fa fa-cog" @click="blockSettings()" aria-hidden="true"></i>
            {{ settings.blockTitle }}
            <button v-on:click="removeBlock" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>

        <div v-show="showContent" class="image-block-body" style="display: flex; flex-direction: column;">
            <label v-show="!showHeaders && !containerPreview">{{ settings.blockTitle }}</label>
                <input v-if="settings.imageTitleEnabled"
                    v-model='image.title'
                    class="form-control image-title"
                    :class="[settings.imageTitleClass]"
                    v-bind:style="{' margin-bottom': '2px', 'order': settings.imageTitlePosition }"
                    type="text"
                    placeholder="Enter Image Title">

                <div style="order: 2;">
                    <img v-if="image.id" :src="imageUrl" @click="openMediaModal" :class="settings.imageClass">
                    <div v-else @click="openMediaModal" class="text-center no-image">
                        <h3 style="height: 30px;">Image</h3>
                    </div>
                    <!-- <img src="/images/default_single.jpg" :class="settings.imageClass"> -->
                </div>

                <textarea v-if="settings.imageCaptionEnabled"
                    v-model='image.caption'
                    class="form-control image-caption"
                    :class="settings.imageCaptionClass"
                    style="margin-top: 2px; order: 4;"
                    placeholder="Enter Image Caption">
                </textarea>
        </div>

        <modal ref="settingsModal" :title="modalTitle"
            v-model="showModal"
            v-bind="state.modalData"
            :draggable="true"
            :defaultWidth="modalDefaults.width"
            :defaultHeight="modalDefaults.height"
        >
            <div class="modal-body" slot="modal-body">
                <div class="form-group">
                    <label>Block Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
                </div>

                <div class="form-group">
                    <label>Size</label>
                    <select class="form-control" v-model="settings.imageSize">
                        <option v-for="option in imageSizeOptions" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Image Class</label>
                    <input type="text" class="form-control" v-model="settings.imageClass">
                </div>

                <div class="form-group">
                    <label>Show Image Title</label>
                    <select class="form-control" v-model="settings.imageTitleEnabled">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>

                <div v-show="settings.imageTitleEnabled" class="form-group">
                    <label>Image Title Size</label>
                    <input type="text" class="form-control" v-model="settings.imageTitleClass"/>
                </div>

                <div v-show="settings.imageTitleEnabled" class="form-group">
                    <label>Image Title Position</label>
                    <select class="form-control" v-model="settings.imageTitlePosition">
                        <option value='1'>Above</option>
                        <option value='3'>Below</option>
                    </select>
                </div>


                <div class="form-group">
                    <label>Show Image Caption</label>
                    <select class="form-control" v-model="settings.imageCaptionEnabled">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>

                <div v-show="settings.imageCaptionEnabled" class="form-group">
                    <label>Image Caption Position</label>
                    <input type="text" class="form-control" v-model="settings.imageCaptionClass"/>
                </div>

            </div>
            <div class="modal-footer" slot="modal-footer">
                <button type="button" class="btn btn-primary" @click="closeModalAction">Close</button>
            </div>
        </modal>
    </div>
</template>

<script>
    import GeneralMixin from '../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'
    import Modal from 'components/ui/Modal.vue'

    export default {
        components: {
            Modal,
        },
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Image'},
            imageTitleEnabled: {type: Boolean, default: false},
            imageTitleClass: {type: String, default: 'h2'},
            imageTitlePosition: {type: String, default: '1'},
            showLabel: {type: Boolean, default: true},

            imageCaptionEnabled: {type: Boolean, default: false},
            imageCaptionClass: {type: String, default: 'caption'},

            imageSize: {type: String, default: 'original'},
            imageClass: {type: String, default: 'img-responsive img-center'},
        },
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
                    height: 580
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
                image: {
                    title: '',
                    caption: '',
                },
                imageSizeOptions: [
                    { text: 'Original', value: 'original' },
                    { text: 'Large', value: 'large' },
                    { text: 'Medium', value: 'medium' },
                    { text: 'Grid Large', value: 'grid_large' },
                    { text: 'Grid Medium', value: 'grid_medium' },
                    { text: 'Thumbnail Large', value: 'thumb_large' },
                    { text: 'Thumbnail Small', value: 'thumb' },
                ],
            }
        },
        computed: {
            modalTitle: function () {
                return this.settings.blockTitle + ' Settings'
            },
            settings () {
                return this.$store.getters.itemSettings(this.uniqueId)
            },
            subItems () {
                return this.$store.getters.items(this.uniqueId)
            },
            content () {
                return this.$store.getters.itemContent(this.uniqueId)
            },
            imageUrl() {
                return this.getCorrectImageSize()
            }
        },
        watch: {
            image: {
                handler(val) {
                    console.log(val)
                    this.updateItemContent({content: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
            settings: {
                handler(val) {
                    this.updateItemSettings({settings: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
        },
        methods: {
            ...mapActions([
                'updateItemSettings',
                'updateItemContent',
            ]),
            openMediaModal() {
                var mode = 'insertImage'
                var params = new Object
                // params.imageId = this.imageId
                params.id = this._uid

                params.cb = (image) => {
                    let tmpImg = new Object()
                    tmpImg.title = ''
                    tmpImg.caption = ''
                    tmpImg.id = image.id
                    tmpImg.filename = image.filename
                    tmpImg.extension = image.extension
                    tmpImg.path = image.path

                    this.image = tmpImg
                }

                this.$bus.$emit('open-media-modal', mode, params)
            },
            getCorrectImageSize() {
                if(this.image.id) {
                    switch (this.settings.imageSize) {
                        case 'original':
                            return '/' + this.image.path + this.image.filename + '.' + this.image.extension
                        break;

                        case 'large':
                            return '/' + this.image.path + this.image.filename + '_large.' + this.image.extension
                        break;

                        case 'medium':
                            return '/' + this.image.path + this.image.filename + '_medium.' + this.image.extension
                        break;

                        case 'grid_large':
                            return '/' + this.image.path + this.image.filename + '_grid_large.' + this.image.extension
                        break;

                        case 'grid_medium':
                            return '/' + this.image.path + this.image.filename + '_grid_medium.' + this.image.extension
                        break;

                        case 'thumb_large':
                            return '/' + this.image.path + this.image.filename + '_thumb_large.' + this.image.extension
                        break;

                        case 'thumb':
                            return '/' + this.image.path + this.image.filename + '_thumb.' + this.image.extension
                        break;

                        default:
                            return '/' + this.image.path + this.image.filename + '_grid_medium.' + this.image.extension
                    }
                }
            },
            removeBlock() {
                this.$emit('remove');
            },
            closeModalAction() {
                // this.saveData()
                this.showModal = false
            },
            blockSettings() {
                this.showModal = true
            },
            processContent(content) {
                this.image = new Object()
                this.image.title = content.title
                this.image.caption = content.caption
                this.image.url = content.url
                this.image.id = content.id
                this.image.path = content.path
                this.image.filename = content.filename
                this.image.extension = content.extension
            }
        },
        mounted : function() {
            if(this.content)
                this.processContent(JSON.parse(this.content))
        }
    }
</script>

<style scoped lang="less">
    .h1, h1, .h2, h2, .h3, h3, .h4, h4 {
        height: 50px;
    }

    .no-image {
        padding: 20px;
        border: 1px dashed rgba(0, 0, 0, 0.2);
        background-color: rgba(0,0,0, 0.12);
        cursor: pointer;
    }

    img {
        cursor: pointer;
    }

    .content-block {
        textarea {
           resize: none;
           height: 60px;
        }
    }
</style>
