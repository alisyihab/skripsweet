import $axios from '../api.js'

const state = () => ({
    customers: [],

    customer: {
        nik: '',
        name: '',
        address: '',
        phone: ''
    },
    page: 1
});

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.customers = payload
    },
    SET_PAGE(state, payload) {
        state.page = payload
    },
    ASSIGN_FORM(state, payload) {
        state.customer = payload
    },
    CLEAR_FORM(state) {
        state.customer = {
            nik: '',
            name: '',
            address: '',
            phone: ''
        }
    }
};

const actions = {
    getCustomers({ commit, state }, payload) {
        let search = typeof payload != 'undefined' ? payload:'';
        return new Promise((resolve, reject) => {
            $axios.get(`/customer?page=${state.page}&q=${search}`)
            .then((response) => {
                commit('ASSIGN_DATA', response.data);
                resolve(response.data)
            })
        })
    },
    submitCustomer({ dispatch, commit, state }) {
        return new Promise((resolve, reject) => {
            $axios.post(`/customer`, state.customer)
            .then((response) => {
                dispatch('getCustomers').then(() => {
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
