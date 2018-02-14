<template>
    <div class="content-block">
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

                        <div v-else @click="openMediaModal(image.uniqueId)" class="text-center no-image"
                            v-bind:style="{
                                'background-color': 'rgba(0,0,0, 0.15)',
                                'padding': '40px'}"
                        >
                            <h3 style="height: 30px;">Image</h3>
                        </div>

                        <textarea v-if="settings.imageCaptionEnabled"
                            class="form-control image-caption"
                            :class="settings.imageCaptionClass"
                            v-bind:style="{'margin-top': '2px', 'order': '4', 'background-color': inputBackground }"
                            placeholder="Enter Caption">
                        </textarea>
                    </div>
                </div>
                <div style="clear: both;"></div>
                <div v-if="settings.canAddImages" class="text-center" style="margin: 2px 0;">
                    <button class="btn btn-primary btw-add-image" type="button" name="button">Add New Image</button>
                </div>
                <textarea v-show="settings.imagesCaptionEnabled"
                    class="form-control"
                    v-bind:style="{'margin-top': '2px', 'background-color': inputBackground }"
                    placeholder="Enter Caption" >
                </textarea>
            </div>
        </div>
    </div>
</template>

<script>
    import GeneralMixin from '../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

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
            spaceBetweenImages: {type: String, default: '1px'},

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
            }
        },
        props: {

        },
        computed: {
            settings () {
                return this.$store.getters.itemSettings(this.uniqueId)
            },
            content () {
                return this.$store.getters.itemContent(this.uniqueId)
            },
            imageWidth: function () {
                let offset = this.settings.spaceBetweenImages.replace(/[^0-9.]/g,"") * 2
                this.settings.imageWidth = "calc(calc(100% / "+this.settings.columnsPerRow+") - "+offset+"px)"
                return this.settings.imageWidth
            },
            imageAmount: function() {
                return this.settings.imageAmount
            },
            inputBackground: function () {
                if(this.transparentInputBackground)
                    return 'rgba(0,0,0, 0.2)'
            },
        },
        watch: {
            settings: {
                handler(newVal) {
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            },
            imageList: {
                handler(val) {
                    this.updateItemContent({content: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
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
            addImage() {
                if(this.imageList.length < this.maxImages) {
                    let image = new Object()
                    image.uniqueId = this.uniqueId + '-image-' + this.imageList.length
                    image.id = null
                    image.title = ''
                    image.caption = ''
                    this.imageList.push(image)
                }
            },
            openMediaModal(uniqueId) {
                // console.log('open media modal')
                let mode = 'insertImage'
                let params = new Object
                this.imageInput = uniqueId

                params.cb = (image) => {
                    for (var i = 0; i < this.imageList.length; i++) {
                        let item = this.imageList[i]
                        if(item.uniqueId == uniqueId) {
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
            removeImage(uniqueId) {
                if(this.imageList.length > this.minImages) {
                    this.imageList = $.grep(this.imageList, function(image){
                        return image.uniqueId != uniqueId;
                    })
                }
            },
            removeBlock() {
                this.$emit('remove');
            },
            addImages(val) {
                this.imageList = []
                for (var i = 0; i < val; i++) {
                    let image = new Object()
                    image.uniqueId = this.uniqueId+'-image-' + this.imageList.length
                    image.id = null
                    this.imageList.push(image)
                }
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
                })
            }
        },
        mounted : function() {
            if(this.content)
                this.processContent(JSON.parse(this.content))
            else {
                this.addImages(this.settings.imageAmount)
            }
        },
        components: {
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

    .no-image {
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
        order: 2;
    }
</style>
