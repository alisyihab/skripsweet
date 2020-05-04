<template>
        <div class="col-lg-8 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>Data Pengeluaran</h4>
                    <div class="card-header-action">
                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#">
                            <i class="fas fa-minus"></i>
                        </a>
                    </div>
                </div>
                <div class="collapse show" id="mycard-collapse" style="">
                    <div class="card-body">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">Tahun</label>
                                <select v-model="year" class="form-control">
                                    <option v-for="(y, i) in years" :key="i" :value="y">{{ y }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3"></div>
                        <bar-chart
                            :data="expanse_data"
                            :options="options"
                            :labels="label"
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

    import BarChart from '../BarChart'
    
    export default {
        created() {
            this.getChartBar({
                month: this.month,
                year: this.year
            });
        },
        data() {
            return {
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
                this.getChartBar({
                    month: this.month,
                    year: this.year
                })
            },
            year() {
                this.getChartBar({
                    month: this.month,
                    year: this.year
                })
            }
        },
        computed: {
            ...mapState('dashboard', {
                expenses: state => state.expenses
            }),
            years() {
                return _.range(2010, moment().add(1, 'years').format('Y'))
            },
            label() {
                return _.map(this.expenses, function (o) {
                    return o.date
                });
            },
            expanse_data() {
                return _.map(this.expenses, function (o) {
                    return o.total
                });
            }
        },
        methods: {
            ...mapActions('dashboard', ['getChartBar'])
        },
        components: {
            'bar-chart': BarChart,
        }
    }
</script>
