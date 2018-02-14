<template>
    <div class="content-block">
        <div v-show="showHeaders" class="content-block-header">
            <i class="fa fa-arrows"></i>
            <i class="fa fa-cog" @click="blockSettings()"></i>
            {{ settings.blockTitle }}
            <button @click="removeBlock()" type="button" class="close"><span>×</span></button>
        </div>

        <div v-show="showContent" class="content-block-body" style="position: relative;">
            <label v-show="!showHeaders && !containerPreview">{{  settings.blockTitle }}</label>
            <div v-bind:style="{ 'display': 'flex', 'justify-content': settings.position }">
                <!-- <embed :width="settings.width" :height="settings.height" :src="embed" style="border: 0;"> -->
                <iframe :id="this.uniqueId" :width="settings.width" :height="settings.height" :src="embed" style="border: 0;"></iframe>
            </div>
            <span class="video-buttons">
                <span @click="setVideo()" class="set-video" title="Set Video"><i class="fa fa-image" aria-hidden="true"></i></span>
            </span>
        </div>

        <modal ref="settingsModal" title="Set Video"
          v-model="showSetVideoModal"
          v-bind="state.modalData"
          :draggable="true"
          :defaultWidth="modalDefaults.width"
          defaultHeight="300">
            <div class="modal-body" slot="modal-body">
                <div class="form-group">
                    <label>Video Url</label>
                    <input type="text" class="form-control" v-model="video.url">
                </div>
            </div>
            <div class="modal-footer" slot="modal-footer">
                <button type="button" class="btn btn-primary" @click="closeSetVideoModalAction">Set</button>
            </div>
        </modal>

        <modal ref="settingsModal" :title="modalTitle"
          v-model="showModal"
          v-bind="state.modalData"
          :draggable="true"
          :defaultWidth="modalDefaults.width"
          :defaultHeight="modalDefaults.height">
            <div class="modal-body" slot="modal-body">
                <div class="form-group">
                    <label>Block Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
                </div>

                <div class="form-group">
                    <label>Position</label>
                    <select class="form-control" v-model="settings.position">
                        <option value="flex-start">Left</option>
                        <option value="center">Center</option>
                        <option value="flex-end">Right</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Width</label>
                    <input type="text" class="form-control" v-model="settings.width">
                </div>

                <div class="form-group">
                    <label>Height</label>
                    <input type="text" class="form-control" v-model="settings.height">
                </div>

                <div class="form-group">
                    <label>Enable Video Title</label>
                    <select class="form-control" v-model="settings.videoTitleEnabled">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>

                <div v-show="settings.videoTitleEnabled" class="form-group">
                    <label>Title Class</label>
                    <input type="text" class="form-control" v-model="settings.videoTitleClass"/>
                </div>

                <div class="form-group">
                    <label>Show Image Caption</label>
                    <select class="form-control" v-model="settings.videoCaptionEnabled">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>

                <div v-show="settings.videoCaptionEnabled" class="form-group">
                    <label>Caption Class</label>
                    <input type="text" class="form-control" v-model="settings.videoCaptionClass"/>
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
            Modal
        },
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Video'},
            position: {type: String, default: 'center'},
            width: {type: String, default: '100%'},
            height: {type: String, default: '480'},

            videoTitleEnabled: {type: Boolean, default: false},
            videoTitleClass: {type: String, default: 'h2'},

            videoCaptionEnabled: {type: Boolean, default: false},
            videoCaptionClass: {type: String, default: 'caption'},
        },
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
                    height: 530
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
                showSetVideoModal: false,
                video: {
                    title: '',
                    caption: '',
                    url: 'https://www.youtube.com/watch?v=6W_SRmUNR1g',
                }
            }
        },
        props: {
        },
        computed: {
            embed() {
                console.log('url: ' + this.video.url)
                return "https://www.youtube.com/embed/" + this.video.url.substr(this.video.url.indexOf("=") + 1);
            },
            modalTitle: function () {
                return this.blockTitle + ' Settings'
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
        },
        watch: {
            video: {
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
        },
        methods: {
            ...mapActions([
                'updateItemSettings',
                'updateItemContent',
            ]),
            removeBlock() {
                this.$emit('remove');
            },
            closeSetVideoModalAction() {
                this.showSetVideoModal = false
            },
            closeModalAction() {
                this.showModal = false
            },
            setVideo() {
                this.showSetVideoModal = true
            },
            blockSettings() {
                this.showModal = true
            },
            processContent(content) {
                this.video = new Object()
                this.video.title = content.title
                this.video.caption = content.caption
                this.video.url = content.url
            }
        },
        mounted : function() {
            if(this.content)
                this.processContent(JSON.parse(this.content))
        }
    }
</script>



<style scoped lang="less">

    .video-buttons {
        margin: 0;
        position: absolute;
        top: 5px;
        width: 100%;
        text-align: right;
        padding: 10px;
        pointer-events: none;
    }

    .set-video {
        border: 1px solid rgba(173, 216, 230, 0.5);
        padding: 10px 12px;
        margin: 1px;
        cursor: pointer;
        border-radius: 50%;
        background-color: rgba(43, 92, 147, 0.65);
        color: #b8c7ce;
        text-align: center;
        font-size: 15px;
        pointer-events: all;

        &:hover {
            background-color: rgba(43, 92, 147, 0.85);
            border: 1px solid rgba(173, 216, 230, 0.8);
        }
    }


</style>
