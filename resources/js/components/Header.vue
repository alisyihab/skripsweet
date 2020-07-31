<template>
    <div>
    <div class="navbar-bg"></div>
    <nav class="navbar navbar-expand-lg main-navbar">
        <div class="form-inline mr-auto">
            <ul class="navbar-nav mr-3">
                <li>
                    <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
        </div>
        <ul class="navbar-nav navbar-right">
            <li class="dropdown dropdown-list-toggle">
                
                <a 
                    v-if="notifications.length > 0"
                    href="#" data-toggle="dropdown" 
                    class="nav-link nav-link-lg message-toggle beep">
                    <i class="far fa-bell"></i>
                    <sup>{{ notifications.length }}</sup>
                </a>
                <a 
                    v-else
                    href="#" data-toggle="dropdown" 
                    class="nav-link nav-link-lg message-toggle">
                    <i class="far fa-bell"></i>
                    <sup>{{ notifications.length }}</sup>
                </a>
                <div class="dropdown-menu dropdown-list dropdown-menu-right">
                    <div class="dropdown-header">You have {{ notifications.length }}
                    </div>
                    <div class="dropdown-list-content dropdown-list-message" v-if="notifications.length > 0">
                        <span v-for="(row, index) in notifications" :key="index">
                            <a href="javascript:void(0)" @click="readNotif(row)" class="dropdown-item dropdown-item-unread">
                                <div class="dropdown-item-avatar" v-if="row.data.sender_photo == null">
                                    <img alt="image" src="https://via.placeholder.com/160" class="rounded-circle">
                                    <div class="is-online"></div>
                                </div>
                                <div class="dropdown-item-avatar" v-else>
                                    <img alt="image" 
                                        :src="'/storage/users/' + row.data.sender_photo" 
                                        class="rounded-circle"
                                    />
                                    <div class="is-online"></div>
                                </div>
                                <div class="dropdown-item-desc">
                                    <b>{{ row.data.sender_name }}</b>
                                    <p v-if="row.data.expenses">
                                        {{ row.data.expenses.description.substr(0, 30) }}
                                    </p>
                                    <p v-else-if="row.data.payment">
                                        <span>
                                            {{ row.data.note }}
                                        </span>
                                    </p>
                                    <p v-else-if="row.data.transaction">
                                        Pembayaran telah di verifikasi oleh admin.
                                        <span v-html="row.data.transaction.status_label"></span>
                                    </p>
                                    <div class="time">{{ row.created_at | formatDate }}</div>
                                </div>
                            </a>
                        </span>
                    </div>
                    <div class="dropdown-footer text-center">
                        <router-link :to="{ name: 'notifications.data' }">
                            Lihat Semua 
                            <i class="fas fa-chevron-right"></i>
                        </router-link>
                    </div>
                </div>
            </li>

            <li class="dropdown">
                <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    <span v-if="authenticated.photo == null">
                        <img alt="image" src="https://via.placeholder.com/160" class="rounded-circle mr-1">
                    </span>
                    <img
                        v-if="authenticated.photo !== null" 
                        alt="image" 
                        :src="'/storage/users/' + authenticated.photo"
                        class="rounded-circle"
                    >
                    <div class="d-sm-none d-lg-inline-block">Hi, {{ authenticated.name }}</div>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="javascript:void(0)" @click="logout" class="dropdown-item has-icon text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>
</div>
</template>

<script>
    import {mapActions, mapState} from "vuex";
    import moment from 'moment'

    import CustomerNotif from './homepage/CustomerNotif';

    export default {
        computed: {
            ...mapState('user', {
                authenticated: state => state.authenticated
            }),
            ...mapState('notification', {
                notifications: state => state.notifications
            })
        },
        filters: {
            formatDate(val) {
                return moment(new Date(val)).fromNow()
            }
        },
        methods: {
            ...mapActions('notification', ['readNotification']),
            readNotif(row) {
                if (row.data.expenses) {
                        this.readNotification({id: row.id}).then(() => this.$router.push({
                        name: 'expenses.view',
                        params: {
                            id: row.data.expenses.id
                        }
                    }))
                } else if (row.data.payment) {
                        this.readNotification({id: row.id}).then(() => this.$router.push({
                        name: 'transaction.payment',
                        params: {
                            id: row.data.payment.transaction_id
                        }
                    }))
                } else if (row.data.transaction) {
                      this.readNotification({id: row.id}).then(() => this.$router.push({
                        name: 'transactions.view',
                        params: {
                            id: row.data.transaction.id
                        }
                    }))
                }
            },
            logout() {
                return new Promise((resolve, reject) => {
                    localStorage.removeItem('token');
                    resolve()
                }).then(() => {
                    this.$store.state.token = localStorage.getItem('token');
                    this.$router.push('/login')
                })
            }
        },
        components: {
            'notif': CustomerNotif,
        }
    }
</script>
