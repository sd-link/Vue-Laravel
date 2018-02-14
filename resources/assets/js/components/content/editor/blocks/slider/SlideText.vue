<template>
    <div class="slide-text" style="position: relative; pointer-events: all;">
        <medium-editor :class="settings.contentClass" :text='blockContent' custom-tag='div' :options="options" v-on:edit='updateBlockContent'></medium-editor>
    </div>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'
    import Modal from 'components/ui/Modal.vue'
    import autosize from 'components/directives/autosize'
    import editor from 'vue2-medium-editor'

    import 'medium-editor/dist/css/medium-editor.min.css'

    export default {
        directives: { autosize },
        components: {
            Modal,
            'medium-editor': editor
        },
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Text'},
            contentClass: {type: String, default: 'white'},
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
                options: {
                    toolbar: {buttons: ['bold', 'italic', 'strikethrough', 'anchor', 'h1', 'h2', 'h3', 'h4']}
                },
                showModal: false,
                noBorder: 'no-border',
                blockContent: this.$store.getters.itemContent(this.uniqueId),
            }
        },
        props: {
            editingSlide: {type: Boolean, default: false},
        },
        computed: {
            blockStyles: function() {
                let styles = ""
                // styles = styles + 'position: '+this.settings.position+';'
                return styles
            },
            modalTitle: function () {
                return this.settings.blockTitle + ' Settings'
            },
            settings () {
                return this.$store.getters.itemSettings(this.uniqueId)
            },
        },
        watch: {
            blockContent(val, old) {
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
            updateBlockContent: function (operation) {
                this.blockContent = operation.event.srcElement.innerHTML
            },
            ...mapActions([
                'updateItemSettings',
                'updateItemContent',
            ]),
            responsiveWidth() {
                this.settings.widthResponsive =! this.settings.widthResponsive

                if(this.settings.widthResponsive) {
                    this.settings.widthTablet = this.settings.width
                    this.settings.widthMobile = this.settings.width
                }
            },
            blockSettings() {
                this.showModal = true
            },
            closeModalAction() {
                this.showModal = false
            },
        },
        mounted : function() {
            console.log('slide text mounted')
            if(this.blockContent == null)
                this.blockContent = 'Lorem Ipsum is simply dummy text...'
        }
    }
</script>

<style lang="less" scoped>
    .text-controls {
        padding: 2px;
    }

    textarea {
        height: 1.3em;
    }

    .no-border {
        border: 0;
    }

    .slide-text {
    }

    p {
        margin: 0px !important;
    }
</style>
