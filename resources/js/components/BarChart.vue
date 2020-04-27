<script>
    import { Bar } from 'vue-chartjs'

    export default {
        extends: Bar,
        props: ['data', 'options', 'labels'],
        mounted() {
            this.barRenderChart()
        },
        watch: {
            data: {
                handler() {
                    this._data._chart.destroy();
                    this.barRenderChart();
                },
                deep: true
            }
        },
        methods: {
            barRenderChart() {
                this.renderChart({
                    labels: this.labels,
                    datasets: [{
                        label: 'Data Pengeluaran',
                        data: this.data,
                        backgroundColor: '#f87979',
                    }]
                }, this.options)
            }
        }
    }
</script>
