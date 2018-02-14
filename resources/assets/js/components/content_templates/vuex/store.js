import Vue from 'vue';
import Vuex from 'vuex';
import { processSettings, processSettingsConfig, getComponentByName, difference } from 'utils/helpers.js'

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        userId: 1,
        templateData: {
            contentTypeId: 0,
            templateId: 0,
            templateName: '',
            contentTypeSlug: '',
            defaultTemplate: false,
        },

        editorMode: 'create',
        blockItems: {},
        rootItemsList: [],
        removedItemsList: [],
    },
    getters: {
        editorMode(state) {
            return state.editorMode
        },
        templateData(state) {
            return state.templateData
        },
        defaultTemplate(state) {
            return state.templateData.defaultTemplate
        },
        contentTypeId(state) {
            return state.templateData.contentTypeId
        },
        templateId(state) {
            return state.templateData.templateId
        },
        templateName(state) {
            return state.templateData.templateName
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
        'SET_TEMPLATE_ID'(state, payload) {
            state.templateData.templateId = payload
        },
        'SET_TEMPLATE_NAME'(state, payload) {
            state.templateData.templateName = payload
        },
        'SET_CONTENT_TYPE_ID'(state, payload) {
            state.templateData.contentTypeId = payload
        },
        'SET_TEMPLATE_DATA'(state, payload) {
            let { templateName, templateId, contentTypeId, defaultTemplate } = payload

            state.templateData = {
                ...state.templateData,
                ...payload
            }
        },
        'ADD_ITEM'(state, payload) {
            let { type, settingsConfig, settings, parentId, content, id } = payload
            let uniqueId = id || +(state.userId+Date.now()+_.uniqueId())

            let newItem = {
                type: type,
                uniqueId: uniqueId,
                settingsConfig: settingsConfig,
                initialSettings: _.clone(settings),
                settings: settings,
                content: content,
                components: []
            }

            Vue.set(state.blockItems, uniqueId, newItem)

            if (parentId) {
                let parent = state.blockItems[parentId]
                parent.components.push(uniqueId)
            } else {
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
        'UPDATE_ITEM_CONTENT'(state, payload) {
            let { content, uniqueId } = payload

            let component = state.blockItems[uniqueId]
            component.content = content
        },
        'UPDATE_ITEM_SETTINGS'(state, payload) {
            let { settings, uniqueId } = payload

            let component = state.blockItems[uniqueId]
            component.settings = settings
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
        setTemplateId({ commit }, payload) {
            commit('SET_TEMPLATE_ID', payload)
        },
        setTemplateName({ commit }, payload) {
            commit('SET_TEMPLATE_NAME', payload)
        },
        setContentTypeId({ commit }, payload) {
            commit('SET_CONTENT_TYPE_ID', payload)
        },
        setTemplateData({ commit }, payload) {
            commit('SET_TEMPLATE_DATA', payload)
        },
        addItem({ commit }, payload) {
            commit('ADD_ITEM', payload)
        },
        removeItem({ commit }, payload) {
            commit('REMOVE_ITEM', payload)

            // let formData = {
            //     unique_id: payload.id
            // }

            // return axios.post(route('backend.content.templates.block.delete'), formData)
            //     .then((response) => {
            //         // console.log('success', response)
            //     })
            //     .catch((error) => {
            //         console.error('API error', error)
            //     })
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
                    let settingsForUpdate = difference(object.settings, object.initialSettings)
                    let settings = _.mapValues(settingsForUpdate, (value, key) => {
                        let type = (object.settingsConfig && object.settingsConfig[key] && object.settingsConfig[key].type)
                            ? (object.settingsConfig[key].type).name.toLowerCase() // get type in string format
                            : 'string'
                        return {
                            type: type,
                            value: value
                        }
                    })
                    console.log('CONT TEMP SAVE 1', settings)
                    return {
                        type: object.type,
                        uniqueId: object.uniqueId,
                        settings: settings,
                        content: object.content,
                        subItems: prepareDataBeforeSave( getters.items(object.uniqueId) ),
                        order: (key+1)
                    }
                })
            }

            let formData = {
                name: getters.templateName,
                id: getters.templateId,
                contentTypeId: getters.contentTypeId,
                blocksData: prepareDataBeforeSave(getters.rootItems),
                removedItems: getters.removedItems,
                defaultTemplate: getters.defaultTemplate,
            }
            return axios.post(route('backend.content.templates.save'), formData)
                .then((response) => {
                    if (history.pushState && getters.templateId == 0) {
                        // console.log('updating url')
                        var path = window.location.pathname
                        path = path.replace(/create\b/g, "edit/")
                        var newurl = window.location.protocol + "//" + window.location.host + path + response.data.template.id
                        window.history.pushState({path:newurl},'',newurl)
                    }

                    commit('SET_EDITOR_MODE', 'edit')
                    commit('SET_TEMPLATE_ID', response.data.template.id)
                    console.log('saved')
                })
                .catch((error) => {
                    console.error('API error', error)
                })
        },
        fetchAll({ commit, getters, state }, payload) {
            let {templateId} = payload
            axios.get(route('backend.content.templates.blocks', {id: templateId}))
                .then((response) => {
                    // console.log('success', response)
                    _.forEach(_.sortBy(response.data.blocks, [( o ) => { return o.parent_id || 0},'order']), function(object) {
                        let comp = getComponentByName(object.type)
                        if (!comp) {
                            alert('Wrong component\'s name')
                            return false
                        }

                        let customSettings = processSettingsConfig(object.type)

                        commit('ADD_ITEM', {
                            type: object.type,
                            id: Number(object.unique_id),
                            settingsConfig: customSettings,
                            content: object.content ? object.content : undefined,
                            settings: _.defaults(
                                processSettings(object.settings),
                                _.mapValues(customSettings, object => (object.default === undefined)? null : object.default)
                            ),
                            parentId: object.parent_id
                        })
                    })
                })
                .catch((error) => {
                    console.error('API error', error)
                })
        }
    }
})
