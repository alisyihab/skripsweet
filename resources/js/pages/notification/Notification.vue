<template>
  <div class="section-block">
    <span v-for="day in days">
      <h2 class="section-title"> {{ day.created_at | formatDate }} </h2>
      <ul class="timeline">
        <li class="timeline-item"  v-for="(row, i) in day.notifications" :key="i">
          <div class="timeline-figure">
            <span class="tile tile-circle">
                <i class="fas fa-wallet text-success" v-if="row.data.payment"></i>
                <i class="fas fa-hand-holding-usd text-danger" v-if="row.data.expenses"></i>
                <i class="fas fa-file-invoice-dollar text-success" v-if="row.data.transaction"></i>
                <i class="fas fa-info text-primary" v-if="row.data.detailTransaction"></i>
                <i class="fas fa-tshirt text-warning" v-if="row.data.completeItem"></i>
            </span>
          </div>
          <div class="timeline-body">
            <div class="media">
              <div class="media-body">
                <h6 class="timeline-heading">
                  <span v-if="row.data.expenses">
                    <router-link :to="{ name: 'expenses.view', params: { id: row.data.expenses.id } }" class="text-link">
                      Pengeluaran
                    </router-link>
                  </span>
                  <span v-if="row.data.payment">
                    <router-link :to="{ name: 'transaction.payment', params: { id: row.data.payment.transaction_id } }" class="text-link">
                      Pembayaran
                    </router-link>  
                  </span>
                  <span v-if="row.data.transaction">
                    <router-link :to="{ name: 'transactions.view', params: {id: row.data.transaction.id } }" class="text-link">
                      Pembayaran diterima
                    </router-link>
                  </span>
                  <span v-if="row.data.detailTransaction">
                    <router-link :to="{ name: 'transactions.view', params: { id: row.data.detailTransaction.id} }" class="text-link">
                      Order
                    </router-link>
                  </span>
                  <span v-if="row.data.completeItem">
                    <router-link :to="{ name: 'transactions.view', params: { id: row.data.completeItem.transaction_id} }" class="text-link">
                      Laundry
                    </router-link>
                  </span>
                </h6>
                <p class="mb-0">
                  <b>{{ row.data.sender_name }}</b> 
                    melakukan
                    <span v-if="row.data.expenses" class="badge badge-danger">pengeluaran</span> 
                    <span v-if="row.data.payment" class="badge badge-success">pembayaran</span> 
                    <span v-if="row.data.transaction" class="badge badge-info">pengecekan pembayaran</span> 
                    <span v-if="row.data.detailTransaction" class="badge badge-info">order</span> 
                    <span v-if="row.data.completeItem" class="badge badge-success">Penyelesaian Laundry</span> 
                </p>
                <p class="mt-1">
                  <span v-if="row.data.expenses">
                    <b>{{ row.data.expenses.description.substr(0, 30) }}</b> <br>
                    <span>pengeluaran sebesar : {{ row.data.expenses.price | currency('Rp', '2', { spaceBetweenAmountAndSymbol: true }) }} </span> 
                  </span>
                  <span v-if="row.data.detailTransaction">
                    <b>{{row.data.note}}</b><br>
                  </span>
                  <span v-if="row.data.transaction">
                    {{row.data.note}} 
                  </span>
                  <span v-if="row.data.payment">
                    Pembayaran sebesar {{ row.data.payment.amount | currency('Rp', '2', { spaceBetweenAmountAndSymbol: true })}} 
                  </span>
                  <span v-if="row.data.completeItem">
                    {{ row.data.note }}
                  </span>
                </p>
                <p class="timeline-date d-sm-none"> About a minute ago </p>
              </div>
              <div class="d-none d-sm-block">
                <span class="timeline-date"> {{ row.created_at | formatDay }} </span>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </span>
    <infinite-loading @infinite="infiniteHandler"></infinite-loading>
  </div>
</template>

<script>
  import $axios from "../../api";
  import moment from 'moment'
  import InfiniteLoading from 'vue-infinite-loading';

  export default {
    data() {
      return {
        notifications: [],
        page: 1
      }
    },
    methods: {
      infiniteHandler($state) {
        $axios.get(`list-notif`, {
          params: {
            page: this.page,
          },
        }).then(({ data }) => {
          if (data.result.data.length) {
            this.page += 1;
            this.notifications.push(...data.result.data);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
      }
    },
    filters: {
      formatDate(val) {
        return moment(new Date(val)).format('dddd, Do MMMM YYYY')
      },
      formatDay(val) {
        return moment(new Date(val)).fromNow()
      },
    },
    computed: {
      days () {
        const map = {}
        
        for (const activity of this.notifications) {
          const day = activity.created_at.slice(0, 10)
          
          map[day] = map[day] || { created_at: day, notifications: [] }
          map[day].notifications.push(activity)
        }
        
        return Object.keys(map).sort().map(day => map[day]).reverse()
      }
    },
    components: {
        InfiniteLoading
    }
  }
</script>