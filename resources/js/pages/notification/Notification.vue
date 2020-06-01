<template>
    <div>
        <h2 class="section-title">Notification</h2>
        <div class="row">
            <div class="col-12">
                <div class="activities" v-for="(row, index) in notifications" :key="index">
                    <div class="activity">
                        <div class="activity-icon bg-primary text-white shadow-primary">
                            <i class="fas fa-comment-alt"></i>
                        </div>
                        <div class="activity-detail">
                            <div class="mb-2">
                                <span class="text-job text-primary">
                                    Dibaca {{ row.read_at | formatDate }}
                                </span>
                                <span class="bullet"></span>
                                <router-link 
                                    class="text-job" 
                                    :to="{ name: 'transactions.view', params: {id: row.data.transaction.id} }">
                                    Lihat
                                </router-link>
                            </div>
                            <p>
                                <b>{{ row.data.transaction.product.name }}</b>
                            </p>
                            <p>
                                total biaya laundy 
                                {{ row.data.transaction.transaction.amount | 
                                    currency('Rp', '2', { spaceBetweenAmountAndSymbol: true }) 
                                }} 
                            </p>
                            <p>
                                <span v-html="row.data.transaction.status_label" />
                            </p>
                        </div>
                    </div>
                </div>
               <infinite-loading spinner="spiral" @distance="1" @infinite="infiniteHandler" />
            </div>
        </div>
    </div>
</template>

<script>
    import $axios from "../../api";
    import moment from 'moment'
    import InfiniteLoading from 'vue-infinite-loading';

    export default {
        data() {
            return {
                notifications: [],
                page: 1
            }
        },
        methods: {
            infiniteHandler($state) {
                $axios.get(`list-notif`, {
                    params: {
                        page: this.page,
                    },
                }).then(({ data }) => {
                    if (data.data.data.length) {
                        this.page += 1;
                        this.notifications.push(...data.data.data);
                        $state.loaded();
                    } else {
                        $state.complete();
                    }
                })
            }
        },
        filters: {
            formatDate(val) {
                return moment(new Date(val)).fromNow()
            }
        },
        components: {
            InfiniteLoading
        }
    }
</script>