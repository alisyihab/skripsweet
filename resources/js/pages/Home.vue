<template>
    <div class="container">
        <section class="content-header">
            <h1>
                Dashboard
            </h1>
            <ol class="breadcrumb">
                <li>
                    <router-link :to="'/'"><i class="fa fa-dashboard"></i> Home</router-link>
                </li>
                <li><a href="#">Dashboard</a></li>
            </ol>
        </section>
        <section class="content">
         <span v-if="authenticated.role !=2">
            <div class="row">
             <div class="col-lg-3 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                    <h4>{{ totData.expanse | currency('Rp.', '2', { spaceBetweenAmountAndSymbol: true }) }}</h4>

                    <p>Total Pengeluaran</p>
                    </div>
                    <div class="icon">
                    <i class="ion ion-cash"></i>
                    </div>
                    <router-link :to="{ name: 'expenses.data' }" class="small-box-footer">
                        More info
                        <i class="fa fa-arrow-circle-right"></i>
                    </router-link>
                </div>
            </div>
            <div class="col-lg-3 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                    <h4>{{ totData.orders }}</h4>

                    <p>Total Order</p>
                    </div>
                    <div class="icon">
                    <i class="ion ion-bag"></i>
                    </div>
                    <router-link :to="{ name: 'transactions.list' }" class="small-box-footer">
                        More info
                        <i class="fa fa-arrow-circle-right"></i>
                    </router-link>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                <h4>{{ totData.income | currency('Rp.', '2', { spaceBetweenAmountAndSymbol: true }) }}<sup style="font-size: 20px"></sup></h4>

                <p>Total Pendapatan</p>
                </div>
                <div class="icon">
                <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-12">
            <div class="small-box bg-yellow">
                <div class="inner">
                <h4>{{ totData.customer }}</h4>

                <p>Total Pelanggan</p>
                </div>
                <div class="icon">
                <i class="ion ion-person-add"></i>
                </div>
                <router-link :to="{ name: 'customers.data' }" class="small-box-footer">
                    More info
                    <i class="fa fa-arrow-circle-right"></i>
                </router-link>
            </div>
            </div>
        </div>
        </span>
        <span v-if="authenticated.role == 2">
            <div class="col-lg-12 col-xs-12">
                <div class="small-box bg-red">
                    <div class="inner">
                    <h4>{{ totData.expanse | currency('Rp.', '2', { spaceBetweenAmountAndSymbol: true }) }}</h4>

                    <p>Total Pengeluaran</p>
                    </div>
                    <div class="icon">
                    <i class="ion ion-cash"></i>
                    </div>
                    <router-link :to="{ name: 'expenses.data' }" class="small-box-footer">
                        More info
                        <i class="fa fa-arrow-circle-right"></i>
                    </router-link>
                </div>
            </div>
        </span>
    </section>
    <section class="content" v-if="authenticated.role == 0 || authenticated.role == 1">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Laporan Transaksi</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Bulan</label>
                                    <select v-model="month" class="form-control">
                                        <option value="01">Januari</option>
                                        <option value="02">Februari</option>
                                        <option value="03">Maret</option>
                                        <option value="04">April</option>
                                        <option value="05">Mei</option>
                                        <option value="06">Juni</option>
                                        <option value="07">Juli</option>
                                        <option value="08">Agustus</option>
                                        <option value="09">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Tahun</label>
                                    <select v-model="year" class="form-control">
                                        <option v-for="(y, i) in years" :key="i" :value="y">{{ y }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-sm pull-right" @click="exportData">
                                    Export
                                </button>
                            </div>
                        </div>
                        <line-chart
                            v-if="transactions.length > 0"
                            :data="transaction_data"
                            :options="chartOptions"
                            :labels="labels">
                        </line-chart>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content" v-if="authenticated.role == 0 || authenticated.role == 2">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Laporan Pengluaran</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-primary btn-sm pull-right" @click="exportData">
                                Export
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Tahun</label>
                                    <select v-model="year" class="form-control">
                                        <option v-for="(y, i) in years" :key="i" :value="y">{{ y }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">

                            </div>
                        </div>
                        <bar-chart
                            :data="expanse_data"
                            :options="options"
                            :labels="label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
</template>

<script>
    import moment from 'moment'
    import _ from 'lodash'
    import LineChart from '../components/LineChart.vue'
    import {mapActions, mapState} from 'vuex'
    import $axios from "../api";
    import Vue2Filters from 'vue2-filters'
    import BarChart from "../components/BarChart"

    export default {
        created() {
            this.getChartData({
                month: this.month,
                year: this.year
            });
            this.getChartBar({
                month: this.month,
                year: this.year
            });
        },
        data() {
            return {
                totData: [],
                chartOptions: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            ticks: {}
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                userCallback: function (value, index, values) {
                                    value = value.toString();
                                    value = value.split(/(?=(?:...)*$)/);
                                    value = value.join('.');
                                    return 'Rp.' + value;
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function (item, data) {
                                let label = data.datasets[item.datasetIndex].label || '';
                                let yLabel = item.yLabel;
                                let content = '';

                                if (data.datasets.length > 1) {
                                    content += label;
                                }

                                yLabel = yLabel.toString();
                                yLabel = yLabel.split(/(?=(?:...)*$)/);
                                yLabel = yLabel.join('.');
                                content += label + ' ';
                                content += 'Rp. ' + yLabel;
                                return content;
                            }
                        }
                    }
                },
                options: {
                     responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            ticks: {}
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                userCallback: function (value, index, values) {
                                    value = value.toString();
                                    value = value.split(/(?=(?:...)*$)/);
                                    value = value.join('.');
                                    return 'Rp.' + value;
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function (item, data) {
                                let label = data.datasets[item.datasetIndex].label || '';
                                let yLabel = item.yLabel;
                                let content = '';

                                if (data.datasets.length > 1) {
                                    content += label;
                                }

                                yLabel = yLabel.toString();
                                yLabel = yLabel.split(/(?=(?:...)*$)/);
                                yLabel = yLabel.join('.');
                                content += label + ' ';
                                content += 'Rp. ' + yLabel;
                                return content;
                            }
                        }
                    }
                },
                month: moment().format('MM'),
                year: moment().format('Y')
            }
        },
        watch: {
            month() {
                this.getChartData({
                    month: this.month,
                    year: this.year
                });
                this.getChartBar({
                    month: this.month,
                    year: this.year
                })
            },
            year() {
                this.getChartData({
                    month: this.month,
                    year: this.year
                })
                this.getChartBar({
                    month: this.month,
                    year: this.year
                })
            }
        },
        computed: {
            ...mapState('dashboard', {
                transactions: state => state.transactions,
                expenses: state => state.expenses
            }),
            ...mapState('user', {
                authenticated: state => state.authenticated
            }),
            ...mapState(['token']),
            years() {
                return _.range(2010, moment().add(1, 'years').format('Y'))
            },
            labels() {
                return _.map(this.transactions, function (o) {
                    return moment(o.date).format('Do MMMM YYYY')
                });
            },
            label() {
                return _.map(this.expenses, function (o) {
                    return o.date
                });
            },
            transaction_data() {
                return _.map(this.transactions, function (o) {
                    return o.total
                });
            },
            expanse_data() {
                return _.map(this.expenses, function (o) {
                    return o.total
                });
            }
        },
        methods: {
            ...mapActions('dashboard', ['getChartData', 'getChartBar']),
            exportData() {
                window.open(`/api/export?api_token=${this.token}&month=${this.month}&year=${this.year}`)
            },
            exportData() {
                window.open(`/api/export?api_token=${this.token}&month=${this.month}&year=${this.year}`)
            }
        },
        components: {
            'line-chart': LineChart,
            'bar-chart': BarChart,
             mixins: [Vue2Filters.mixin]
        },
        mounted() {
           totData: {
               $axios.get(`data`).then(response => {
                this.totData = response.data;
                })
                .catch(error => {
                    console.log(error)
              })
           }
        }
    }
</script>

<style>
    .content{
        min-height: 0;
    }
</style>