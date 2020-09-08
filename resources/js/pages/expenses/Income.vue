<template>
  <div class="section-block">
    <div class="d-flex justify-content-between align-items-center">
      <h1 class="section-title mb-0"> &nbsp; </h1><!-- .dropdown -->
      <div class="dropdown">
        <button class="btn btn-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span>This Month</span>
          <i class="fa fa-fw fa-caret-down"></i></button> <!-- .dropdown-menu -->
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-md stop-propagation">
          <div class="dropdown-arrow"></div><!-- .custom-control -->
          <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="dpToday" name="dpFilter" value="0"> <label
              class="custom-control-label d-flex justify-content-between" for="dpToday"><span>Today</span> <span
              class="text-muted">Mar 27</span></label>
          </div><!-- /.custom-control -->
          <!-- .custom-control -->
          <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="dpYesterday" name="dpFilter" value="1"> <label
              class="custom-control-label d-flex justify-content-between" for="dpYesterday"><span>Yesterday</span> <span
              class="text-muted">Mar 26</span></label>
          </div><!-- /.custom-control -->
          <!-- .custom-control -->
          <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="dpWeek" name="dpFilter" value="2"> <label
              class="custom-control-label d-flex justify-content-between" for="dpWeek"><span>This Week</span> <span
              class="text-muted">Mar 21-27</span></label>
          </div><!-- /.custom-control -->
          <!-- .custom-control -->
          <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="dpMonth" name="dpFilter" value="3" checked=""> <label
              class="custom-control-label d-flex justify-content-between" for="dpMonth"><span>This Month</span> <span
              class="text-muted">Mar 1-31</span></label>
          </div><!-- /.custom-control -->
          <!-- .custom-control -->
          <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="dpYear" name="dpFilter" value="4"> <label
              class="custom-control-label d-flex justify-content-between" for="dpYear"><span>This Year</span> <span
              class="text-muted">2019</span></label>
          </div><!-- /.custom-control -->
          <!-- .custom-control -->
          <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="dpCustom" name="dpFilter" value="5"> <label
              class="custom-control-label" for="dpCustom">Custom</label>
            <div class="custom-control-hint my-1">
              <!-- datepicker:range -->
              <input type="text" class="form-control flatpickr-input" data-toggle="flatpickr" data-mode="range"
                     data-date-format="Y-m-d" readonly="readonly"> <!-- /datepicker:range -->
            </div>
          </div><!-- /.custom-control -->
        </div><!-- /.dropdown-menu -->
      </div><!-- /.dropdown -->
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
          <div class="chartjs" style="height: 253px">
            <div class="chartjs-size-monitor">
              <div class="chartjs-size-monitor-expand">
                <div class=""></div>
              </div>
              <div class="chartjs-size-monitor-shrink">
                <div class=""></div>
              </div>
            </div>
            <canvas id="canvas-achievement" style="display: block; width: 585px; height: 253px;" width="585" height="253" class="chartjs-render-monitor"></canvas>
          </div>
        </div><!-- /.card -->
      </div><!-- /grid column -->
      <!-- grid column -->
      <div class="col-xl-4">
        <!-- .card -->
        <div class="card card-fluid">
          <!-- .card-header -->
          <div class="card-header"> Overview</div><!-- /.card-header -->
          <!-- .card-body -->
          <div class="card-body">
            <dl class="d-flex justify-content-between">
              <dt class="text-left">
                <span class="mr-2">Revenue</span> <small class="text-success"><i class="fa fa-caret-up"></i> 87%</small>
              </dt>
              <dd class="text-right mb-0">
                <small class="text-muted">Rp. </small> <strong>{{ finace.debit | convert }}</strong>
              </dd>
            </dl>
          </div><!-- /.card-body -->
          <!-- .card-body -->
          <div class="card-body border-top">
            <dl class="d-flex justify-content-between">
              <dt class="text-left">
                <span class="mr-2">Expenses</span> 
                <small class="text-danger"><i class="fa fa-caret-down"></i>12%</small>
              </dt>
              <dd class="text-right mb-0">
                <small class="text-muted">Rp. </small> <strong>{{ finace.credit | convert }}</strong>
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
                <span class="text-muted">Rp. </span> 
                <strong class="h3">{{ finace.debit - finace.credit | convert}}</strong> 
              </p>
            </div>
          </div><!-- /.card-body -->
        </div><!-- /.card -->
      </div><!-- /grid column -->
    </div>
  </div>
</template>

<script>

import $axios from "../../api";
import Canvas from "./Chart";
import Vue from 'vue';

export default {
  data() {
    return {
      finace: []
    }
  },
  mounted() {
    finace: {
      $axios.get(`finace`).then((response) => {
        this.finace = response.data;
      }).catch((error) => {
        console.log(error)
      })
    }
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
  components: {
    'canvas' : Canvas
  }
}
</script>