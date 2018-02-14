<template>
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Content Type Settings</h3>
            </div>
            <div class="box-body">
                <div class="form-group" style="margin-bottom: 2px; margin-top: 5px;">
                    <button v-on:click="save()" :disabled='savingContentType' style="width: 70px;" class="btn btn-primary pull-right" type="submit">{{saveBtnText}}</button>
                    <div style="clear: both;"></div>
                </div>

                <label>Features</label>
                <div class="form-group" style="padding: 15px; border: 1px solid #222; border-radius: 5px;">
                    <div class="form-group">
                        <div class="form-group">
                            <label>Enable Content Blocks</label>
                            <select class="form-control" v-model="settings.featureEnableContentBlocks.value">
                                <option v-bind:value='true'>Yes</option>
                                <option v-bind:value='false'>No</option>
                            </select>
                        </div>

                        <label>Enable Featured Image</label>
                        <select class="form-control" v-model="settings.featureEnableFeaturedImage.value">
                            <option v-bind:value='true'>Yes</option>
                            <option v-bind:value='false'>No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Enable Access Control</label>
                        <select class="form-control" v-model="settings.featureEnableAccessControl.value">
                            <option v-bind:value='true'>Yes</option>
                            <option v-bind:value='false'>No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Enable Password Support</label>
                        <select class="form-control" v-model="settings.featureEnablePasswordSupport.value">
                            <option v-bind:value='true'>Yes</option>
                            <option v-bind:value='false'>No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from 'components/eventbus.js'

    export default {
        data: function data() {
            return {
                savingContentType: false,
                saveBtnText: 'Save',
                settings: {
                    featureEnableAccessControl: {value: this.initFeatureEnableAccessControl, type: 'boolean'},
                    featureEnableFeaturedImage: {value: this.initFeatureEnableFeaturedImage, type: 'boolean'},
                    featureEnablePasswordSupport: {value: this.initFeatureEnablePasswordSupport, type: 'boolean'},
                    featureEnableContentBlocks: {value: this.initFeatureEnableContentBlocks, type: 'boolean'},

                    editPageShowAuthor: {value: this.initEditPageShowAuthor, type: 'boolean'},
                    editPageShowContentInfo: {value: this.initEditPageShowContentInfo, type: 'boolean'},

                    indexPageDisplayContentAs: {value:  this.initIndexPageDisplayContentAs, type: 'string'},
                    indexPageGridColumns:  {value:  this.initIndexPageGridColumns, type: 'string'},
                    indexPageShowStatus: {value: this.initIndexPageShowStatus, type: 'boolean'},
                    indexPageShowAuthor: {value: this.initIndexPageShowAuthor, type: 'boolean'},
                    indexPageShowFeaturedImage: {value: this.initIndexPageShowFeaturedImage, type: 'boolean'},
                    indexPageShowCreatedUpdated: {value: this.initIndexPageShowCreatedUpdated, type: 'boolean'},
                }
            }
        },
        watch: {
        },
        methods: {
            save () {
                this.savingContentType = true
                this.saveBtnText = 'Saving'
                let settings = this.settings
                EventBus.$emit('content-type-save', settings)
            //   return axios.post('/api/blocks/save', this.blockList);
            }
        },
        mounted: function() {
            let self = this
            EventBus.$on('content-type-save-done', function() {
                self.savingContentType = false
                self.saveBtnText = 'Save'
            })
        },
        props: {
            initFeatureEnableFeaturedImage: {type: Boolean, default: true},
            initFeatureEnableAccessControl: {type: Boolean, default: false},
            initFeatureEnablePasswordSupport: {type: Boolean, default: false},
            initFeatureEnableContentBlocks: {type: Boolean, default: true},

            initEditPageShowAuthor: {type: Boolean, default: true},
            initEditPageShowContentInfo: {type: Boolean, default: true},

            initIndexPageDisplayContentAs: {type: String, default: 'list'},
            initIndexPageGridColumns: {type: String, default: 'column-4'},
            initIndexPageShowStatus: {type: Boolean, default: true},
            initIndexPageShowAuthor: {type: Boolean, default: true},
            initIndexPageShowFeaturedImage: {type: Boolean, default: true},
            initIndexPageShowCreatedUpdated: {type: Boolean, default: true},
        },
        components: {

        }
    }
</script>

<style scoped>
    .btn-primary {
        background-color: rgba(87, 113, 128, 0.09);
        border-color: rgba(54, 127, 169, 0);
    }

    .btn-primary:hover, .btn-primary:active, .btn-primary.hover {
        background-color: rgba(103, 137, 157, 0.19);
    }

    .btn-primary:hover {
        color: #fff;
        background-color: rgba(40, 96, 144, 0.42);
        border-color: rgba(32, 77, 116, 0.17);
    }
</style>

<style>
    .fa-arrows {
        cursor: move;
        font-size: 16px;
        margin-right: 8px;
    }

    .fa-cog {
        cursor: pointer;
        font-size: 16px;
        margin-right: 8px;
    }

    .content-block {
        margin-bottom: 10px;
    }

    .content-block-header {
        padding: 10px;
        background: rgba(0,0,0, 0.1)
    }
</style>
