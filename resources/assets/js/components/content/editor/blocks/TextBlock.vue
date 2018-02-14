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

            <div v-if="settings.editor=='basic'" style="padding: 10px 5px; border: 1px solid rgba(17,17,17,0.39);">
                <vue-medium-editor :class="settings.contentClass" :text='blockContent' custom-tag='div' :options="mediumEditorOptions" v-on:edit='updateBlockContent'></vue-medium-editor>
            </div>

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
                        <option value='basic'>Inline Editor</option>
                        <option value='markdown'>Markdown Editor</option>
                    </select>
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

    // Editors
    import VueMediumEditor from 'vue2-medium-editor'
    import Markdown from 'components/ui/editors/markdown.vue'
    import Colorpicker from 'utils/medium-editor-plugins/colorpicker'

    // Import editor css
    import 'medium-editor/dist/css/medium-editor.min.css'
    import 'medium-editor/dist/css/themes/mani.min.css'


    var faker = require('faker')


    export default {
        components: {
            Modal,
            VueMediumEditor,
            Markdown,
        },
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Text'},
            textClass: {type: String, default: ''},
            showEditorToolbar: {type: Boolean, default: true},
            editor: {type: String, default: 'basic'},
        },
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
                    height: 420
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
                markdownPreview: false,
                blockContent: this.$store.getters.itemContent(this.uniqueId) ? this.$store.getters.itemContent(this.uniqueId) : '',

                mediumEditorOptions: {
                    toolbar: {buttons: ['colorPicker', 'bold', 'italic', 'strikethrough', 'anchor', 'h1', 'h2', 'h3', 'h4', 'indent', 'outdent', 'orderedlist', 'unorderedlist', 'justifyLeft', 'justifyCenter', 'justifyRight']},
                    buttonLabels: 'fontawesome',
                    mode: VueMediumEditor.MediumEditor.inlineRichMode,
                    maxLength: 25,
                    placeholder: {
                        text: 'Get creative, write something here!',
                        hideOnClick: true
                    },
                    extensions: {
                        colorPicker: Colorpicker
                    }
                },

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
        },
        methods: {
            ...mapActions([
                'updateItemSettings',
                'updateItemContent',
            ]),
            updateBlockContent: function (operation) {
                this.blockContent = operation.event.srcElement.innerHTML
            },
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
            // if(!this.blockContent)
            //     this.blockContent = 'Add some text'
            console.log('text mounted')
        },
    }
</script>

<style lang="less" scoped>
    // .content-block-body textarea, {
    //     min-height: 250px;
    // }
    .medium-editor-element p {
        margin: 0;
    }
    .medium-editor-element {
        word-wrap: break-word;
        min-height: 1em;
    }

    .content-block-body .ql-editor {
        min-height: 80px;
    }
</style>
