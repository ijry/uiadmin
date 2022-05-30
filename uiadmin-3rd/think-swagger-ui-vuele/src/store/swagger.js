const swagger = {
  state: {
    info: null,
    path: null,
    menus: null,
    headers: null
  },
  mutations: {
    swaggerPath: function(state, result) {
      state.path = result
    },
    swaggerInfo: function(state, result) {
      state.info = result
    },
    menus: function(state, result) {
      state.menus = result
    },
    headers: function(state, result) {
      state.headers = result
    }
  },
  actions: {
    path({ commit }, param) {
      commit('swaggerPath', param)
    },
    info({ commit }, param) {
      commit('swaggerInfo', param)
    },
    menus({ commit }, param) {
      commit('menus', param)
    },
    headers({ commit }, param) {
      commit('headers', param)
    }
  }
}

export default swagger
