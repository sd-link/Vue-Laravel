<template>
    <div :id="uniqueId" class="content-block">
        <div v-show="showHeaders" class="content-block-header">
            <i class="fa fa-arrows" aria-hidden="true"></i>
            <i class="fa fa-cog" @click="blockSettings()" aria-hidden="true"></i>
            {{ settings.blockTitle }}
            <!-- <div class="required" v-if="settings.required"></div> -->
            <button v-on:click="removeBlock" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>

        <div v-show="showContent" class="content-block-body">
            <div class="form-group">
                <label v-show="!showHeaders && !containerPreview">{{ settings.blockTitle }}</label>
                <input v-model="inputContent.value" v-bind:style="inputStyles" class="form-control" :class="settings.inputClass" type="text" :placeholder="'Enter '+settings.blockTitle">
            </div>
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
                    <label>Input Class</label>
                    <input type="text" class="form-control" v-model="settings.inputClass">
                </div>

                <div class="form-group">
                    <label>Render Label</label>
                    <select class="form-control" v-model="settings.renderLabel">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                    <small>If value should be rendered with label on the frontend.</small>
                </div>

                <div v-if="settings.renderLabel" class="form-group">
                    <label>Label</label>
                    <input type="text" class="form-control" v-model="inputContent.label">
                </div>

                <div v-if="settings.renderLabel" class="form-group">
                    <label>Render Label Style</label>
                    <select class="form-control" v-model="settings.renderLabelStyle">
                        <option value='above'>Above</option>
                        <option value='inline'>Inline</option>
                    </select>
                </div>

                <div v-if="settings.renderLabel" class="form-group">
                    <label>Lable Class</label>
                    <input type="text" class="form-control" v-model="settings.lableClass">
                </div>
                <!-- <div class="form-group">
                    <label>Required</label>
                    <select class="form-control" v-model="settings.required">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div> -->

                <!-- <div v-show="settings.required" class="form-group">
                    <label>Minimum Characters Required</label>
                    <el-input-number v-model="settings.minCharactersRequired" :min="1" :max="6"></el-input-number>
                </div> -->

                <!-- <div class="form-group">
                    <label>Max Characters Allowed</label>
                    <el-input-number v-model="settings.maxCharactersAllowed" :min="7" :max="180"></el-input-number>
                </div> -->

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
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Input'},
            required: {type: Boolean, default: false},
            inputClass: {type: String, default: ''},
            minCharactersRequired: {type: Number, default: 1},
            maxCharactersAllowed: {type: Number, default: 120},
            renderLabel: {type: Boolean, default: false},
            renderLabelStyle: {type: String, default: 'above'},
            lableClass: {type: String, default: 'bold'},
        },
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
                    height: 580
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
                handler(newVal) {
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
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
            removeBlock() {
                this.$emit('remove', this.uniqueId)
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
            },
            processContent(content) {
                this.inputContent = new Object()
                this.inputContent.label = content.label
                this.inputContent.value = content.value
            }
        },
        mounted : function() {
            if(this.content)
                this.processContent(JSON.parse(this.content))

        },
        components: {
            Modal,
        }
    }
</script>

<style scoped lang="less">
    .header {
        opacity: 1
    }
</style>
