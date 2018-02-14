<template>
    <div>
        <i @click="showModal=true" class="fa fa-cog" style="font-size: 18px; cursor: pointer;" title="Display Settings" aria-hidden="true"></i>

        <modal ref="settingsModal" :title="modalTitle"
          v-model="showModal"
          v-bind="state.modalData"
          :draggable="true"
          :defaultWidth="modalDefaults.width"
          :defaultHeight="modalDefaults.height">
            <div class="modal-body" slot="modal-body">
                <div class="form-group">
                    <label>Display as</label>
                    <select class="form-control" v-model="indexDisplay">
                        <option value='list'>List</option>
                        <option value='grid'>Grid</option>
                    </select>
                </div>

                <div v-show="indexDisplay == 'grid'" class="form-group">
                    <label>Columns</label>
                    <select class="form-control" v-model="indexPageGridColumns">
                        <option value='column-1'>1</option>
                        <option value='column-2'>2</option>
                        <option value='column-3'>3</option>
                        <option value='column-4'>4</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer" slot="modal-footer">
                <button type="button" class="btn btn-primary" @click="saveAction">Save</button>
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
                    height: 300
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
                modalTitle: 'Settings',
                indexDisplay: 'grid',
                indexPageGridColumns: 'column-1',
            }
        },
        watch: {
            indexDisplay(val, old) {
                $("#galleries").removeClass()
                $("#galleries").addClass(val+" gallery")
            },
            indexPageGridColumns(val, old) {
                $("[id^=gallery-]").removeClass()
                $("[id^=gallery-]").addClass('row ' + val)
            }
        },
        methods: {
            saveAction() {
                this.showModal = false
            },
        },
        components: {
            Modal,
        }
    }
</script>

<style lang="less" scoped>

</style>
