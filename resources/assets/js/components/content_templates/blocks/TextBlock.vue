<template>
    <div :id="uniqueId" class="content-block">
        <div v-show="showHeaders" class="content-block-header">
            <i class="fa fa-arrows" aria-hidden="true"></i>
            <i class="fa fa-cog" @click="blockSettings()" aria-hidden="true"></i>
            {{ settings.blockTitle }}
            <div class="pull-right">
                <i @click="removeBlock" class="fa fa-times" aria-hidden="true" title="Remove"></i>
                <!-- <button v-on:click="removeBlock" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> -->
            </div>
        </div>

        <div v-show="showContent" class="content-block-body">
            <label v-show="!showHeaders && !containerPreview">{{ settings.blockTitle }}</label>

            <span v-if="settings.editor=='basic'">
                <textarea
                    v-model="blockContent"
                    :id="'basic-'+uniqueId"
                    v-bind:style="{'background-color': transparentBackground}"
                    style="height: 150px;"
                    class="form-control"
                    :class='settings.textClass'></textarea>
            </span>

            <span v-if="settings.editor=='markdown'">
                <markdown
                    v-model="blockContent"
                    :text-class='settings.textClass'
                    :uniqueId="'markdown-'+uniqueId"
                    :transparent-background="transparentInputBackground"
                    :show-toolbar="settings.showEditorToolbar">
                </markdown>
            </span>

        </div>

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
                    <label>Editor Type</label>
                    <select class="form-control" v-model="settings.editor">
                        <option value='basic'>Basic</option>
                        <option value='trumbowyg'>Trumbowyg</option>
                        <option value='markdown'>Markdown</option>
                    </select>
                    <small v-show="settings.editor=='basic'">Basic text editor, for simple text editing.</small>
                    <small v-show="settings.editor=='markdown'">Markdown editor, for markdown lovers.</small>
                    <small v-show="settings.editor=='trumbowyg'">What you see is what you get editor.</small>
                </div>
                <div class="form-group">
                    <label>Text Class</label>
                    <input type="text" class="form-control" v-model="settings.textClass">
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

    import Markdown from 'components/ui/editors/markdown.vue'

    var faker = require('faker')

    export default {
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Text'},
            textClass: {type: String, default: ''},
            showLabel: {type: Boolean, default: false},
            showEditorToolbar: {type: Boolean, default: true},
            editor: {type: String, default: 'markdown'},
        },
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
                    height: 420
                },
                // customToolbar: [
                //     ['bold', 'italic', 'underline'],
                //     [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                //     ['image', 'code-block']
                // ],
                state: {
                  modalData: {
                    top: 0,
                    left: 0,
                    width: null,
                    height: null
                  }
                },
                showModal: false,
                markdownPreview: false,
                blockContent: this.$store.getters.itemContent(this.uniqueId),

                configs: {
                  advanced: {
                    autogrow: true,
                    removeformatPasted: true,
                    // Adding color plugin button
                    btnsAdd: ['foreColor', 'backColor'],
                    // Limit toolbar buttons
                    btns: [
                      ['undo', 'redo'], ['formatting'], ['strong', 'em'],
                      ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                      ['link'],
                      ['removeformat'],
                      ['foreColor'], ['backColor'],
                    ]
                  },
                },

            }
        },
        props: {
            transparentInputBackground: {type: Boolean, default: false},
        },
        computed: {
            modalTitle: function () {
                return this.blockTitle + ' Settings'
            },
            compiledMarkdown: function () {
              return marked(this.content, { sanitize: true })
            },
            headerStyles: function() {
                if (this.transparentInputBackground) {
                    return 'background-color: transparent;'
                }
            },
            modalTitle: function () {
                return this.settings.blockTitle + ' Settings'
            },
            settings () {
                return this.$store.getters.itemSettings(this.uniqueId)
            },
            content () {
                return this.$store.getters.itemContent(this.uniqueId)
            },
            transparentBackground: function () {
                if(this.transparentInputBackground)
                    return 'rgba(0,0,0, 0.2)'
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
            'settings.editor': {
                handler(val, old) {
                    console.log('new: ' + val)
                    console.log('old: ' + old)
                    if(old == 'trumbowyg') {
                        $('#trumbowyg-'+this.uniqueId).trumbowyg('destroy')
                        console.log('trumbowyg cleaned')
                    }

                },
                deep: true
            }
        },
        methods: {
            ...mapActions([
                'updateItemSettings',
                'updateItemContent',
            ]),
            removeBlock() {
                this.$emit('remove', this.uniqueId);
            },
            closeModalAction() {
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
        },
        components: {
            Modal,
            Markdown,
        }
    }
</script>

<style lang="less" scoped>
    // .content-block-body textarea, {
    //     min-height: 250px;
    // }

    .content-block-body .ql-editor {
        min-height: 80px;
    }

    .ql-snow .ql-stroke {
        stroke: #cacaca;
    }

    .ql-snow .ql-picker {
        color: #cacaca;
    }

    .ql-toolbar.ql-snow {
        border: 1px solid rgba(17,17,17,0.12);
    }

    .ql-container.ql-snow {
        border: 1px solid rgba(17,17,17,0.29);
    }

    .ql-toolbar.ql-snow {
        background: rgba(0,0,0, 0.12);
    }
</style>
