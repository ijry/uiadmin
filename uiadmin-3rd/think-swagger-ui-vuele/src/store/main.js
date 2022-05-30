const main = {
  state: {
    theme: null
  },
  mutations: {
    theme: function(state, result) {
      state.theme = result
    }
  },
  actions: {
    path({ commit }, param) {
      commit('theme', param)
    }
  }
}

export default main
