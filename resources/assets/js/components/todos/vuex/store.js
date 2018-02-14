import Vue from 'vue';
import Vuex from 'vuex';
import { processSettings } from 'utils/helpers.js'

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        userId: 1,
        todoItems: {},
        rootList: []
    },
    getters: {
        rootItems(state) {
            return state.rootList.map( id => state.todoItems[id] )
        },
        items: (state, getters) => (parentId) => {
            if (!parentId) return []
            let parent = state.todoItems[parentId]
            let components = parent.components.map( componentId => state.todoItems[componentId] )
            return components
        },
        item: (state, getters) => (uniqueId) => {
            if (!uniqueId) return []
            let component = state.todoItems[uniqueId]
            return component
        },
        itemSettings: (state, getters) => (uniqueId) => {
            if (!uniqueId) return []
            let component = state.todoItems[uniqueId]
            return component.settings
        }
    },
    mutations: {
        'ADD_ITEM'(state, payload) {
            let { type, settingsConfig, settings, parentId, id } = payload
            let uniqueId = id || +(state.userId+Date.now()+_.uniqueId())

            let newTodo = {
                type: type,
                uniqueId: uniqueId,
                settingsConfig: settingsConfig,
                settings: settings,
                components: []
            }

            Vue.set(state.todoItems, uniqueId, newTodo)
            if (parentId) {
                let parent = state.todoItems[parentId]
                parent.components.push(uniqueId)
            } else {
                state.rootList.push(uniqueId)
            }
        },
        'UPDATE_ITEM'(state, payload) {
            let { data, uniqueId } = payload

            state.todoItems[uniqueId] = {
                ...state.todoItems[uniqueId],
                ...data
            }
        },
        'UPDATE_ITEM_SETTINGS'(state, payload) {
            let { settings, uniqueId } = payload

            let component = state.todoItems[uniqueId]
            component.settings = settings
        },
        'UPDATE_TODOS_LIST'(state, payload) {
            let { list, id } = payload

            if (id) {
                let item = state.todoItems[id]
                item.components = list
            } else {
                state.rootList = list
            }
        }
    },
    actions: { // actions is great for asynchronous call
        addItem({ commit }, payload) {
            commit('ADD_ITEM', payload)
        },
        updateItemSettings({ commit }, payload) {
            commit('UPDATE_ITEM_SETTINGS', payload)
        },
        updateTodosList({ commit }, payload) {
            commit('UPDATE_TODOS_LIST', payload)
        },
        saveAll({ commit, getters, state }) {
            let prepareDataBeforeSave = function(data) {
                if (!data) return []
                return data.map((object, key) => {
                    let settings = _.mapValues(object.settings, (value, key) => {
                        let type = (object.settingsConfig && object.settingsConfig[key] && object.settingsConfig[key].type)
                            ? (object.settingsConfig[key].type).name.toLowerCase() // get type in string format
                            : 'string'

                        return {
                            type: type,
                            value: value
                        }
                    })
                    return {
                        type: object.type,
                        uniqueId: object.uniqueId,
                        settings: settings,
                        subTodos: prepareDataBeforeSave( getters.items(object.uniqueId) ),
                        order: key
                    }
                })
            }

            let formData = {
                todosData: prepareDataBeforeSave(getters.rootItems)
            }
            return axios.post(route('backend.project.save'), formData)
                .then((response) => {
                    // console.log('success', response)
                })
                .catch((error) => {
                    console.error('API error', error)
                })
        },
        fetchAll({ commit, getters, state }, payload) {
            let {projectId} = payload
            axios.get(route('backend.project.todos', {id: projectId}))
                .then((response) => {
                    // console.log('success', response)
                    _.forEach(_.sortBy(response.data.todos, [( o ) => { return o.parent_id || 0},'order']), function(object) {
                        commit('ADD_ITEM', {
                            type: object.type,
                            id: Number(object.unique_id),
                            settings: processSettings(object.settings),
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
