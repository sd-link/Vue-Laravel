<template>
    <div class="content-block">
        <div v-show="showContent" class="content-block-body" style="display: flex; flex-direction: column;">
            <label>{{ settings.blockTitle }}</label>
            <input v-if="settings.imageTitleEnabled"
                v-model="image.title"
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
            </div>

            <textarea v-if="settings.imageCaptionEnabled"
                v-model="image.caption"
                class="form-control image-caption"
                :class="settings.imageCaptionClass"
                style="margin-top: 2px; order: 4;"
                placeholder="Enter Image Caption">
            </textarea>
        </div>
    </div>
</template>

<script>
    import GeneralMixin from '../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'
    import Modal from 'components/ui/Modal.vue'

    export default {
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
        props: {
            templateBlockId: {type: Number, default: 0},
        },
        watch: {
            image: {
                handler(val) {
                    // console.log(val)
                    this.updateItemContent({content: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
        },
        computed: {
            modalTitle: function () {
                return this.settings.blockTitle + ' Settings'
            },
            settings () {
                return this.$store.getters.itemSettings(this.uniqueId)
            },
            content () {
                return this.$store.getters.itemContent(this.uniqueId)
            },
            imageUrl() {
                return this.getCorrectImageSize()
            }
        },
        methods: {
            ...mapActions([
                'updateItemContent',
            ]),
            openMediaModal() {
                var mode = 'insertImage'
                var params = new Object
                // params.imageId = this.imageId
                params.id = this._uid

                params.cb = (image) => {
                    // console.log(image)
                    let tmpImg = new Object()
                    tmpImg.id = image.id
                    tmpImg.filename = image.filename
                    tmpImg.extension = image.extension
                    tmpImg.path = image.path

                    this.image = tmpImg
                    // this.setCorrectImageSize()
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

    textarea {
       resize: none;
       height: 60px;
    }

    // .image-title, .image-caption {
    //     background: transparent;
    //     border: 0;
    //     padding-left: 0;
    // }
</style>
