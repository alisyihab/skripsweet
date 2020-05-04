<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Data Transaksi</h4>
                <div class="card-header-action">
                    <a data-collapse="#mycard-collapse1" class="btn btn-icon btn-info" href="#">
                        <i class="fas fa-minus"></i>
                    </a>
                </div>
            </div>
            <div class="collapse show" id="mycard-collapse1">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">Bulan</label>
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
                                <label for="">Tahun</label>
                                <select v-model="year" class="form-control">
                                    <option v-for="(y, i) in years" :key="i" :value="y">{{ y }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" @click="exportData">Export</button>
                        </div>
                    </div>
                    <line-chart 
                        v-if="transactions.length > 0" :data="transaction_data"
                        :options="chartOptions" :labels="labels"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapState} from 'vuex';
    import moment from 'moment'
    import _ from 'lodash';

    import LineChart from '../LineChart'

    export default {
        created() {
            this.getChartData({
                month: this.month,
                year: this.year
            });
        },
        data() {
            return {
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
            },
            year() {
                this.getChartData({
                    month: this.month,
                    year: this.year
                })
            }
        },
        computed: {
            ...mapState('dashboard', {
                transactions: state => state.transactions,
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
            transaction_data() {
                return _.map(this.transactions, function (o) {
                    return o.total
                });
            },
        },
        methods: {
            ...mapActions('dashboard', ['getChartData']),
            exportData() {
                window.open(`/api/export?api_token=${this.token}&month=${this.month}&year=${this.year}`)
            },
        },
        components: {
            'line-chart': LineChart,
        }
    }
</script>