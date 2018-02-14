import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

// the root, initial state object
const state = {
  sidebar: {
      settings: {
          sidebarFontSize: '18px'
      }
  },
  testing: 'abcd'
}

// define the possible mutations that can be applied to our state
const mutations = {
    ADD_SIDEBAR_SETTING (state, id, value) {
      const newSetting = {
        id: '',
        value: '',
      }
      state.sidebar.settings.push(newSetting)
    },
}

// create the Vuex instance by combining the state and mutations objects
// then export the Vuex store for use by our components
export default new Vuex.Store({
  state,
  mutations
})
