<template>
    <div :id="uniqueId" class="content-block">
        <modal ref="settingsModal" :title="modalTitle"
          v-model="showModal"
          v-bind="state.modalData"
          :draggable="true"
          :defaultWidth="modalDefaults.width"
          :defaultHeight="modalDefaults.height">
            <div class="modal-body" slot="modal-body">
                <div v-if="mode=='template'" v-for="blockItem in templateBlocks" @click="selectBlock(blockItem.block)" :class="['pick-block', { 'selected': selectedBlock == blockItem.block }]">
                    {{blockItem.title}}
                </div>

                <div v-if="mode=='standard'" v-for="blockItem in standardBlocks" @click="selectBlock(blockItem.block)" :class="['pick-block', { 'selected': selectedBlock == blockItem.block }]">
                    {{blockItem.title}}
                </div>
            </div>
            <div class="modal-footer" slot="modal-footer">
                <button type="button" class="btn btn-primary" @click="insertBlock" :disabled="selectedBlock == null ? true : false">Insert</button>
            </div>
        </modal>
    </div>
</template>

<script>
    import Modal from 'components/ui/Modal.vue'

    export default {
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
                showModal: this.initShowModal,
                modalTitle: 'Select Content Block',
                selectedBlock: null,
                callback: null,

                mode: 'template',

                standardBlocks: [
                    {'title': 'Header', 'block': 'HeaderBlock'},
                    {'title': 'Input', 'block': 'InputBlock'},
                    {'title': 'Text', 'block': 'TextBlock'},
                    {'title': 'Image', 'block': 'ImageBlock'},
                    {'title': 'Images', 'block': 'ImagesBlock'},
                    {'title': 'Columns', 'block': 'ColumnsBlock'},
                ],

                templateBlocks: [
                    {'title': 'Header', 'block': 'HeaderBlockTemplate'},
                    {'title': 'Input', 'block': 'InputBlockTemplate'},
                    {'title': 'Text', 'block': 'TextBlockTemplate'},
                    {'title': 'Image', 'block': 'ImageBlockTemplate'},
                    {'title': 'Images', 'block': 'ImagesBlockTemplate'},
                    {'title': 'Columns', 'block': 'ColumnsBlockTemplate'},
                ],
            }
        },
        props: {
            uniqueId: {type: String, default: null},
            initShowModal: {type: Boolean, default: false}
        },
        computed: {
        },
        watch: {
            showModal(val, old) {
                if(val == false)
                    this.selectedBlock = null
            },
            initShowModal(val, old) {
                this.showModal = val
            },
        },
        methods: {
            insertBlock() {
                this.showModal = false
                console.log('showModal: ' + this.showModal)
                if (this.callback) this.callback( this.selectedBlock )
            },
            selectBlock(block) {
                // console.log('selected block: ' + block)
                this.selectedBlock = block
            }
        },
        beforeDestroy() {
          //do something before destroying vue instance
        },
        mounted : function() {
            let self = this
            this.$bus.$on('open-blocks-modal', function(params) {
                console.log('opening blocks modal in mode: ' + params.mode)
                self.mode = params.mode
                self.showModal = true
                self.callback = (params.cb) ? params.cb : null
            })
        },
        components: {
            Modal
        }
    }
</script>

<style scoped lang="less">
    .content-block-body {
        min-height: 122px;
    }

    .pick-block {
        background-color: rgba(87, 113, 128, 0.09);
        float: left;
        width: 19.1%;
        padding: 15px;
        margin-right: 5px;
        margin-bottom: 5px;
        text-align: center;
        cursor: pointer;
        border: 1px solid transparent;
    }

    .pick-block:hover, .pick-block:active, .pick-block.hover {
        background-color: rgba(87, 113, 128, 0.15);
        border: 1px solid rgba(0,0,0, 0.25);
    }

    .selected {
        border: 1px solid rgba(0,0,0, 0.25);
    }

</style>
