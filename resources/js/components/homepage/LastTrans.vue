<template>
  <div class="col-lg-4 col-md-12 col-12 col-sm-12">
    <div class="card">
      <div class="card-header">
        <h6>Transaksi Terahir</h6>
      </div>
      <div class="card-body">
        <ul class="list-unstyled list-unstyled-border"
            v-for="(row, index) in trans" :key="index">
          <li class="media">
            <img
              class="mr-3 rounded-circle"
              width="50"
              :src="'/storage/users/' + row.customer.photo"
              alt="avatar"
            />
            <div class="media-body">
              <div class="float-right text-primary">{{ row.created_at | date }}</div>
              <div class="media-title">
                <router-link :to="{ name: 'transactions.view', params: {id: row.id} }">
                  {{row.customer.name}}
                </router-link>
              </div>
              <span class="text-small text-muted">
                <b> Status</b> : <span v-html="row.status_label"></span>
              </span>
              <p class="text-medium text-muted mt-3">
                <strong>Item Jasa</strong> : {{ row.detail.length }}
                <span class="float-right">
                  {{ row.amount | currency('Rp', '2', { spaceBetweenAmountAndSymbol: true }) }}
                </span>
              </p>
              <p>
                <router-link v-if="row.status == 1"
                  v-show="$can('create transaction')"
                  :to="{ name: 'transaction.payment', params: {id: row.id} }">
                  Lihat
                  <i class="fas fa-arrow-right"></i>
                </router-link>
                <router-link v-else
                  :to="{ name: 'transactions.view', params: {id: row.id} }">
                  Lihat
                  <i class="fas fa-arrow-right"></i>
                </router-link>
              </p>
            </div>
          </li>
        </ul>
        <div class="text-center pt-1 pb-1">
          <router-link :to="{ name: 'transactions.list' }" class="btn btn-primary">
            Lihat Semua
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import {mapState, mapActions} from 'vuex';
  import $axios from '../../api';

  import dayjs from 'dayjs';
  import relativeTime from 'dayjs/plugin/relativeTime';
  import Vue2Filters from 'vue2-filters';

  require('dayjs/locale/id')

  export default {
    created() {
      dayjs.locale('id')
      dayjs.extend(relativeTime);
    },
    data() {
      return {
        trans: [],
        loading: false
      }
    },
    filters: {
      date: (date) => {
        if (!date) {
          null
        }

        return dayjs(date).fromNow();
      }
    },
    mounted() {
      transaction: {
        $axios.get(`last-trans`).then((response) => {
          this.loading = true
          this.trans = response.data.data;
        }).catch((error) => {
          console.log(error)
        })
        setTimeout(() => {
          this.loading = false
        }, 5000)
      }
    },
    components: {
      mixins: [Vue2Filters.mixin]
    }
  }
</script>