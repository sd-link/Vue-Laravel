<template>
    <div class="content-block">
        <div v-show="showContent" class="content-block-body">
            <label>{{ settings.blockTitle }}</label>
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
    </div>
</template>

<script>
    import GeneralMixin from '../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

    import Markdown from 'components/ui/editors/markdown.vue'


    export default {
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Text'},
            blockClass: {type: String, default: 'h2'},
            showLabel: {type: Boolean, default: false},
            showEditorToolbar: {type: Boolean, default: true},
        },
        data: function data() {
            return {
                // content: '',
                customToolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['image', 'code-block']
                ],
                showModal: false,
                blockContent: this.$store.getters.itemContent(this.uniqueId),
                markdownPreview: false,

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
            editor: {type: String, default: 'markdown-editor'},
        },
        computed: {
            compiledMarkdown: function () {
              return marked(this.content, { sanitize: true })
            },
            headerStyles: function() {
                if (this.transparentInputBackground) {
                    return 'background-color: transparent;'
                }
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
            content: {
                handler(newVal) {
                    console.log('does not get triggered')
                    this.updateItemContent({content: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            },
            settings: {
                handler(newVal) {
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            },
        },
        methods: {
            ...mapActions([
                'updateItemSettings',
                'updateItemContent',
            ])
        },
        mounted : function() {

        },
        components: {
            Markdown,
        }
    }
</script>

<style lang="less" scoped>
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
