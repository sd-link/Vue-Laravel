<template>
    <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{{ contentType.name }}</h3>
          <i @click="showModal=true" class="fa fa-cog" style="margin-left: 5px; font-size: 16px; cursor: pointer;" title="Display Settings" aria-hidden="true"></i>
          <div class="box-tools">
              <a :href="createNewUrl">
                <button class="btn btn-block btn-primary btn-sm">New {{ contentType.name_singular }}</button>
              </a>
          </div>
        </div>
        <div class="box-body">
            <div>
                <div style="border: 0px red solid; margin-bottom: 2px;">
                    <div style="border: 0px green solid;">
                        <div class="pull-left" style="font-weight: 700; font-size: 13px;">
                            <span @click="filterStatus('all')" class="status-count">All</span>: {{ this.allCount }}
                            <span @click="filterStatus('published')" class="status-count">Published</span>: {{ this.publishedCount }}
                            <span @click="filterStatus('scheduled')" class="status-count">Scheduled</span>: {{ this.scheduledCount }}
                            <span @click="filterStatus('drafts')" class="status-count">Drafts</span>: {{ this.draftCount}}
                        </div>

                        <div class="pull-right status-count">Found: {{ this.returnedCount }}</div>
                        <div style="clear: both;"></div>
                    </div>
                </div>


                <div class="search_options pull-left">
                    <div style="display: inline-flex;">
                        <div style="float: left; margin-right: 1px;"><input v-model="search" class="form-control" type="text" placeholder="Search" style="padding-left: 5px; width: 100%;"></div>
                        <div style="float: left; margin-right: 1px;"><select v-model="filter" style="width: 120px;" class="form-control">
                                <option value="title">Title</option>
                                <option value="username">Username</option>
                        </select></div>
                        <div @click="searchBtn()" style="float: left;"><button class="btn btn-primary">Search</button></div>
                    </div>
                </div>

                <div style="height: 24px; margin-bottom: 5px;" class="pull-right">
                    <vue-pagination v-show="pagination.last_page > 1"  v-bind:pagination="pagination"
                                     v-on:page-updated="getContent()"
                                     :offset="4">
                    </vue-pagination>
                </div>
                <div style="clear: both;"></div>
                <div :class="indexDisplay">
                    <!-- HEADER -->
                    <div v-if="indexDisplay == 'list'" class="row-header">
                        <div v-if="displayStatus" class="column status_column">Status</div>
                        <div v-if="displayAuthor" class="column author_column">Author</div>
                        <div class="column title_column">Title</div>
                        <div v-if="displayFeaturedImage" class="column featured_image_column">Featured Image</div>
                        <div v-if="displayCreatedUpdated" class="column created_column">Created</div>
                        <div v-if="displayCreatedUpdated" class="column updated_column">Updated</div>
                        <div class="column actions_column">Actions</div>
                    </div>

                    <!-- NORMAL Render -->
                    <div v-if="indexDisplay == 'list' || (indexDisplay == 'grid' && gridDisplayStyle=='normal')" v-for="content in contentList" class="row" :class="gridColumns">
                        <div v-if="displayStatus" class="column status_column status_list">
                            <span v-if="content.status==1" class="label-published">Published</span>
                            <span v-if="content.status==2" class="label-draft">Draft</span>
                            <span v-if="content.status==3" class="label-schedule">Scheduled</span>
                        </div>

                        <!-- AUTHOR -->
                        <div v-if="displayAuthor" class="column author_column">
                            {{content.author.firstname}} {{content.author.lastname}}
                        </div>

                        <!-- TITLE -->
                        <div class="column title_column">
                            <a :href="content.url" class="content_title">{{ content.title }}</a>
                        </div>

                        <!-- FEATURED -->
                        <div v-if="displayFeaturedImage && indexDisplay == 'list'" class="column featured_image_column">
                            <img v-if="content.featuredimage" class="featured_image img-responsive" :src="content.featuredimage">
                            <!-- <img v-else class="featured_image img-responsive" src="/images/no_featured_default.jpg" style="min-height: 220px;" v-bind:style="{'min-height': minImageHeight}"> -->
                        </div>

                        <!-- FEATURED -->
                        <div v-if="displayFeaturedImage && indexDisplay == 'grid'" class="column featured_image_column" v-bind:style="{'min-height': minFeaturedImageColumnHeight}">
                            <img v-if="content.featuredimage" class="featured_image img-responsive" :src="content.featuredimage">
                            <!-- <img v-else class="featured_image img-responsive" src="/images/no_featured_default.jpg" style="min-height: 220px;" v-bind:style="{'min-height': minImageHeight}"> -->
                        </div>

                        <div v-if="displayCreatedUpdated" class="column created_column">
                            {{ content.created_at }}
                        </div>

                        <div v-if="displayCreatedUpdated" class="column updated_column">
                            {{ content.updated_at }}
                        </div>

                        <!-- ACTIONS -->
                        <div class="column actions_column">
                            <a :href="content.url">
                                <button class="btn btn-xs btn-primary action-btn" type="button">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </a>

                            <button class="btn btn-xs btn-danger action-btn" type="button">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Grid Inline Render -->
                    <div v-if="indexDisplay == 'grid' && gridDisplayStyle=='inline'" v-for="content in contentList" class="row" :class="gridColumns" v-bind:style="{'height': inlineDivHeight}">
                        <div class="contents"
                        v-bind:style="{'cursor':'default', 'padding':'0', 'display':'flex', 'justify-content': 'space-between', 'flex-direction': 'column', 'background-image': imageUrl(content.featuredimage), 'width': '100%', 'background-size': 'cover','background-position': 'center'}">
                            <div class="inline_column"
                                style="margin: 0; flex: 1; align-items: center; justify-content: center; font-size: 30px;"
                                v-bind:style="{'font-size': gridTitleFontSize}">
                                <!-- AUTHOR -->
                                <div v-if="displayAuthor" class="author">
                                    {{content.author.firstname}} {{content.author.lastname}}
                                </div>

                                <!-- STATUS -->
                                <div v-if="displayStatus" class="status_inline">
                                    <span v-if="content.status==1" class="label-published">Published</span>
                                    <span v-if="content.status==2" class="label-draft">Draft</span>
                                    <span v-if="content.status==3" class="label-schedule">Scheduled</span>
                                </div>

                                <!-- TITLE -->
                                <a :href="content.url" class="content_title" style="text-align: center; color: #ffffff; text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);">{{ content.title }}</a>

                                <!-- ACTIONS -->
                                <div class="actions" style="font-size: 30px;">
                                    <a :href="content.url">
                                        <button class="btn btn-xs btn-primary action-btn" type="button">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </a>

                                    <button @click="deleteContent(content.uniqueId)" class="btn btn-xs btn-danger action-btn" type="button">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div v-if="indexDisplay == 'list'" class="row-header">
                        <div v-if="displayStatus" class="column status_column">Status</div>
                        <div v-if="displayAuthor" class="column author_column">Author</div>
                        <div class="column title_column">Title</div>
                        <div v-if="displayFeaturedImage" class="column featured_image_column">Featured Image</div>
                        <div v-if="displayCreatedUpdated" class="column created_column">Created</div>
                        <div v-if="displayCreatedUpdated" class="column updated_column">Updated</div>
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
                            <label>Items Per Page</label>
                            <input type="text" class="form-control" v-model="itemsPerPage">
                        </div>

                        <div class="form-group">
                            <label>Sort By</label>
                            <select class="form-control" v-model="sortBy">
                                <option value='latest'>Latest</option>
                                <option value='oldest'>Oldest</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Display as</label>
                            <select class="form-control" v-model="indexDisplay">
                                <option value='list'>List</option>
                                <option value='grid'>Grid</option>
                            </select>
                        </div>

                        <div v-show="indexDisplay == 'grid'" class="form-group">
                            <label>Display Style</label>
                            <select class="form-control" v-model="gridDisplayStyle">
                                <option value='inline'>Inline</option>
                                <option value='normal'>Normal</option>
                            </select>
                        </div>

                        <div v-show="indexDisplay == 'grid' && gridDisplayStyle == 'inline'" class="form-group">
                            <label>Title Size</label>
                            <px v-model="gridTitleFontSize"></px>
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
                            <label>Display Status</label>
                            <select class="form-control" v-model="displayStatus">
                                <option v-bind:value='true'>Yes</option>
                                <option v-bind:value='false'>No</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Display Author</label>
                            <select class="form-control" v-model="displayAuthor">
                                <option v-bind:value='true'>Yes</option>
                                <option v-bind:value='false'>No</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Display Featured Image</label>
                            <select class="form-control" v-model="displayFeaturedImage">
                                <option v-bind:value='true'>Yes</option>
                                <option v-bind:value='false'>No</option>
                            </select>
                        </div>

                        <div v-show="gridDisplayStyle == 'normal'" class="form-group">
                            <label>Display Created&Updated</label>
                            <select class="form-control" v-model="displayCreatedUpdated">
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
        </div>
    </div>
</template>

<script>
    import Modal from 'components/ui/Modal.vue'
    import Vue from 'vue'
    import Router from 'vue-router'
    Vue.use(Router)

    export default {
        data: function data() {
            return {
                modalDefaults: {
                    width: 600,
                    height: 650
                },
                state: {
                  modalData: {
                    top: 0,
                    left: 0,
                    width: null,
                    height: null
                  }
                },
                contentType: this.initContentType,
                showModal: false,
                modalTitle: this.initContentType.name+' Display Settings',
                contentList: [],

                indexDisplay: this.initIndexPageDisplay,
                gridColumns: this.initIndexPageGridColumns,
                gridDisplayStyle: this.initIndexPageGridStyle,
                itemsPerPage: this.initIndexPageItemsPerPage,
                sortBy: this.initIndexPageSortBy,
                displayAuthor: this.initIndexPageDisplayAuthor,
                displayStatus: this.initIndexPageDisplayStatus,
                displayCreatedUpdated: this.initIndexPageDisplayCreatedUpdated,
                displayFeaturedImage: this.initIndexPageDisplayFeaturedImage,
                gridTitleFontSize: this.initGridTitleFontSize,

                allCount: 0,
                returnedCount: 0,
                publishedCount: 0,
                draftCount: 0,
                scheduledCount: 0,

                pagination: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },

                status: 'all',
                search: null,
                filter: 'title',
                paging: true,
            }
        },
        props: {
            initContentType: {type: Object, default: null},
            initContentTypeId: {type: Number, default: 0},
            initIndexPageDisplay: {type: String, default: 'list'},
            initIndexPageGridColumns: {type: String, default: 'column-3'},
            initIndexPageGridStyle: {type: String, default: 'inline'},
            initIndexPageItemsPerPage: {type: Number, default: 12},
            initIndexPageSortBy: {type: String, default: 'latest'},
            initIndexPageDisplayStatus: {type: Boolean, default: true},
            initIndexPageDisplayAuthor: {type: Boolean, default: true},
            initIndexPageDisplayCreatedUpdated: {type: Boolean, default: true},
            initIndexPageDisplayFeaturedImage: {type: Boolean, default: true},
            initGridTitleFontSize: {type: String, default: '50px'},
        },
        watch: {
            itemsPerPage(val, old) {
                this.pagination.current_page = 1
                this.getContent()
            },
            sortBy(val, old) {
                this.pagination.current_page = 1
                this.getContent()
            },
        },
        computed: {
            createNewUrl: function() {
                return route('backend.content.create', {type: this.contentType.slug})
            },
            queryString: function() {
                let query = '?sort='+this.sortBy+'&status='+this.status+'&per_page='+this.itemsPerPage

                if(this.search) {
                    query += '&search='+this.search+'&filter='+this.filter
                    this.paging = false
                }

                if(this.paging)
                    query += '&page='+this.pagination.current_page

                return query
            },
            inlineDivHeight: function() {
                switch (this.gridColumns) {
                    case 'column-1':
                        return '255px';
                    break;
                    case 'column-2':
                        return '380px';
                    break;
                    case 'column-3':
                        return '310px';
                    break;
                    case 'column-4':
                        return '252px';
                    break;
                    default:
                }
            },
            minFeaturedImageColumnHeight: function() {
                switch (this.gridColumns) {
                    case 'column-1':
                        return '300px';
                    break;
                    case 'column-2':
                        return '468px';
                    break;
                    case 'column-3':
                        return '314px';
                    break;
                    case 'column-4':
                        return '237px';
                    break;
                    default:
                }
            }
        },
        methods: {
            searchBtn() {
                this.status = 'all'
                this.getContent()
            },
            filterStatus(val) {
                this.search = null
                this.status = val
                this.paging = false
                this.getContent()
            },
            imageUrl(url) {
                if(url && this.displayFeaturedImage)
                    return 'url('+url+')'
                else
                    return 'url()'

            },
            initContent(content) {
                let newContent = new Object()
                newContent.uniqueId = 'content-' + this.contentList.length
                newContent.id = content.id
                newContent.status = content.status
                newContent.author = content.author
                newContent.title = content.title

                newContent.created_at = content.created_at
                newContent.updated_at = content.updated_at

                if(content.featuredimage)
                    newContent.featuredimage = content.featuredimage.grid_large

                newContent.url = route('backend.content.edit', {type: this.contentType.slug, id: content.id})
                this.contentList.push(newContent)
            },
            getContent() {
                axios.get(route('backend.content.get.posts', {contentTypeId: this.contentType.id})+this.queryString)
                .then((response) => {
                    this.contentList = []

                    for (var i = 0; i < response.data.content.data.length; i++) {
                        this.initContent(response.data.content.data[i])
                    }

                    // update url
                    // if (history.pushState) {
                    //     // console.log('updating url')
                    //     var path = window.location.pathname
                    //     var newurl = window.location.protocol + "//" + window.location.host + path + this.queryString
                    //     window.history.pushState({path:newurl},'',newurl)
                    // }

                    this.allCount = response.data.counts.allCount
                    this.returnedCount = response.data.counts.returnedCount
                    this.publishedCount = response.data.counts.publishedCount
                    this.draftCount = response.data.counts.draftCount
                    this.scheduledCount = response.data.counts.scheduledCount

                    this.pagination = response.data.content
                })
                .catch((error) => {
                    console.log(error)
                })
            },
            saveSettings() {
                this.showModal = false

                let settings = {
                    indexPageDisplay: {type: 'string', value: this.indexDisplay},
                    indexPageGridColumns: {type: 'string', value: this.gridColumns},
                    indexPageGridStyle: {type: 'string', value: this.gridDisplayStyle},
                    indexPageItemsPerPage: {type: 'integer', value: this.itemsPerPage},
                    indexPageSortBy: {type: 'string', value: this.sortBy},
                    indexPageDisplayAuthor: {type: 'boolean', value: this.displayAuthor},
                    indexPageDisplayStatus: {type: 'boolean', value: this.displayStatus},
                    indexPageDisplayCreatedUpdated: {type: 'boolean', value: this.displayCreatedUpdated},
                    indexPageDisplayFeaturedImage: {type: 'boolean', value: this.displayFeaturedImage},
                }

                let formData = {
                    contentTypeId: this.contentType.id,
                    settings: settings,
                }

                return axios.post(route('backend.content.settings.save'), formData)
                    .then((response) => {
                    })
                    .catch((error) => {
                    })

            },
        },
        mounted: function() {

            // window.addEventListener("popstate", function(e) {
            //     var path = location.pathname
            //     console.log(path)
            //     path = path.replace(/create\b/g, "")
            //     console.log(window.location.protocol + "//" + window.location.host + path)
            //     // window.location = window.location.protocol + "//" + window.location.host + path
            // });

            this.getContent()
        },
        components: {
            Modal,
        }
    }
</script>

<style lang="less" scoped>
    .search_options {
        margin-bottom: 5px;
    }

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
        background: #3c8dbc;
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

    .status_list {
        font-size: 14px;
        color: white;
        text-shadow: rgba(0, 0, 0, 0.3) 1px 1px 1px;
    }

    .status_inline {
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
