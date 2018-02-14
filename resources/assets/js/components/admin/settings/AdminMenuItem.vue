<template>
    <div :id="id" style="margin-bottom: 2px; background-color: rgba(224, 239, 255, 0.025);" v-bind:style="subitemstyle()">
        <div class="admin-menu-sortable">
            <div v-if="!subitem" class="admin-menu-sortable-handle">
                <span>☰</span>
            </div>
            <div v-if="!subitem && hasChildren" class="admin-menu-sortable-left" style="cursor: pointer; padding-left: 7px;" v-on:click="showSubItems = !showSubItems">
                <i :class="icon"></i> <span> {{ name }} </span>
            </div>
            <div v-else class="admin-menu-sortable-left" style=" padding-left: 7px;">
                <i :class="icon"></i> <span> {{ name }} </span>
            </div>
            <div class="admin-menu-sortable-right" style="font-size: 16px;">
                <i class="fa fa-pencil-square-o" @click="editMenuItem()" style="cursor: pointer;" aria-hidden="true"></i>
                <i v-if="visible" v-on:click="hide()" class="fa fa-eye" style="cursor: pointer;" aria-hidden="true"></i>
                <i v-else class="fa fa-eye-slash" v-on:click="show()" style="cursor: pointer;" aria-hidden="true"></i>
            </div>
        </div>
        <div v-show="showSubItems" style="padding-left: 20px; padding-bottom: 5px;">
            <slot></slot>
        </div>

        <modal ref="settingsModal" :title="modalTitle"
          v-model="showModal"
          v-bind="state.modalData"
          :defaultWidth="modalDefaults.width"
          :defaultHeight="modalDefaults.height"
        >
            <div class="modal-body" slot="modal-body">
                <div class="form-group">
                      <label for="">Item Name</label>
                      <input type="text" class="form-control" v-model="name">
                </div>
                <div class="form-group">
                      <label for="">Item Icon</label>
                      <input type="text" class="form-control" v-model="icon">
                      <small>available icons: <a href="http://fontawesome.io/icons/" target="_blank">fontawesome</a></small>
                </div>
          </div>
          <div class="modal-footer" slot="modal-footer">
            <button type="button" class="btn btn-default" @click="cancelAction">Cancel</button>
            <button type="button" class="btn btn-primary" @click="saveAction">Save</button>
          </div>
        </modal>
    </div>
</template>

<script>
    import { find as _find, findIndex as _findIndex, indexOf as _indexOf, cloneDeep as _cloneDeep } from 'lodash'

    export default {
        props: {
            id: {type: Number},
            initIcon: {type: String},
            initName: {type: String},
            initVisible: {type: Number, default: 1},
            subitem: {type: Boolean, default: false},
            initShowSubItems: {type: Boolean, default: false},
            hasChildren: {type: Boolean, default: false},
        },
        data() {
            return {
                modalDefaults: {
                  width: 300,
                  height: 300
                },
                tempData: {
                    name: null,
                    icon: null
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
                modalTitle: 'Update Menu Item',
                name: this.initName,
                icon: this.initIcon,
                showSubItems: this.initShowSubItems,
                visible: this.initVisible,
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        watch: {
          showModal (val, old) {
            if(!val && old) {
              let currentModalPosition = this.$refs.settingsModal.currentPositionData
              this.state.modalData = Object.assign({}, this.state.modalData, currentModalPosition)
            }
          }
        },
        methods: {
            subitemstyle: function() {
                if(this.subitem) {
                    return {
                        'width': '97%',
                        'margin-left': '10px',
                        'background-color': 'rgba(224, 239, 255, 0.02)',
                        'margin-bottom': '2px',
                        'padding-left': '10px'
                    }
                } else {
                    return {};
                }
            },
            editMenuItem() {
                this.tempData.name = this.name
                this.tempData.icon = this.icon
                this.showModal = true
            },
            saveAction() {
                this.saveData()
                this.showModal = false
            },
            cancelAction() {
                this.name = this.tempData.name
                this.icon = this.tempData.icon
                this.showModal = false
            },
            hide: function() {
                this.visible = 0
                this.saveData()
            },
            show: function() {
                this.visible = 1
                this.saveData()
            },
            fillFormData() {
                this.formData = {visible: this.visible, id: this.id}
            },
            saveVisibility() {
                let formData = {visible: this.visible, id: this.id}
                axios.post('/backend/settings/admin/menu/item/visible', formData)
                .then((response) => {
                    console.log(response.data)
                })
                .catch((error) => {
                    console.log(error)
                })
            },
            saveData() {
                let formData = {id: this.id, name: this.name, icon: this.icon, visible: this.visible}
                axios.post('/backend/settings/admin/menu/item/save', formData)
                .then((response) => {
                    console.log(response.data)
                })
                .catch((error) => {

                })
            }
        }
    }
</script>
