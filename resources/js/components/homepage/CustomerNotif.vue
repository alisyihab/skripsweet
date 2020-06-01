<template>
    <li class="dropdown dropdown-list-toggle" v-if="authenticated.role != 0 && authenticated.role != 1">
        <a 
            v-if="notifications.length > 0"
            href="#" data-toggle="dropdown" 
            class="nav-link nav-link-lg message-toggle beep"
        >
            <i class="far fa-bell"></i>
            <sup>{{ notifications.length }}</sup>
        </a>
        <a 
            v-else
            href="#" data-toggle="dropdown" 
            class="nav-link nav-link-lg message-toggle"
        >
            <i class="far fa-bell"></i>
            <sup>{{ notifications.length }}</sup>
        </a>
        <div class="dropdown-menu dropdown-list dropdown-menu-right">
            <div class="dropdown-header">You have {{ notifications.length }}
                <div class="float-right">
                    <a href="#">Mark All As Read</a>
                </div>
            </div>
            <div 
                class="dropdown-list-content dropdown-list-message" 
                v-if="notifications.length > 0">
                <span v-for="(row, index) in notifications" :key="index">
                    <a href="javascript:void(0)" @click="readTransNotif(row)" class="dropdown-item dropdown-item-unread">
                        <div class="dropdown-item-avatar">
                            <img alt="image" src="https://via.placeholder.com/160" class="rounded-circle">
                            <div class="is-online"></div>
                        </div>
                        <div class="dropdown-item-desc">
                            <b>{{ row.data.sender_name }}</b>
                            <p  v-show="authenticated.role = 3">
                                Laundry kamu telah selesai, Silahkan di pikup.
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
            readTransNotif(row) {
                this.readNotification({id: row.id}).then(() => this.$router.push ({
                    name: 'transactions.view',
                    params: {id: row.data.transaction.transaction.id}
                }))
            },
        }
    }
</script>