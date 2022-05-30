import Vue from 'vue'
import Vuex from 'vuex'
import swagger from './swagger'
import getters from './getters'
import main from './main'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    swagger,
    main
  },
  getters
})

export default store
