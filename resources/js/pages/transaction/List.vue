<template>
    <div class="col-md-12">
        <div class="card">
           <div class="card-header">
                 <h4>Daftar Transaksi</h4>
            </div>
            <div class="card-body">
                <div class="float-left">
                    <select v-model="filter_status" class="form-control selectric">
                        <option value="2">All</option>
                        <option value="1">Selesai</option>
                        <option value="0">Proses</option>
                    </select>
                </div>
                <div class="float-right">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                        <div class="input-group-append">
                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="clearfix mb-3"></div>
                <b-table striped hover bordered responsive :items="transactions.data" :fields="fields" show-empty>
                    <template v-slot:cell(index)="data">
                         <p class="text-center">{{ data.index + 1 }}</p>
                    </template>
                     <template v-slot:cell(customer)="row">
                        <p><strong>{{ row.item.customer ? row.item.customer.name:'' }}</strong></p>
                        <p>Telp: {{ row.item.customer.phone }}</p>
                        <p>NIK: {{ row.item.customer.nik }}</p>
                    </template>
                    <template v-slot:cell(user_id)="row">
                        <p>{{ row.item.user ? row.item.user.name:'' }}</p>
                    </template>
                    <template v-slot:cell(service)="row">
                        <p>{{ row.item.detail.length }} Item</p>
                    </template>
                    <template v-slot:cell(amount)="row">
                        {{ row.item.amount | currency('Rp. ', '2', { spaceBetweenAmountAndSymbol: true }) }}
                    </template>
                    <template v-slot:cell(created_at)="row">
                        {{ row.item.created_at | formatDate }}
                    </template>
                    <template v-slot:cell(status)="row">
                        <p v-html="row.item.status_label"></p>
                    </template>
                    <template v-slot:cell(actions)="row">
                        <router-link
                            v-b-tooltip.hover.top="'Lihat data'"
                            :to="{ name: 'transactions.view', params: {id: row.item.id} }"
                            class="btn btn-info btn-sm">
                            <i class="fa fa-eye"></i>
                        </router-link>
                    </template>
                </b-table>

                <div class="row">
                    <div class="col-md-6">
                        <p v-if="transactions.data" style="padding-left: 20px;">
                            <i class="fa fa-bars"></i>
                            {{ transactions.data.length }} item dari {{ transactions.meta.total }}
                            total data
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <b-pagination-nav
                                    v-model="page"
                                    :total-rows="transactions.meta.total"
                                    :per-page="transactions.meta.per_page"
                                    aria-controls="couriers"
                                    v-if="transactions.data && transactions.data.length > 0"
                                    align="right">
                            </b-pagination-nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapState} from 'vuex'
    import Vue2Filters from 'vue2-filters'
    import Vue from 'vue'

    const moment = require('moment')
    require('moment/locale/id')

    Vue.use(Vue2Filters);

    Vue.filter('formatDate', function(value) {
        if (value) {
            return moment(String(value)).format('Do MMMM YYYY, h:mm:ss a')
        }
    });

    export default {
        name: 'DataTransaction',
        created() {
            this.getTransactions({
                status: this.filter_status,
                search: this.search
            })
        },
        data() {
            return {
                fields: [
                    {key: 'index', label:'No', sortable: true},
                    {key: 'customer', label: 'Customer', sortable: true},
                    {key: 'user_id', label: 'Admin', sortable: true},
                    {key: 'service', label: 'Item Jasa', sortable: true},
                    {key: 'amount', label: 'Total', sortable: true},
                    {key: 'created_at', label: 'Tgl Transaksi', sortable: true},
                    {key: 'status', label: 'Status', sortable: true},
                    {key: 'actions', label: 'Aksi'}
                ],
                search: '',
                filter_status: 2
            }
        },
        computed: {
            ...mapState('transaction', {
                transactions: state => state.list_transaction
            }),
            page: {
                get() {
                    return this.$store.state.transaction.page
                },
                set(val) {
                    this.$store.commit('transaction/SET_PAGE', val)
                }
            }
        },
        watch: {
            page() {
                this.getTransactions({
                    status: this.filter_status,
                    search: this.search
                })
            },
            search() {
                this.getTransactions({
                    status: this.filter_status,
                    search: this.search
                })
            },
            filter_status() {
                this.getTransactions({
                    status: this.filter_status,
                    search: this.search
                })
            }
        },
        methods: {
            ...mapActions('transaction', ['getTransactions'])
        },
        components: {
            mixins: [Vue2Filters.mixin]
        }
    }
</script>
