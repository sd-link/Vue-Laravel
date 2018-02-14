<template>
    <div class="content-block">
        <div v-show="showHeaders" class="content-block-header">
            <i class="fa fa-arrows" style="cursor: move; font-size: 16px; margin-right: 8px;" aria-hidden="true"></i>
            <i class="fa fa-cog" @click="blockSettings()" style="cursor: pointer; font-size: 16px; margin-right: 8px;" aria-hidden="true"></i>
            {{ settings.blockTitle }}
            <button v-on:click="removeBlock" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>

        <div v-show="showContent" class="content-block-body">
            <div class="form-group">
                <label v-show="!showHeaders && !containerPreview">{{ settings.blockTitle }}</label>
                <input v-show="settings.imagesTitleEnabled" type="text" class="form-control" :class="settings.imagesTitleClass">
                <div class="images" v-bind:style="{'margin-left': '-'+settings.spaceBetweenImages, 'margin-right': '-'+settings.spaceBetweenImages}">
                    <div class="image" v-for="(image, index) in imageList"
                    v-bind:style="{'display': 'flex',
                                    'flex-direction': 'column',
                                    'border': '1px dashed rgba(0,0,0, 0.2)',
                                    'justify-content': 'center',
                                    'width': imageWidth,
                                    'margin': settings.spaceBetweenImages,
                                    'background-color': settings.imageBackgroundColor,
                                    'padding': settings.imagePadding}">
                        <input v-if="settings.imageTitleEnabled"
                            v-model="image.title"
                            class="form-control image-title"
                            :class="settings.imageTitleClass"
                            v-bind:style="{'margin-bottom': '2px', 'order': settings.imageTitlePosition, 'background-color': inputBackground }"
                            type="text"
                            placeholder="Enter Image Title">

                        <img v-if="image.id" @click="openMediaModal(image.uniqueId)" :src="imageUrl(image)" :class="settings.imageClass"
                            v-bind:style="{
                                'cursor': 'pointer',
                                'order': '2',
                                'cursor': 'pointer',
                                'border': settings.imageOutline +' solid '+settings.imageOutlineColor,
                                'padding': settings.imageBorder,
                                'background-color': settings.imageBorderColor}">

                        <div v-else @click="openMediaModal(image.uniqueId)" class="text-center"
                            style="cursor: pointer; display: flex; flex-direction: column; justify-content: center; height: 100%; order: 2;"
                            v-bind:style="{
                                'background-color': 'rgba(0,0,0, 0.15)',
                                'padding': noImagePadding}"
                        >
                            <h3 style="height: 30px;">Image</h3>
                        </div>

                        <textarea v-if="settings.imageCaptionEnabled"
                            v-model="image.caption"
                            class="form-control image-caption"
                            :class="settings.imageCaptionClass"
                            v-bind:style="{'margin-top': '2px', 'order': '4', 'background-color': inputBackground }"
                            placeholder="Enter Caption">
                        </textarea>
                    </div>
                </div>
                <div style="clear: both;"></div>
                <div v-if="settings.canAddImages" class="text-center" style="margin: 2px 0;">
                    <button @click="addImage()" class="btn btn-primary btw-add-image" type="button" name="button">Add New Image</button>
                </div>
                <textarea v-show="settings.imagesCaptionEnabled"
                    class="form-control"
                    v-bind:style="{'margin-top': '2px', 'background-color': inputBackground }"
                    placeholder="Enter Caption" >
                </textarea>
            </div>
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
                    <div @click="blockSettingsOpen=!blockSettingsOpen" style="cursor: pointer; background: rgba(0,0,0, 0.2); padding: 5px 12px;">
                        <label style="margin: 0;">Block Settings</label>
                        <span class="pull-right">
                            <i v-if="!blockSettingsOpen" class="fa fa-arrow-circle-o-down" title="Show Css Settings" aria-hidden="true"></i>
                            <i v-else class="fa fa-arrow-circle-o-up" title="Hide Css Settings" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div v-if="blockSettingsOpen" class="form-sub-group">
                        <div class="form-group">
                            <label>Images Title Enabled</label>
                            <select class="form-control" v-model="settings.imagesTitleEnabled">
                                <option v-bind:value='true'>Yes</option>
                                <option v-bind:value='false'>No</option>
                            </select>
                            <small>If this images group title is rendered on frontend.</small>
                        </div>

                        <div v-show="settings.imagesTitleEnabled" class="form-group">
                            <label>Images Title Class</label>
                            <input type="text" class="form-control" v-model="settings.imagesTitleClass">
                        </div>

                        <div class="form-group">
                            <label>Images Caption Enabled</label>
                            <select class="form-control" v-model="settings.imagesCaptionEnabled">
                                <option v-bind:value='true'>Yes</option>
                                <option v-bind:value='false'>No</option>
                            </select>
                            <small>If this images group caption is rendered on frontend.</small>
                        </div>

                        <div v-show="settings.imagesCaptionEnabled" class="form-group">
                            <label>Caption Class</label>
                            <input type="text" class="form-control" v-model="settings.blockCaptionClass">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div @click="imagesSettingsOpen=!imagesSettingsOpen" style="cursor: pointer; background: rgba(0,0,0, 0.2); padding: 5px 12px;">
                        <label style="margin: 0;">Images Settings</label>
                        <span class="pull-right">
                            <i v-if="!imagesSettingsOpen" class="fa fa-arrow-circle-o-down" title="Show Css Settings" aria-hidden="true"></i>
                            <i v-else class="fa fa-arrow-circle-o-up" title="Hide Css Settings" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div v-if="imagesSettingsOpen" class="form-sub-group">
                        <div class="form-group">
                            <label>Image Size</label>
                            <select class="form-control" v-model="settings.imageSize">
                                <option v-for="option in imageSizeOptions" v-bind:value="option.value">
                                    {{ option.text }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Images</label>
                            <el-input-number v-model="settings.imageAmount" :min="minImages" :max="maxImages"></el-input-number>
                        </div>

                        <div class="form-group">
                            <label>Columns</label>
                            <el-input-number v-model="settings.columnsPerRow" :min="1" :max="6"></el-input-number>
                        </div>

                        <div class="form-group">
                            <label>Allow Adding New Images</label>
                            <select class="form-control" v-model="settings.canAddImages">
                                <option v-bind:value='true'>Yes</option>
                                <option v-bind:value='false'>No</option>
                            </select>
                            <small>If user should be able to add new images when editing this block.</small>
                        </div>

                        <div class="form-group">
                            <label>Show Image Title</label>
                            <select class="form-control" v-model="settings.imageTitleEnabled">
                                <option v-bind:value='true'>Yes</option>
                                <option v-bind:value='false'>No</option>
                            </select>
                        </div>

                        <div v-show="settings.imageTitleEnabled" class="form-group">
                            <label>Image Title Class</label>
                            <input type="text" class="form-control" v-model="settings.imageTitleClass">
                        </div>

                        <div v-show="settings.imageTitleEnabled" class="form-group">
                            <label>Image Title Position</label>
                            <select class="form-control" v-model="settings.imageTitlePosition">
                                <option value='1'>Above Image</option>
                                <option value='3'>Below Image</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Image Caption</label>
                            <select class="form-control" v-model="settings.imageCaptionEnabled">
                                <option v-bind:value='true'>Yes</option>
                                <option v-bind:value='false'>No</option>
                            </select>
                        </div>

                        <div v-show="settings.imageCaptionEnabled" class="form-group">
                            <label>Image Caption Class</label>
                            <input type="text" class="form-control" v-model="settings.imageCaptionClass">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div @click="cssSettingsOpen=!cssSettingsOpen" style="cursor: pointer; background: rgba(0,0,0, 0.2); padding: 5px 12px;">
                        <label style="margin: 0;">Css Settings</label>
                        <span class="pull-right">
                            <i v-if="!cssSettingsOpen" class="fa fa-arrow-circle-o-down" title="Show Css Settings" aria-hidden="true"></i>
                            <i v-else class="fa fa-arrow-circle-o-up" title="Hide Css Settings" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div v-if="cssSettingsOpen" class="form-sub-group">
                        <div class="form-group">
                            <label>Image Spacing</label>
                            <px v-model="settings.spaceBetweenImages"></px>
                        </div>

                        <div class="form-group">
                            <label>Image Padding</label>
                            <px v-model="settings.imagePadding"></px>
                        </div>

                        <div class="form-group">
                            <label>Background Color</label>
                            <color-picker v-model="settings.imageBackgroundColor"></color-picker>
                        </div>

                        <div class="form-group">
                            <label>Image Border</label>
                            <px v-model="settings.imageBorder"></px>
                        </div>

                        <div class="form-group">
                            <label>Image Border Background</label>
                            <color-picker v-model="settings.imageBorderColor"></color-picker>
                        </div>

                        <div class="form-group">
                            <label>Image Outline</label>
                            <px v-model="settings.imageOutline"></px>
                        </div>

                        <div class="form-group">
                            <label>Outline Color</label>
                            <color-picker v-model="settings.imageOutlineColor"></color-picker>
                        </div>
                    </div>
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
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Images'},
            blockClass: {type: String, default: ''},

            imagesTitleEnabled: {type: Boolean, default: false},
            imagesCaptionEnabled: {type: Boolean, default: false},
            imagesTitleClass: {type: String, default: 'h2'},
            imagesCaptionClass: {type: String, default: 'caption'},

            imageClass: {type: String, default: 'img-responsive img-center'},
            spaceBetweenImages: {type: String, default: '5px'},
            imageWidth: {type: String, default: '0'},

            imageBackgroundColor: {type: String, default: 'transparent'},
            imagePadding: {type: String, default: '0px'},

            imageBorder: {type: String, default: '0px'},
            imageBorderColor: {type: String, default: 'transparent'},
            imageOutline: {type: String, default: '0px'},
            imageOutlineColor: {type: String, default: 'transparent'},

            imageSize: {type: String, default: 'grid_large'},
            columnsPerRow: {type: Number, default: 3},
            imageAmount: {type: Number, default: 3},

            imageTitleEnabled: {type: Boolean, default: false},
            imageTitleClass: {type: String, default: ''},
            imageTitlePosition: {type: String, default: '1'},
            imageCaptionEnabled: {type: Boolean, default: false},
            imageCaptionClass: {type: String, default: 'caption'},

            canAddImages: {type: Boolean, default: false},
        },
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
                    height: 600
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
                imageList: [],
                maxImages:250,
                minImages: 1,
                imageSizeOptions: [
                    { text: 'Original', value: 'original' },
                    { text: 'Large', value: 'large' },
                    { text: 'Medium', value: 'medium' },
                    { text: 'Grid Large', value: 'grid_large' },
                    { text: 'Grid Medium', value: 'grid_medium' },
                    { text: 'Thumbnail Large', value: 'thumb_large' },
                    { text: 'Thumbnail Small', value: 'thumb' },
                ],
                cssSettingsOpen: false,
                blockSettingsOpen: false,
                imagesSettingsOpen: false,
            }
        },
        props: {
            transparentInputBackground: {type: Boolean, default: false},
        },
        computed: {
            settings () {
                return this.$store.getters.itemSettings(this.uniqueId)
            },
            modalTitle: function () {
                return this.settings.blockTitle + ' Settings'
            },
            imageWidth: function () {
                let offset = this.settings.spaceBetweenImages.replace(/[^0-9.]/g,"") * 2
                this.settings.imageWidth = "calc((100% / "+this.settings.columnsPerRow+") - "+offset+"px)"
                return this.settings.imageWidth
            },
            imageAmount: function() {
                return this.settings.imageAmount
            },
            inputBackground: function () {
                if(this.transparentInputBackground)
                    return 'rgba(0,0,0, 0.2)'
            },
            content () {
                return this.$store.getters.itemContent(this.uniqueId)
            },
            noImagePadding: function() {
                // console.log(this.settings.columnsPerRow)
                if(this.settings.columnsPerRow < 5)
                    return '40px'
                else if(this.settings.columnsPerRow < 6)
                    return '20px'
                else
                    return '10px'
            }
        },
        watch: {
            imageList: {
                handler(val) {
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
            'settings.imageSize'(val, old) {
                for (var i = 0; i < this.imageList.length; i++) {
                    this.setImageUrl(this.imageList[i])
                }
            },
            imageAmount(val, old) {
                if(val > old) {
                    // console.log('val: ' + val)
                    this.addImages(val)
                }
                else {
                    if(this.imageList.length != val)
                        this.removeImages(this.imageList.length - val)
                }
            },
            'settings.imageSize'(val, old) {
                for (var i = 0; i < this.imageList.length; i++) {
                    this.setImageUrl(this.imageList[i])
                }
            }
        },
        methods: {
            ...mapActions([
                'updateItemSettings',
                'updateItemContent',
            ]),
            imageUrl(image) {
                switch (this.settings.imageSize) {
                    case 'original':
                        return '/' + image.path + image.filename + '.' + image.extension
                    break;

                    case 'large':
                        return '/' + image.path + image.filename + '_large.' + image.extension
                    break;

                    case 'medium':
                        return '/' + image.path + image.filename + '_medium.' + image.extension
                    break;

                    case 'grid_large':
                        return '/' + image.path + image.filename + '_grid_large.' + image.extension
                    break;

                    case 'grid_medium':
                        return '/' + image.path + image.filename + '_grid_medium.' + image.extension
                    break;

                    case 'thumb_large':
                        return '/' + image.path + image.filename + '_thumb_large.' + image.extension
                    break;

                    case 'thumb':
                        return '/' + image.path + image.filename + '_thumb.' + image.extension
                    break;

                    default:
                        return '/' + image.path + image.filename + '_grid_medium.' + image.extension
                }
            },
            openMediaModal(uniqueId) {
                // console.log('open media modal')
                let mode = 'insertMultiImage'
                let params = new Object
                // params.imageId = this.selectedImage
                this.imageInput = uniqueId

                params.cb = (image) => {
                    for (var i = 0; i < this.imageList.length; i++) {
                        let item = this.imageList[i]
                        if(item.uniqueId == uniqueId) {
                            // item.object = image
                            item.filename = image.filename
                            item.id = image.id
                            item.extension = image.extension
                            item.path = image.path
                            item.id = image.id
                            break
                        }
                    }
                }

                this.$bus.$emit('open-media-modal', mode, params)
            },
            removeImages(val) {
                for (var i = 0; i < val; i++) {
                    this.imageList.pop()
                }
            },
            addImages(val) {
                let toAdd = val - this.imageList.length
                console.log('toAdd: ' + toAdd)
                for (var i = 0; i < toAdd; i++) {
                    let image = new Object()
                    image.uniqueId = this.uniqueId+'-image-' + this.imageList.length
                    image.id = null
                    image.title = ''
                    image.caption = ''
                    this.imageList.push(image)
                }
            },
            addImage() {
                if(this.imageList.length < this.maxImages) {
                    let image = new Object()
                    image.uniqueId = this.uniqueId+'-image-' + this.imageList.length
                    image.id = null
                    image.title = ''
                    image.caption = ''
                    this.imageList.push(image)
                    this.settings.imageAmount = this.imageList.length
                }
            },
            removeBlock() {
                this.$emit('remove', this.uniqueId);
            },
            closeModalAction() {
                // this.saveData()
                this.showModal = false
            },
            blockSettings() {
                this.showModal = true
            },
            processContent(content) {
                let self = this
                _.forEach(content, function(item) {
                    let image = new Object()
                    image.uniqueId = self.uniqueId+'-image-' + self.imageList.length
                    image.id = item.id
                    image.title = item.title
                    image.caption = item.caption
                    image.filename = item.filename
                    image.extension = item.extension
                    image.path = item.path
                    self.imageList.push(image)
                    self.settings.imageAmount = self.imageList.length
                })
                // this.imageAlt = content.alt
            }
        },
        beforeDestroy() {
            // console.log('images block before destroy')
        },
        mounted : function() {
            if(this.content)
                try {
                    this.content = JSON.parse(this.content);
                    this.processContent(JSON.parse(this.content))
                } catch (e) { this.processContent(this.content) }
            else {
                this.addImages(this.settings.imageAmount)
            }
        },
        components: {
            Modal,
            // ColorPicker
        }
    }
</script>

<style scoped lang="less">
    .content-block-body {
        min-height: 122px;
    }

    .title {
        margin-bottom: 5px;
        margin-top: 5px;
        padding-top: 5px;
        padding-bottom: 5px;

        &:hover {
            background-color: rgba(0,0,0, 0.05);
        }
    }

    .images {
        display: flex;
        flex-wrap: wrap;
        flex-direction: row;
    }

    .content-block {
        textarea {
           resize: none;
           height: 60px;
        }
    }

    .image {
        textarea {
           resize: none;
           height: 50px;
        }
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
