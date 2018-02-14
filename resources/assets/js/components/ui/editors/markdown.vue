<template>
    <div id="uniqueId+'-markdown'" class="content-block">
        <div v-if="showToolbar" class="markdown-toolbar">
            <i class="fa fa-eye toobar-button" style="cursor: pointer;" @click="markdownPreviewToggle()" title="Preview" aria-hidden="true"></i>
            <!-- <i class="fa toobar-button" @click="makeBold()" title="bold" aria-hidden="true">B</i>
            <i class="fa toobar-button" @click="makeItalic()" title="italic" aria-hidden="true">i</i> -->
            <i class="fa fa-columns toobar-button" @click="toggleSideBySide()" title="toggle side by side" aria-hidden="true"></i>
        </div>
        <div>
            <textarea v-show="!markdownPreview"
                v-autosize
                :id="uniqueId+'-textarea'"
                :value="content"
                @input="update"
                class="form-control"
                :class="textClass"
                style="float:left; width:50%;"
                v-bind:style="{'float': 'left', 'width': markdownEditorWidth, 'background-color': transparentInputBackground}">
                {{ content }}
            </textarea>
            <div v-show="markdownSideBySide && !markdownPreview"
                v-html="compiledMarkdown"
                style="float:left; padding-left: 10px; padding: 10px; width:50%; font-size: 14px;">
            </div>
            <div style="clear: both;"></div>
            <div v-show="markdownPreview" v-html="compiledMarkdown" style="font-size: 14px;"></div>
        </div>

    </div>
</template>
<script>
    import marked from 'marked'
    import autosize from 'components/directives/autosize'
    import _ from 'lodash'

    export default {
        directives: { autosize },
        data: function data() {
            return {
                markdownSideBySide: false,
                markdownEditorWidth: '100%',
                markdownPreview: false,
                content: this.value,
            }
        },
        props: {
            uniqueId: {type: String, default: null},
            value: {type: String, default: 'Get creative!'},
            transparentBackground: {type: Boolean, default: true},
            showToolbar: {type: Boolean, default: false},
            textClass: {type: String, default: ''},
        },
        watch: {
            // transparentBackground(val, old) {
            //     console.log('transparentBackground: ' + val)
            // },
        },
        computed: {
            compiledMarkdown: function () {
                return marked(this.content, { sanitize: true })
            },
            transparentInputBackground: function () {
                if(this.transparentBackground)
                    return 'rgba(0,0,0, 0.2)'
            }
        },
        methods: {
            markdownPreviewToggle() {
                this.markdownPreview = !this.markdownPreview
            },
            toggleSideBySide() {
                this.markdownSideBySide = !this.markdownSideBySide
                if(this.markdownSideBySide)
                    this.markdownEditorWidth = '50%'
                else
                    this.markdownEditorWidth = '100%'
            },
            // makeBold() {
            //     // var mytextarea = $('#'+this.uniqueId+'-textarea')[0]
            //     // var selectionText = mytextarea.value.substr(mytextarea.selectionStart, mytextarea.selectionEnd)
            //     // mytextarea.value = "**" + selectionText + "**"
            //
            //     this.wrapText(this.uniqueId+'-textarea', "**", "**")
            // },
            // makeItalic() {
            //     this.wrapText(this.uniqueId+'-textarea', "*", "*")
            // },
            // wrapText(elementID, openTag, closeTag) {
            //     var textArea = $('#' + elementID);
            //     var len = textArea.val().length;
            //     var start = textArea[0].selectionStart;
            //     var end = textArea[0].selectionEnd;
            //     var selectedText = textArea.val().substring(start, end);
            //     var replacement = openTag + selectedText + closeTag;
            //     this.content = (textArea.val().substring(0, start) + replacement + textArea.val().substring(end, len))
            // },
            update: _.debounce(function (e) {
                // console.log('updated')
                this.content = e.target.value
                this.$emit('input', this.content)
            }, 100),
            // update(e) {
            //     this.content = e.target.value
            //     this.$emit('input', this.content)
            // }
        },
        mounted : function() {
            // $('html, body').scrollTop( $(document).height() )
        },
    }
</script>
<style scoped lang="less">

    .markdown-toolbar {
        padding: 2px 10px;
        background: rgba(0,0,0, 0.08);
    }

    p {
        margin: 0 0 40px
    }

    // textarea {
    //     min-height: 130px;
    // }

    .toobar-button {
        // background: rgba(0,0,0, 0.1);
        cursor: pointer;
        text-align: center;
        width: 22px;
        margin-right: 5px;

        display: inline-block;
        text-align: center;
        text-decoration: none!important;
        width: 30px;
        height: 30px;
        margin: 0;
        border: 1px solid transparent;
        border-radius: 3px;
        cursor: pointer;
        line-height: 26px;
        font-size: 15px;

            &:hover {
                // background-color: rgba(0,0,0, 0.25);
                color: #06c;
            }
    }
</style>
