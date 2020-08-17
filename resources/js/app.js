import Vue from 'vue'
import router from './router.js'
import store from './store.js'
import App from './App.vue'

import BootstrapVue from 'bootstrap-vue'
import VueSweetalert2 from "vue-sweetalert2";
import Permissions from './mixins/permission.js'

Vue.mixin(Permissions);
Vue.use(VueSweetalert2);
Vue.use(BootstrapVue);

import 'bootstrap-vue/dist/bootstrap-vue.css'
import {mapActions, mapGetters, mapState} from 'vuex'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

new Vue({
    el: '#apps',
    router,
    store,
    components: {
        App
    },
    computed: {
        ...mapGetters(['isAuth']),
        ...mapState(['token']),
        ...mapState('user', {
            user_authenticated: state => state.authenticated
        })
    },
    methods: {
        ...mapActions('user', ['getUserLogin']),
        ...mapActions('notification', ['getNotifications']),
        ...mapActions('expenses', ['getExpenses']),
        ...mapActions('transaction', ['getTransactions']),
        initialLister() {
            if (this.isAuth) {
                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: process.env.MIX_PUSHER_APP_KEY,
                    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
                    encrypted: false,
                    auth: {
                        headers: {
                            Authorization: 'Bearer ' + this.token
                        },
                    },
                });

                if (typeof this.user_authenticated.id != 'undefined') {
                    window.Echo.private(`App.User.${this.user_authenticated.id}`)
                        .notification(() => {
                            this.getNotifications()
                            this.getExpenses()
                            this.getTrasactions()
                        })
                }
            }
        }
    },
    watch: {
        token() {
            this.initialLister()
        },
        user_authenticated() {
            this.initialLister()
        }
    },
    created() {
        if (this.isAuth) {
            this.getUserLogin();
            this.initialLister();
            this.getNotifications()
        }
    }
});
