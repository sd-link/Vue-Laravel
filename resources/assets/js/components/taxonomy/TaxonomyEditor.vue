<template>
    <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{{ taxonomy.name }}</h3>
          <div class="box-tools">
              <a href="#">
                    <button @click="openNewTermModal(newTerm)" class="btn btn-block btn-primary btn-sm">New {{ taxonomy.name_singular }}</button>
              </a>
          </div>
        </div>
        <div class="box-body">

            <div class="list terms">
                    <!-- HEADER -->
                    <div class="row-header">
                        <div class="column name_column">Name</div>
                        <div class="column description_column">Description</div>
                        <div class="column actions_column">Actions</div>
                    </div>

                    <div v-for="term in termsList" class="row">
                        <!-- TITLE -->
                        <div class="column name_column">
                            <span>{{ term.name }}</span>
                        </div>

                        <div class="column description_column">
                            {{ term.description }}
                        </div>

                        <!-- ACTIONS -->
                        <div class="column actions_column">
                            <button @click="editTermModal(term)" class="btn btn-xs btn-primary action-btn" type="button">
                                <span style="text-align: center; color: #ffffff; text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);">
                                    <i class="fa fa-edit"></i>
                                </span>
                            </button>

                            <button @click="deleteTermModal(term)" class="btn btn-xs btn-danger action-btn" type="button">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>

                <!-- FOOTER -->
                <div class="row-header">
                    <div class="column name_column">Name</div>
                    <div class="column description_column">Description</div>
                    <div class="column actions_column">Actions</div>
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
                        <label>Name</label>
                        <input type="text" class="form-control" v-model="editedTerm.name">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" v-model="editedTerm.description">
                    </div>
                </div>
                <div class="modal-footer" slot="modal-footer">
                    <button v-if="modalMode=='update'" type="button" class="btn btn-primary" @click="save()">Update</button>
                    <button v-if="modalMode=='create'" type="button" class="btn btn-primary" @click="createNewTerm()">Create</button>
                </div>
            </modal>

            <modal ref="settingsModal" title="Delete"
              v-model="showDeleteModal"
              v-bind="state.modalData"
              :draggable="true"
              defaultWidth="500"
              defaultHeight="200">
                <div class="modal-body" slot="modal-body">
                    <div class="form-group text-center" style="margin-top: 20px;">
                        <h3>Delete <b>{{ this.termToDelete.name }}</b>?</h3>
                    </div>
                </div>
                <div class="modal-footer" slot="modal-footer">
                    <button type="button" class="btn btn-primary" @click="deleteTerm()">Make It So</button>
                </div>
            </modal>

        </div>
    </div>
</template>

<script>
    import Modal from 'components/ui/Modal.vue'

    export default {
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
                    height: 280
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
                showDeleteModal: false,
                modalMode: 'create',
                termsList: [],
                taxonomy: this.initTaxonomy,
                editedTerm: [],
                termToDelete: [],
                newTerm: {
                    id: 0,
                    name: '',
                    description: '',
                    taxonomy_id: this.initTaxonomy.id
                },
            }
        },
        props: {
            initTaxonomy: {type: Object, default: null},
        },
        watch: {
        },
        computed: {
            modalTitle: function() {
                if(this.modalMode == 'create')
                    return 'Create'
                else
                    return 'Edit'
            }
        },
        methods: {
            openNewTermModal(term) {
                this.editedTerm = term
                this.modalMode = 'create'
                this.showModal = true
            },
            createNewTerm() {
                let newTerm = new Object()
                newTerm.uniqueId = 'term-' + this.termsList.length
                newTerm.id = this.editedTerm.id
                newTerm.name = this.editedTerm.name
                newTerm.description = this.editedTerm.description
                newTerm.taxonomy_id = this.editedTerm.taxonomy_id
                this.termsList.push(newTerm)

                this.save()
            },
            deleteTermModal(term) {
                this.termToDelete = term
                this.showDeleteModal = true
            },
            editTermModal(termToEdit) {
                console.log(termToEdit)
                this.editedTerm = termToEdit
                this.modalMode = 'update'
                this.showModal = true
            },
            initTerm(term) {
                let newTerm = new Object()
                newTerm.uniqueId = 'term-' + this.termsList.length
                newTerm.id = term.id
                newTerm.name = term.name
                newTerm.description = term.description
                newTerm.taxonomy_id = term.taxonomy_id
                this.termsList.push(newTerm)
            },
            getTerms() {
                axios.get(route('backend.content.taxonomy.terms', {type: 'posts', slug: 'tags'}))
                .then((response) => {
                    this.termsList = []
                    for (var i = 0; i < response.data.terms.length; i++) {
                        this.initTerm(response.data.terms[i])
                    }
                })
                .catch((error) => {
                    console.log(error)
                })
            },
            save() {
                this.showModal = false
                console.log(this.editedTerm.name)

                let formData = {
                    term: this.editedTerm,
                }

                return axios.post(route('backend.content.taxonomy.term.save'), formData)
                    .then((response) => {
                    })
                    .catch((error) => {
                    })
            },
            deleteTerm() {
                this.showDeleteModal = false
                let uniqueId = this.termToDelete.uniqueId
                
                this.termsList = $.grep(this.termsList, function(term) {
                    return term.uniqueId != uniqueId
                })

                let formData = {
                    term: this.termToDelete,
                }

                return axios.post(route('backend.content.taxonomy.term.delete'), formData)
                    .then((response) => {
                    })
                    .catch((error) => {
                    })
            }
        },
        mounted: function() {

            // window.addEventListener("popstate", function(e) {
            //     var path = location.pathname
            //     console.log(path)
            //     path = path.replace(/create\b/g, "")
            //     console.log(window.location.protocol + "//" + window.location.host + path)
            //     // window.location = window.location.protocol + "//" + window.location.host + path
            // });

            this.getTerms()
        },
        components: {
            Modal,
        }
    }
</script>

<style lang="less" scoped>
    .contents {
        position: relative;
        padding: 0;
        margin: 0px;
    }

    .inline_column {
        display: flex;
        &:hover {
            background-color: rgba(0,0,0, 0.1)
        }
    }

    .status-count {
        cursor: pointer;
        color: #3c8dbc;
        font-weight: bold;
    }

    .label-published, .label-draft, .label-schedule, .label-created_at, .label-updated_at {
        background: #00c0ef;
        border-radius: 0.25em;
        color: #fff;
        display: inline-block;
        font-size: 95%;
        font-weight: 700;
        padding: 3px 8px;
        text-align: center;
    }

    .author {
        padding: 0;
        border: 0;
        position: absolute;
        left: 10px;
        top: 10px;
        font-size: 14px;
        color: white;
        text-shadow: rgba(0, 0, 0, 0.3) 1px 1px 1px;
    }

    .status {
        padding: 0;
        border: 0;
        position: absolute;
        right: 10px;
        top: 10px;
        font-size: 14px;
        color: white;
        text-shadow: rgba(0, 0, 0, 0.3) 1px 1px 1px;
    }

    .actions {
        padding: 0;
        border: 0;
        position: absolute;
        right: 10px;
        bottom: 10px;
    }
</style>
