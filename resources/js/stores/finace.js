import $axios from "../api";

const state = () => ({
  finace: [],
})

const mutations = {
  ASSIGN_DATA_FINACE(state, payload) {
    state.finace = payload
  }
}

const actions = {
  getChartBar({ commit }, payload) {
    return new Promise(( resolve ) => {
      $axios.get(`/finace?year=${payload.year}`).then((response) => {
        commit('ASSIGN_DATA_FINACE', response.data);
        resolve(response.data)
      })
    })
  }
}

export default {
  namespaced: true,
  state,
  actions,
  mutations
}