<template>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Featured Image</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div v-if="contentId">
                <div v-if="imageId" class="form-group text-center set_featured_image featured-image-set">
                    <img @click="openMediaModal" class="img-responsive featured_image" :src="imageUrl">
                </div>
                <div v-else @click="openMediaModal" class="form-group text-center set_featured_image featured-image-not-set">
                    <div class="set_featured_image_text">Click to set featured image.</div>
                </div>
                <button v-if="imageId" type="button" class="btn btn-primary btn-block" @click="removeFeaturedImage">Remove Featured Image</button>
            </div>
            <div v-else>
                <div v-if="imageId" class="form-group text-center set_featured_image featured-image-set">
                    <img @click="openMediaModal" class="img-responsive featured_image" :src="imageUrl">
                </div>
                <div v-else @click="openMediaModal" class="form-group text-center set_featured_image featured-image-not-set">
                    <div class="set_featured_image_text">Click to set featured image.</div>
                </div>
                <button v-if="imageId" type="button" class="btn btn-primary btn-block" @click="removeFeaturedImage">Remove Featured Image</button>
            </div>
        </div>
    </div>
</template>
<script>
    // import { EventBus } from 'components/eventbus.js'

    export default {
        data: function data() {
            return {
                addRoute: this.initAddRoute,
                removeRoute: this.initRemoveRoute,
                contentId: this.initContentId,
                imageId: this.initImageId,
                imageUrl: this.initImageUrl
            }
        },
        props: {
            initAddRoute: {type: String, default: '/backend/media/add_featured_image'},
            initRemoveRoute: {type: String, default: '/backend/media/remove_featured_image'},
            initContentId: {type: Number, default: null},
            initImageId: {type: Number, default: null},
            initImageUrl: {type: String, default: null},
        },
        methods: {
            openMediaModal() {
                var mode = 'featuredimage'
                var params = new Object
                params.imageId = this.imageId
                params.id = this._uid

                params.cb = (image) => {
                    // console.log('featuredimage: ', image)
                    this.imageId = image.id
                    this.imageUrl = image.medium
                    this.addFeaturedImage()
                }

                // console.log('uid: ' + this._uid)

                this.$bus.$emit('open-media-modal', mode, params)
            },
            addFeaturedImage(image) {
                // console.log('addFeaturedImage: ' + this.addRoute)
                let formData = {id: this.contentId, image_id: this.imageId}
                axios.post(this.addRoute, formData)
                .then((response) => {
                    // console.log('success')
                    // console.log(response.data)
                })
                .catch((error) => {
                    // console.log('error')
                    // console.log(error)
                })
            },
            removeFeaturedImage() {
                let formData = {id: this.contentId}
                axios.post(this.removeRoute, formData)
                .then((response) => {
                    // console.log(response.data)
                    this.imageId = null
                    this.imageUrl = null
                })
                .catch((error) => {
                    // console.log(error)
                })
            }
        },
    }
</script>

<style lang="scss">

</style>
