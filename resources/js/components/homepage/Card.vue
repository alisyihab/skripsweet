<template>
  <div class="section-block">
    <div class="metric-row" v-show="authenticated.role == 0">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card-metric">
          <div class="metric">
            <p class="metric-value h3">
              <sub><i class="oi oi-people text-warning"></i></sub>
              <span class="value">{{ totData.customer }}</span>
            </p>
            <h2 class="metric-label"> Pelanggan </h2>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card-metric">
          <div class="metric">
            <p class="metric-value h3">
              <sub><i class="fas fa-tshirt text-info"></i></sub>
              <span class="value">{{ totData.orders }}</span>
            </p>
            <h2 class="metric-label"> Laundry </h2>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card-metric">
          <div class="metric">
            <h2 class="metric-label text-success"> Pendapatan </h2>
            <p class="metric-value h1">
              <sup>Rp. </sup> <span class="value">{{ totData.income | convert }}</span>
            </p>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card-metric">
          <div class="metric">
            <h2 class="metric-label text-danger"> Pengeluaran </h2>
            <p class="metric-value h1">
              <sup>Rp. </sup> <span class="value">{{ totData.expanse | convert }}</span>
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="metric-row" v-show="authenticated.role == 1">
      <div class="col-12 col-sm-12 col-lg-6">
        <div class="card-metric">
          <div class="metric">
            <p class="metric-value h3">
              <sub><i class="oi oi-people text-warning"></i></sub>
              <span class="value">{{ totData.customer }}</span>
            </p>
            <h2 class="metric-label"> Pelanggan </h2>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-lg-6">
        <div class="card-metric">
          <div class="metric">
            <p class="metric-value h3">
              <sub><i class="fas fa-tshirt text-info"></i></sub>
              <span class="value">{{ totData.orders }}</span>
            </p>
            <h2 class="metric-label"> Laundry </h2>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import $axios from "../../api";
  import {mapActions, mapState} from 'vuex';

  export default {
    data() {
      return {
        totData: [],
      }
    },
    computed: {
      ...mapState('user', {
        authenticated: state => state.authenticated
      }),
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
    }
  }
</script>