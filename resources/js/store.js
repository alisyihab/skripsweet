import Vue from 'vue'
import Vuex from 'vuex'

import auth from './stores/auth.js'
import outlet from './stores/outlet.js'
import product from "./stores/product";
import user from "./stores/user";
import expenses from "./stores/expenses";
import notification from "./stores/notification";
import customer from "./stores/customer";
import transaction from "./stores/transaction";
import dashboard from "./stores/dashboard";
import person from "./stores/person"

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        auth,
        outlet,
        product,
        user,
        expenses,
        notification,
        customer,
        transaction,
        dashboard,
        person
    },
    state: {
        token: localStorage.getItem('token'),
        errors: []
    },
    getters: {
        isAuth: state => {
            return state.token !== "null" && state.token != null
        }
    },
    mutations: {
        SET_TOKEN(state, payload) {
            state.token = payload
        },
        SET_ERRORS(state, payload) {
            state.errors = payload
        },
        CLEAR_ERRORS(state) {
            state.errors = []
        }
    }
});

export default store
