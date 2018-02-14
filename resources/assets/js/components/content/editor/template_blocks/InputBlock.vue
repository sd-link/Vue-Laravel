<template>
    <div class="content-block">
        <div v-show="showContent" class="content-block-body">
            <div class="form-group">
                <label>{{ settings.blockTitle }}</label>
                <!-- <div class="required" v-if="!showHeaders && settings.required"></div> -->
                <input v-model="inputContent.value" v-bind:style="inputStyles" class="form-control" type="text" :placeholder="'Enter '+settings.blockTitle">
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
            blockTitle: {type: String, default: 'Input'},
            required: {type: Boolean, default: false},
            blockClass: {type: String, default: 'h2'},
            minCharactersRequired: {type: Number, default: 1},
            maxCharactersAllowed: {type: Number, default: 120},
            showLabel: {type: Boolean, default: true}
        },
        data: function data() {
            return {
                showModal: false,
                inputContent: {
                    label: '',
                    value: '',
                },
            }
        },
        props: {
            transparentInputBackground: {type: Boolean, default: false}
        },
        computed: {
            inputStyles: function() {
                if (this.transparentInputBackground) {
                    return 'background-color: rgba(0, 0, 0, 0.2);'
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
        },
        watch: {
            settings: {
                handler(val) {
                    this.updateItemSettings({settings: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
            inputContent: {
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
            processContent(content) {
                this.inputContent = new Object()
                this.inputContent.label = content.label
                this.inputContent.value = content.value
            }
        },
        mounted : function() {
            if(this.content)
                this.processContent(JSON.parse(this.content))
        }
    }
</script>

<style scoped lang="less">
    .header {
        opacity: 1
    }
</style>
