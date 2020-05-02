<template>
    <div>
    <div class="navbar-bg"></div>
    <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
            <ul class="navbar-nav mr-3">
                <li>
                    <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
        </form>
        <ul class="navbar-nav navbar-right">
            <li class="dropdown dropdown-list-toggle">
                <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle beep">
                    <i class="far fa-bell"></i>
                    <sup>{{ notifications.length }}</sup>
                </a>
                <div class="dropdown-menu dropdown-list dropdown-menu-right">
                    <div class="dropdown-header">You have {{ notifications.length }}
                        <div class="float-right">
                            <a href="#">Mark All As Read</a>
                        </div>
                    </div>
                    <div class="dropdown-list-content dropdown-list-message" v-if="notifications.length > 0">
                        <span v-for="(row, index) in notifications" :key="index">
                            <a href="javascript:void(0)" @click="readNotif(row)" class="dropdown-item dropdown-item-unread">
                                <div class="dropdown-item-avatar">
                                    <img alt="image" src="https://via.placeholder.com/160" class="rounded-circle">
                                    <div class="is-online"></div>
                                </div>
                                <div class="dropdown-item-desc">
                                    <b>{{ row.data.sender_name }}</b>
                                    <p>{{ row.data.expenses.description.substr(0, 30) }}</p>
                                    <div class="time">{{ row.created_at | formatDate }}</div>
                                </div>
                            </a>
                        </span>
                    </div>
                    <div class="dropdown-footer text-center">
                        <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </li>
            <li class="dropdown">
                <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    <span v-if="authenticated.photo == null">
                        <img alt="image" src="https://via.placeholder.com/160" class="rounded-circle mr-1">
                    </span>
                    <div class="d-sm-none d-lg-inline-block">Hi, {{ authenticated.name }}</div>
                </a>
                <div class="dropdown-menu dropdown-menu-right" v-if="authenticated.role == 0">
                    <router-link :to="{name: 'role.permissions'}" class="dropdown-item has-icon">
                        <i class="fas fa-cog"></i> Settings
                    </router-link>
                    <div class="dropdown-divider"></div>
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
                this.readNotification({id: row.id}).then(() => this.$router.push({
                    name: 'expenses.view',
                    params: {id: row.data.expenses.id}
                }))
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
        }
    }
</script>
