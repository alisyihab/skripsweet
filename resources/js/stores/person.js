import $axios from '../api.js'

const state = () => ({
  person: [],
  page: 1,
});

const mutations = {
  ASSIGN_DATA(state, payload) {
    state.person = payload
  },
  SET_PAGE(state, payload) {
    state.page = payload
  },
};

const actions = {
  getPerson({ commit, state }, payload) {
    let search = typeof payload != 'undefined' ? payload : '';
    return new Promise((resolve) => {
      $axios.get(`/users?page=${state.page}&q=${search}`)
        .then((response) => {
          commit('ASSIGN_DATA', response.data);
          resolve(response.data)
        })
    })
  }
};

export default {
  namespaced: true,
  state,
  actions,
  mutations
}
