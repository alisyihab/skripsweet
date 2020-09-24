import $axios from '../api.js'

const state = () => ({
  transactions: [],
  expenses: [],
});

const mutations = {
  ASSIGN_DATA_TRANSACTION(state, payload) {
    state.transactions = payload
  },
  ASSIGN_DATA_EXPENSE(state, payload) {
    state.expenses = payload
  }
};

const actions = {
  getChartData({commit}, payload) {
    return new Promise((resolve, reject) => {
      $axios.get(`/chart?month=${payload.month}&year=${payload.year}`)
        .then((response) => {
          commit('ASSIGN_DATA_TRANSACTION', response.data);
          resolve(response.data)
        })
    })
  },
  getChartBar({commit}, payload) {
    return new Promise((resolve, reject) => {
      $axios.get(`/expanse?month=${payload.month}&year=${payload.year}`)
        .then((response) => {
          commit('ASSIGN_DATA_EXPENSE', response.data);
          resolve(response.data)
        })
    })
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations
}
