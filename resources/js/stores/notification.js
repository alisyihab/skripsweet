import $axios from '../api.js'

const state = () => ({
    notifications: [],
    list_notifications: [],
    page: 1
});

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.notifications = payload
    },
    ASSIGN_DATA_NOTIFICATION(state, payload) {
        state.list_notifications = payload
    },
    SET_PAGE(state, payload) {
        state.page = payload
    }
};

const actions = {
    getNotifications({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`/notification`)
            .then((response) => {
                commit('ASSIGN_DATA', response.data.data)
                resolve(response.data)
            })
        })
    },
    readNotification({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.post(`/notification`, payload)
            .then((response) => {
                dispatch('getNotifications').then(() => resolve(response.data))
            })
        })
    },
    getListNotifications({ commit }) {
        return new Promise((resolve) => {
            $axios.get(`list-notif`).then((response) => {
                commit('ASSIGN_DATA_NOTIFICATION', response.data.data);
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