import $axios from '../api';

const state = () => ({
  customers: [],
  page: 1,
  id: ''
});

const mutations = {
  ASSIGN_DATA(state, payload) {
    state.customers = payload
  },
  SET_PAGE(state, payload) {
    state.page = payload
  },
  SET_ID_UPDATE(state, payload) {
    state.id = payload
  }
};

const actions = {
  getCustomers({commit, state}, payload) {
    let search = typeof payload != 'undefined' ? payload : ''

    return new Promise((resolve) => {
      $axios.get(`/customer?page=${state.page}&q=${search}`)
        .then((response) => {
          commit('ASSIGN_DATA', response.data);
          resolve(response.data)
        })
    })
  },
  submitCustomer({dispatch, commit}, payload) {
    return new Promise((resolve, reject) => {
      $axios.post(`/customer`, payload, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
        .then((response) => {
          dispatch('getCustomers').then(() => {
            resolve(response.data)
          })
        })
        .catch((error) => {
          if (error.response.status == 422) {
            commit('SET_ERRORS', error.response.data.errors, {root: true})
          }
        })
    })
  },
  editCustomer({commit}, payload) {
    return new Promise((resolve) => {
      $axios.get(`/customer/${payload}/edit`)
        .then((response) => {
          resolve(response.data)
        })
    })
  },
  updateCustomer({state}, payload) {
    return new Promise((resolve) => {
      $axios.post(`/customer/${state.id}`, payload, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then((response) => {
        resolve(response.data)
      })
    })
  },
  removeCustomer({dispatch}, payload) {
    return new Promise((resolve) => {
      $axios.delete(`/customer/${payload}`).then((response) => {
        dispatch('getCustomers').then(() => resolve(response.data))
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
