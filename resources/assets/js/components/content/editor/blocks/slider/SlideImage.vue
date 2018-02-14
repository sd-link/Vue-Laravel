<template>
    <div class="slide-image" style="display: flex; flex-direction: row; alignItems: center;" :style="{'justifyContent': settings.imagePosition}">
        <img v-if="image" :src="image" @click="openMediaModal()" :class="[settings.imageResponsive ? 'img-responsive' : '' , settings.contentClass]">
        <div @click="openMediaModal()" v-else class="text-center" style="padding: 122px;">
            <h2>Set Image</h2>
        </div>
    </div>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'
    import Modal from 'components/ui/Modal.vue'
    import autosize from 'components/directives/autosize'

    export default {
        directives: { autosize },
        components: {
            Modal,
        },
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Image'},
            contentClass: {type: String, default: ''},
            imageResponsive: {type: Boolean, default: true},
            imagePosition: {type: String, default: 'center'},
        },
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
                    height: 320
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
                image: this.$store.getters.itemContent(this.uniqueId),
            }
        },
        props: {
            editingSlide: {type: Boolean, default: false},
        },
        computed: {
            modalTitle: function () {
                return this.settings.blockTitle + ' Settings'
            },
            settings () {
                return this.$store.getters.itemSettings(this.uniqueId)
            },
        },
        watch: {
            image(val, old) {
                this.updateItemContent({content:  val, uniqueId: this.uniqueId})
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
                    this.image = image.original
                }

                this.$bus.$emit('open-media-modal', mode, params)
            },
            blockSettings() {
                this.showModal = true
            },
            closeModalAction() {
                this.showModal = false
            },
        },
        mounted : function() {
            console.log('slideimage mounted')
        }
    }
</script>

<style lang="less" scoped>
    .slide-image {
        pointer-events: all;
        cursor: pointer;
    }
</style>
