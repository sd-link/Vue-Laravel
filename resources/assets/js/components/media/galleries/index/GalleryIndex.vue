<template>
    <div>
        <i @click="showModal=true" class="fa fa-cog" style="font-size: 18px; cursor: pointer;" title="Display Settings" aria-hidden="true"></i>
        <div style="height: 24px; margin-bottom: 5px;" class="pull-right">
            <vue-pagination  v-bind:pagination="pagination"
                             v-on:page-updated="getGalleries(pagination.current_page)"
                             :offset="4">
            </vue-pagination>
        </div>
        <div style="clear: both;">

        </div>
        <div id="galleries" class="gallery" :class="indexDisplay">
                <!-- HEADER -->
                <div v-if="indexDisplay == 'list'" class="row-header">
                    <div v-if="displayAuthor" class="column author_column">Author</div>
                    <div class="column title_column">Title</div>
                    <div class="column featured_image_column">Preview</div>
                    <div class="column actions_column">Actions</div>
                </div>


                <div v-if="indexDisplay == 'list' || (indexDisplay == 'grid' && gridStyle=='default')" v-for="gallery in galleryList" class="row" :class="gridColumns">
                    <!-- AUTHOR -->
                    <div v-if="displayAuthor" class="column author_column">
                        {{gallery.author.firstname}} {{gallery.author.lastname}}
                    </div>

                    <!-- TITLE -->
                    <div class="column title_column">
                        <a :href="gallery.url" class="content_title">{{ gallery.title }}</a>
                    </div>

                    <!-- FEATURED -->
                    <div class="column featured_image_column">
                        <img v-if="gallery.firstImage" class="featured_image img-responsive" :src="gallery.firstImage">
                        <img v-else class="featured_image img-responsive" src="" style="min-height: 220px;" v-bind:style="{'min-height': minImageHeight}">
                    </div>

                    <!-- ACTIONS -->
                    <div class="column actions_column">
                        <button class="btn btn-xs btn-primary action-btn" type="button">
                            <a :href="gallery.url" style="text-align: center; color: #ffffff; text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);">
                                <i class="fa fa-edit"></i>
                            </a>
                        </button>

                        <button class="btn btn-xs btn-danger action-btn" type="button">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>

                <div v-if="indexDisplay == 'grid' && gridStyle=='inline'" v-for="gallery in galleryList" class="row" :class="gridColumns" v-bind:style="{'height': inlineDivHeight}">
                    <div class="gallery" v-bind:style="{'cursor':'default', 'padding':'0', 'display':'flex', 'justify-content': 'space-between', 'flex-direction': 'column', 'background-image': imageUrl(gallery.firstImage), 'width': '100%', 'background-size': 'cover','background-position': 'center'}">
                        <div class="column title_column inline_column" style="margin: 0; flex: 1; align-items: center; justify-content: center; font-size: 30px;">
                            <!-- AUTHOR -->
                            <div v-if="displayAuthor" class="author">
                                {{gallery.author.firstname}} {{gallery.author.lastname}}
                            </div>
                            <!-- TITLE -->
                            <a :href="gallery.url" class="content_title" style="text-align: center; color: #ffffff; text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);">{{ gallery.title }}</a>

                            <!-- ACTIONS -->
                            <div class="actions">
                                <button class="btn btn-xs btn-primary action-btn" type="button">
                                    <a :href="gallery.url" style="text-align: center; color: #ffffff; text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </button>

                                <button class="btn btn-xs btn-danger action-btn" type="button">
                                    <i class="fa fa-trash" style="text-align: center; color: #ffffff; text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);"></i>
                                </button>
                            </div>
                        </div>


                    </div>

                </div>

            <!-- FOOTER -->
            <div v-if="indexDisplay == 'list'" class="row-header">
                <div v-if="displayAuthor" class="column author_column">Author</div>
                <div class="column title_column">Title</div>
                <div class="column featured_image_column">Preview</div>
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
                    <label>Per Page</label>
                    <input type="text" class="form-control" v-model="itemsPerPage">
                </div>

                <div class="form-group">
                    <label>Display as</label>
                    <select class="form-control" v-model="indexDisplay">
                        <option value='list'>List</option>
                        <option value='grid'>Grid</option>
                    </select>
                </div>

                <div v-show="indexDisplay == 'grid'" class="form-group">
                    <label>Display as</label>
                    <select class="form-control" v-model="gridStyle">
                        <option value='default'>Default</option>
                        <option value='inline'>Inline</option>
                    </select>
                </div>

                <div v-show="indexDisplay == 'grid'" class="form-group">
                    <label>Columns</label>
                    <select class="form-control" v-model="gridColumns">
                        <option value='column-1'>1</option>
                        <option value='column-2'>2</option>
                        <option value='column-3'>3</option>
                        <option value='column-4'>4</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Display Author</label>
                    <select class="form-control" v-model="displayAuthor">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div>



            </div>

            <div class="modal-footer" slot="modal-footer">
                <button type="button" class="btn btn-primary" @click="saveSettings">Save</button>
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
                    height: 450
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
                galleryList: [],
                indexDisplay: this.initIndexDisplay,
                gridColumns: this.initGridColumns,
                gridStyle: this.initGridStyle,
                itemsPerPage: this.initItemsPerPage,
                displayAuthor: this.initDisplayAuthor,
                galleries: this.initGalleries,
                pagination: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
            }
        },
        props: {
            initIndexDisplay: {type: String, default: 'grid'},
            initGridColumns: {type: String, default: 'column-3'},
            initGridStyle: {type: String, default: 'inline'},
            initItemsPerPage: {type: Number, default: 3},
            initDisplayAuthor: {type: Boolean, default: true},
        },
        watch: {
            itemsPerPage(val, old) {
                this.getGalleries(1)
            }
        },
        computed: {
            inlineDivHeight: function() {
                switch (this.gridColumns) {
                    case 'column-1':
                        return '222px';
                    break;
                    case 'column-2':
                        return '380px';
                    break;
                    case 'column-3':
                        return '310px';
                    break;
                    case 'column-4':
                        return '217px';
                    break;
                    default:
                }
            },
            minImageHeight: function() {
                switch (this.gridColumns) {
                    case 'column-1':
                        return '380px';
                    break;
                    case 'column-2':
                        return '380px';
                    break;
                    case 'column-3':
                        return '291px';
                    break;
                    case 'column-4':
                        return '217px';
                    break;
                    default:
                }
            }
        },
        methods: {
            imageUrl(url) {
                if(url)
                    return 'url('+url+')'
                else
                    return 'url()'

            },
            initGallery(gallery) {
                let newGallery = new Object()
                newGallery.uniqueId = 'gallery-' + this.galleryList.length
                newGallery.id = gallery.id
                newGallery.author = gallery.author
                newGallery.title = gallery.title
                newGallery.caption = gallery.caption
                if(gallery.firstImage)
                    newGallery.firstImage = gallery.firstImage.grid_large
                newGallery.url = route('backend.media.galleries.edit', {id: gallery.id})
                this.galleryList.push(newGallery)
            },
            getGalleries(page) {
                axios.get(route('backend.media.galleries.get')+'?page='+page+'&per_page='+this.itemsPerPage)
                .then((response) => {
                    this.galleryList = []

                    for (var i = 0; i < response.data.data.length; i++) {
                        this.initGallery(response.data.data[i])
                    }

                    this.pagination = response.data
                })
                .catch((error) => {
                    console.log(error)
                })
            },
            saveSettings() {
                this.showModal = false

                let settings = {
                    indexDisplay: {type: 'string', value: this.indexDisplay},
                    gridColumns: {type: 'string', value: this.gridColumns},
                    gridStyle: {type: 'string', value: this.gridStyle},
                    itemsPerPage: {type: 'integer', value: this.itemsPerPage},
                    displayAuthor: {type: 'boolean', value: this.displayAuthor},
                }

                let formData = {
                    settings: settings,
                }

                return axios.post(route('backend.media.galleries.settings.save'), formData)
                    .then((response) => {
                    })
                    .catch((error) => {
                    })

            },
        },
        mounted: function() {
            this.getGalleries(1)
        },
        components: {
            Modal,
        }
    }
</script>

<style lang="less" scoped>
    .gallery {
        position: relative;
        padding: 0;
    }

    .inline_column {
        &:hover {
            background-color: rgba(0,0,0, 0.1)
        }
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

    .actions {
        padding: 0;
        border: 0;
        position: absolute;
        right: 10px;
        bottom: 10px;
    }
</style>
