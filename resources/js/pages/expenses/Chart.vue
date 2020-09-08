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
                         label: 'Assigned Tasks',
                         borderColor: Looper.colors.brand.teal,
                         backgroundColor: Looper.colors.brand.teal,
                         data: [41, 20, 68, 17, 100, 83, 53]
                    }, {
                         label: 'Completed Tasks',
                         borderColor: Looper.colors.brand.purple,
                         backgroundColor: Looper.colors.brand.purple,
                         data: [51, 14, 51, 63, 59, 83, 34]
                    }]
                }, this.options)
            }
        }
    }
</script>
