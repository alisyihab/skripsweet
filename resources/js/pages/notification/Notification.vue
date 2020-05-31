<template>
    <div>
        <h2 class="section-title">Notification</h2>
        <div class="row">
            <div class="col-12">
                <div class="activities" v-for="item in notifications" :key="item.id">
                    <div class="activity">
                        <div class="activity-icon bg-primary text-white shadow-primary">
                            <i class="fas fa-comment-alt"></i>
                            <p class="text-muted text-small">
                                {{ item.data.sender_name }}
                            </p>
                        </div>
                        <div class="activity-detail">
                            <div class="mb-2">
                                <span class="text-job text-primary">2 min ago</span>
                                <span class="bullet"></span>
                                <a class="text-job" href="#">Lihat</a>
                            </div>
                            <p>Have commented on the task of "<a href="#">Responsive design</a>".</p>
                        </div>
                    </div>
                </div>
                <infinite-loading @distance="1" @infinite="infiniteHandler"></infinite-loading>
            </div>
        </div>
    </div>
</template>

<script>
    import $axios from "../../api";
    import {mapState, mapActions} from 'vuex';
    import Vue from "vue";

    Vue.component('InfiniteLoading', require('vue-infinite-loading'));

    export default {
        data() {
            return {
                notifications: [],
                page: 1
            }
        },
        methods: {
            infiniteHandler($state) {
                $axios.get(`list-notif?page=`+this.page).then(response => {
                    return response.json();
                }).then(data => {
                    $.each(data.data, (key, value) => {
                        this.notifications.push(value)
                    });
                    $state.loaded()
                })
                this.page = this.page + 1
            }
        }
    }
</script>