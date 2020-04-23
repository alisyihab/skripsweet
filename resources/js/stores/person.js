import $axios from '../api.js'

const state = () => ({
  person: [],
  persons: {
    name: '',
    email: '',
    password: ''
  },
  page: 1,
});

const mutations = {
  ASSIGN_DATA(state, payload) {
    state.person = payload
  },
  SET_PAGE(state, payload) {
    state.page = payload
  },
  ASSIGN_FORM(state, payload) {
    state.persons = payload
  },
  CLEAR_FORM(state, payload) {
    state.persons = {
      name: '',
      email: '',
      password: ''
    }
  }
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
  },
  submitPerson({ dispatch, commit, state }) {
    return new Promise((resolve, reject) => {
      $axios.post(`/users`, state.persons)
        .then((response) => {
          dispatch('getPerson').then(() => {
            resolve(response.data)
          })
        })
        .catch((error) => {
          if (error.response.status === 422) {
            commit('SET_ERRORS', error.response.data.errors, { root: true })
          }
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
