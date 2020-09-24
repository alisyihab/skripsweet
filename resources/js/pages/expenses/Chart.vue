<script>
  import { Bar } from 'vue-chartjs'
  
  export default {
    extends: Bar,
      props: {
        data: {
          type: Array,
          required: false
        },
        chartData: {
          type: Array,
          required: false
        },  
        labels: {
          type: Array,
          required: true
        }
      },
      data () {
        return {
          options: {
            scales: {
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
              }],
              xAxes: [ {
                gridLines: {
                  display: false
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
                  content += ' ';
                  content += 'Rp. ' + yLabel;

                  return content;
                }
              }
            },
            legend: {
              display: true
            },
            responsive: true,
            maintainAspectRatio: false
          }
        }
      },
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
                label: 'Pemasukan',
                data: this.data,
                backgroundColor: '#00A28A',
              }, {
                label: 'Pengeluaran',
                data: this.chartData,
                backgroundColor: '#5F4B8B',
            }]
          }, this.options)
        }
      }
  }
</script>
