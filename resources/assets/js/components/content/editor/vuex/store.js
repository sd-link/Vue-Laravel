import Vue from 'vue';
import Vuex from 'vuex';
import { processSettings, processSettingsConfig, getComponentByName, differenceSettings } from 'utils/helpers.js'

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        userId: 1,
        editorMode: 'create',
        contentData: {
            contentId: 0,
            contentTitle: '',
            contentTypeSlug: '',
            contentTypeId: 0,
            publishedAt: null,
            createdAt: null,
            updatedAt: null,
            publishAt: null,
            featuredImage: null,
        },
        blockItems: {},
        rootItemsList: [],
        removedItemsList: [],
    },
    getters: {
        editorMode(state) {
            return state.editorMode
        },
        contentTitle(state) {
            return state.contentData.contentTitle
        },
        contentData(state) {
            return state.contentData
        },
        contentId(state) {
            return state.contentData.contentId
        },
        publishedAt(state) {
            return state.contentData.publishedAt
        },
        publishAt(state) {
            return state.contentData.publishAt
        },
        contentTypeId(state) {
            return state.contentData.contentTypeId
        },
        contentTypeSlug(state) {
            return state.contentData.contentTypeSlug
        },
        rootItems(state) {
            return state.rootItemsList.map( id => state.blockItems[id] )
        },
        removedItems(state) {
            return state.removedItemsList
        },
        items: (state, getters) => (parentId) => {
            if (!parentId) return []
            let parent = state.blockItems[parentId]
            let components = parent.components.map( componentId => state.blockItems[componentId] )
            return components
        },
        item: (state, getters) => (uniqueId) => {
            if (!uniqueId) return []
            let component = state.blockItems[uniqueId]
            return component
        },
        itemSettings: (state, getters) => (uniqueId) => {
            if (!uniqueId) return []
            let component = state.blockItems[uniqueId]
            return component.settings
        },
        itemContent: (state, getters) => (uniqueId) => {
            if (!uniqueId) return []
            let component = state.blockItems[uniqueId]
            return component.content
        }
    },
    mutations: {
        'SET_EDITOR_MODE'(state, payload) {
            state.editorMode = payload
        },
        'SET_CONTENT_DATA'(state, payload) {
            state.contentData = {
                ...state.contentData,
                ...payload
            }
        },
        'ADD_ITEM'(state, payload) {
            let { type, title, content, settingsConfig, settings, parentId, id, tId } = payload
            let uniqueId = id || +(state.userId+Date.now()+_.uniqueId())
            let templateBlockId = tId || undefined

            let newItem = {
                title: title,
                type: type,
                uniqueId: uniqueId,
                templateBlockId: templateBlockId,
                settingsConfig: settingsConfig,
                settings: settings,
                content: content,
                components: []
            }

            Vue.set(state.blockItems, uniqueId, newItem)
            // console.log('ADD_ITEM: ' + type)
            if (parentId) {
                // console.log('if(parentID)')
                let parent = state.blockItems[parentId]
                parent.components.push(uniqueId)
            } else {
                // console.log('else')
                state.rootItemsList.push(uniqueId)
            }
        },
        'REMOVE_ITEM'(state, payload) {
            let { id, parentId } = payload
            state.removedItemsList.push(id)

            if (parentId) {
                let parent = state.blockItems[parentId]
                var index = parent.components.indexOf(id)
                if(index >= 0) {
                    parent.components.splice(index, 1)
                    Vue.delete(state.blockItems, id)
                }
            } else {
                var index = state.rootItemsList.indexOf(id)
                state.rootItemsList.splice(index, 1)
                Vue.delete(state.blockItems, id)
            }
        },
        'UPDATE_ITEM'(state, payload) {
            let { data, uniqueId } = payload

            state.blockItems[uniqueId] = {
                ...state.blockItems[uniqueId],
                ...data
            }
        },
        'UPDATE_ITEM_SETTINGS'(state, payload) {
            let { settings, uniqueId } = payload

            let component = state.blockItems[uniqueId]
            component.settings = settings
        },
        'UPDATE_ITEM_CONTENT'(state, payload) {
            let { content, uniqueId } = payload

            let component = state.blockItems[uniqueId]
            component.content = content
        },
        'UPDATE_ITEMS_LIST'(state, payload) {
            let { list, id } = payload

            if (id) {
                let item = state.blockItems[id]
                item.components = list
            } else {
                state.rootItemsList = list
            }
        }
    },
    actions: { // actions is great for asynchronous call
        setEditorMode({ commit }, payload) {
            commit('SET_EDITOR_MODE', payload)
        },
        setContentData({ commit }, payload) {
            commit('SET_CONTENT_DATA', payload)
        },
        addItem({ commit }, payload) {
            commit('ADD_ITEM', payload)
        },
        removeItem({ commit }, payload) {
            commit('REMOVE_ITEM', payload)
        },
        updateItemSettings({ commit }, payload) {
            commit('UPDATE_ITEM_SETTINGS', payload)
        },
        updateItemContent({ commit }, payload) {
            commit('UPDATE_ITEM_CONTENT', payload)
        },
        updateItemsList({ commit }, payload) {
            commit('UPDATE_ITEMS_LIST', payload)
        },
        saveAll({ commit, getters, state }) {
            let prepareDataBeforeSave = function(data) {
                if (!data) return []
                return data.map((object, key) => {
                    let settingsForUpdate = differenceSettings(object.settings, object.settingsConfig)
                    let settings = _.mapValues(settingsForUpdate, (value, key) => {
                        let type = (object.settingsConfig && object.settingsConfig[key] && object.settingsConfig[key].type)
                            ? (object.settingsConfig[key].type).name.toLowerCase() // get type in string format
                            : 'string'
                        return {
                            type: type,
                            value: value
                        }
                    })
                    // console.log('SETTINGS SAVE 1', settings)
                    return {
                        type: object.type,
                        uniqueId: object.uniqueId,
                        templateBlockId: object.templateBlockId,
                        title: object.title,
                        content: object.content,
                        settings: settings,
                        subItems: prepareDataBeforeSave( getters.items(object.uniqueId) ),
                        order: (key+1)
                    }
                })
            }

            let formData = {
                title: getters.contentTitle,
                id: getters.contentId,
                status: 1,
                publish_at: getters.publishAt,
                contentTypeId: getters.contentTypeId,
                blocksData: prepareDataBeforeSave(getters.rootItems),
                removedItems: getters.removedItems,
            }

            return axios.post(route('backend.content.save'), formData)
                .then((response) => {
                    if (history.pushState && getters.contentId == 0) {
                        // console.log('updating url')
                        var path = window.location.pathname
                        path = path.replace(/create\b/g, "edit/")
                        var newurl = window.location.protocol + "//" + window.location.host + path + response.data.content.id
                        window.history.pushState({path:newurl},'',newurl)
                    }

                    commit('SET_EDITOR_MODE', 'edit')
                    let payload = {
                        contentId: response.data.content.id,
                        publishedAt: moment.utc(response.data.content.published_at, 'YYYY-MM-DD HH:mm').local().format('YYYY-MM-DD HH:mm'),
                        createdAt: moment.utc(response.data.content.created_at, 'YYYY-MM-DD HH:mm').local().format('YYYY-MM-DD HH:mm'),
                        updatedAt: moment.utc(response.data.content.updated_at, 'YYYY-MM-DD HH:mm').local().format('YYYY-MM-DD HH:mm'),
                    }
                    commit('SET_CONTENT_DATA', payload)
                })
                .catch((error) => {
                    console.error('API error', error)
                })
        },
        fetchContentBlocks({ commit, getters, state }, payload) {
            let { mode } = payload
            let isDefault = mode && mode == 'default'
            let apiUrl = isDefault
                ? route('backend.content.get.template.default', {type: getters.contentTypeSlug})
                : route('backend.content.get.content', {id: getters.contentId})

            axios.get(apiUrl)
                .then((response) => {
                    let replacedIds = {}
                    if(!isDefault) {
                        let payload = {
                            featuredImage: response.data.featuredImage,
                        }
                        commit('SET_CONTENT_DATA', payload)
                    }

                    _.forEach(_.sortBy(response.data.blocks, [( o ) => { return o.parent_id || 0},'order']), function(object) {
                        let comp = getComponentByName(object.type)
                        if (!comp) {
                            alert('Wrong component\'s name')
                            return false
                        }

                        let customSettings = processSettingsConfig(object.type)
                        let id
                        let templateBlockId
                        let title

                        if (isDefault) {
                            id = +(state.userId+Date.now()+_.uniqueId())
                            replacedIds[object.unique_id] = id
                            templateBlockId = object.id
                            title = object.settings[0]['value']
                        } else {
                            id = Number(object.unique_id)
                            title = object.title
                            templateBlockId = object.tblock_id
                        }

                        commit('ADD_ITEM', {
                            type: object.type,
                            title: title,
                            id: id,
                            tId: templateBlockId,
                            settingsConfig: customSettings,
                            content: object.content ? object.content : undefined,
                            settings: _.defaults(
                                processSettings(object.settings),
                                _.mapValues(customSettings, object => (object.default === undefined)? null : object.default)
                            ),
                            parentId: replacedIds[object.parent_id] ? replacedIds[object.parent_id] : object.parent_id
                        })
                    })
                })
                .catch((error) => {
                    console.error('API error', error)
                })
        }
    }
})
