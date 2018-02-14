<template>
    <div :id="settings.uniqueId" class="content-block">
        <div v-show="showHeaders" class="content-block-header">
            <i class="fa fa-arrows" aria-hidden="true"></i>
            <i class="fa fa-cog" @click="blockSettings()" aria-hidden="true"></i>
            {{ settings.blockTitle }}
            <!-- <div class="required" v-if="settings.required"></div> -->
            <button @click="removeBlock" type="button" class="close"><span aria-hidden="true">×</span></button>
        </div>

        <div v-show="showContent" class="content-block-body">
            <label v-show="!showHeaders && !containerPreview">{{ settings.blockTitle }}</label>
            <!-- <div class="required" v-if="!showHeaders && settings.required"></div> -->
            <input v-model="blockContent" v-bind:style="inputStyles" type="text" class="form-control" :class="settings.headerClass" :placeholder="'Enter '+settings.blockTitle">
        </div>

        <modal ref="settingsModal" :title="modalTitle"
          v-model="showModal"
          v-bind="state.modalData"
          :draggable="true"
          :defaultWidth="modalDefaults.width"
          :defaultHeight="modalDefaults.height"
        >
            <div class="modal-body" slot="modal-body">
                <div class="form-group">
                    <label>Block Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
                </div>
                <div class="form-group">
                    <label>Header Classes</label>
                    <input type="text" class="form-control" v-model="settings.headerClass">
                </div>

                <!-- <div class="form-group">
                    <label>Required</label>
                    <select class="form-control" v-model="settings.required">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div> -->

                <div v-show="settings.required" class="form-group">
                    <label>Minimum Characters Required</label>
                    <el-input-number v-model="settings.minCharactersRequired" :min="1" :max="6"></el-input-number>
                </div>

                <div class="form-group">
                    <label>Max Characters Allowed</label>
                    <el-input-number v-model="settings.maxCharactersAllowed" :min="7" :max="180"></el-input-number>
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
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Header'},
            placeholder: {type: String, default: 'Enter Header'},
            required: {type: Boolean, default: false},
            headerClass: {type: String, default: 'h2'},
            minCharactersRequired: {type: Number, default: 1},
            maxCharactersAllowed: {type: Number, default: 120},
            showLabel: {type: Boolean, default: true}
        },
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
                    height: 570
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
                headerSizeOptions: [
                    { text: 'H1', value: 'h1' },
                    { text: 'H2', value: 'h2' },
                    { text: 'H3', value: 'h3' },
                    { text: 'H4', value: 'h4' },
                ],

                blockContent: this.$store.getters.itemContent(this.uniqueId),
            }
        },
        props: {
            transparentInputBackground: {type: Boolean, default: false}
        },
        computed: {
            inputStyles: function() {
                if (this.transparentInputBackground) {
                    return 'background-color: rgba(0, 0, 0, 0.11);'
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
            transparentInputBackground(val, old) {
                console.log('transparentInputBackground changed inside header block')
            },
            settings: {
                handler(val) {
                    this.updateItemSettings({settings: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
            blockContent: {
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
                this.$emit('remove')
            },
            saveBlockAction() {

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
        beforeDestroy() {
          //do something before destroying vue instance
        },
        mounted : function() {

            // if(this.initNewBlock)
            //     document.getElementById(this.uniqueId).scrollIntoView()

        },
        components: {
            Modal,
        }
    }
</script>

<style scoped lang="less">
    .h1, h1, .h2, h2, .h3, h3, .h4, h4 {
        height: 50px;
    }
</style>
