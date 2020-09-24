<template>
  <div class="section-block">
    <div class="d-flex justify-content-between align-items-center">
      <h1 class="section-title mb-0"> &nbsp; </h1><!-- .dropdown -->
      <div class="col-lg-2 col-md-4 col-sm-4">
        <div class="form-group">
        <label>Tahun</label>
          <select v-model="year" class="form-control">
            <option v-for="(y, i) in years" :key="i" :value="y">{{ y }}</option>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <!-- grid column -->
      <div class="col-xl-8">
        <!-- .card -->
        <div class="card card-body card-fluid">
          <!-- legend -->
          <ul class="list-inline small">
            <li class="list-inline-item">
              <i class="fa fa-fw fa-circle text-teal"></i> Pemasukan
            </li>
            <li class="list-inline-item">
              <i class="fa fa-fw fa-circle text-purple"></i> Pengeluaran
            </li>
          </ul><!-- /legend -->
          <div class="chartjs">
            <div class="chartjs-size-monitor">
              <div class="chartjs-size-monitor-expand">
                <div class=""></div>
              </div>
              <div class="chartjs-size-monitor-shrink">
                <div class=""></div>
              </div>
            </div>
            <finace-chart 
              :data="finace_data_debit"
              :chartData="finace_data_credit"
              :labels="label"
              :width="585"
              :height="253"
              class="chartjs-render-monitor">
            </finace-chart>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="card card-fluid">
          <div class="card-header"> Overview</div>
          <div class="card-body">
            <dl class="d-flex justify-content-between">
              <dt class="text-left">
                <span class="mr-2">Revenue</span> <small class="text-success"><i class="fa fa-caret-up"></i> 87%</small>
              </dt>
              <dd class="text-right mb-0">
                <small class="text-muted">Rp. {{ total_debit | convert }} </small> <strong></strong>
              </dd>
            </dl>
          </div>
          <div class="card-body border-top">
            <dl class="d-flex justify-content-between">
              <dt class="text-left">
                <span class="mr-2">Expenses</span> 
                <small class="text-danger"><i class="fa fa-caret-down"></i>12%</small>
              </dt>
              <dd class="text-right mb-0">
                <small class="text-muted">Rp. {{ total_credit | convert }} </small> <strong></strong>
              </dd>
            </dl>
          </div><!-- /.card-body -->
          <!-- .card-body -->
          <div class="card-body border-top">
            <div class="summary">
              <p class="text-left">
                <strong class="mr-2">Earnings</strong> <small class="text-success"><i class="fa fa-caret-up"></i>
                24%</small>
              </p>
              <p class="text-center">
                <span class="text-muted">Rp. {{ total_debit - total_credit | convert }} </span> 
                <strong class="h3"></strong> 
              </p>
            </div>
          </div><!-- /.card-body -->
        </div><!-- /.card -->
      </div><!-- /grid column -->
    </div>
  </div>
</template>

<script>
  import moment from 'moment';
  import { mapActions, mapState } from 'vuex';
  import Chart from "./Chart"
  import _ from 'lodash'
  
  export default {
    components: { 
      'finace-chart': Chart
    },
    created() {
      this.getChartBar({
        year: this.year
      })
    },
    data() {
      return {
        year: moment().format('Y')
      }
    },
    watch: {
      year() {
        this.getChartBar({
          year: this.year
        })
      }
    },
    computed: {
      ...mapState('finace', {
        finace: state => state.finace
      }),
      years() {
        return _.range(2010, moment().add(1, 'years').format('Y'))
      },
      label() {
        return _.map(this.finace, function (d) {
          return d.date
        })
      },
      finace_data_debit() {
        return _.map(this.finace, function (d) {
          return d.debit
        })
      },
      finace_data_credit() {
        return _.map(this.finace, function (d) {
          return d.credit
        })
      },
      total_debit() {
        return _.sumBy(this.finace, function (d) {
          return parseFloat(d.debit)
        })
      },
      total_credit() {
        return _.sumBy(this.finace, function (d) {
          return parseFloat(d.credit)
        })
      }
    },
    methods: {
      ...mapActions('finace', ['getChartBar']),
    },
    filters: {
      convert: function (value) {
        if (value >= 1000000000) {
          value = (value / 1000000000) + " M"
        } else if (value >= 1000000) {
          value = (value / 1000000) + " Jt";
        } else if (value >= 1000) {
          value = (value / 1000) + " Rb";
        }
        return value;
      }
    },
  }
</script>