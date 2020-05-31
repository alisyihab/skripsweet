<template>
    <div class="col-lg-8 col-md-12 col-sm-12">
        <div class="card">
                <div class="card-header">
                  <h4>Status Permohonan</h4>
                  <div class="card-header-action">
                    <router-link :to="{name: 'expenses.data'}" class="btn btn-primary">
                        Lihat Semua <i class="fas fa-chevron-right"></i>
                    </router-link>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive table-invoice">
                    <b-table striped hover responsive :items="expense" :fields="fields" show-empty>
                    <template v-slot:cell(description)="row">
                      <router-link
                        v-b-tooltip.hover.top="'Lihat data'" 
                        :to="{ name: 'expenses.view', params: {id: row.item.id} }"
                      >
                        {{ row.item.description }}
                    </router-link>
                    </template>
                    <template v-slot:cell(price)="row">
                      {{ row.item.price | currency('Rp.', '2', { spaceBetweenAmountAndSymbol: true }) }}
                    </template>
                    <template v-slot:cell(status)="row">
                        <span class="badge badge-success" v-if="row.item.status == 1">Diterima</span>
                        <span class="badge badge-primary" v-else-if="row.item.status == 0">Diproses</span>
                        <span class="badge badge-danger" v-else>Ditolak</span>
                    </template>
                    <template v-slot:cell(user)="row">
                        {{ row.item.user.name }}
                    </template>
                    <template v-slot:cell(reason)="row">
                        <span v-if="row.item.reason !== null">{{ row.item.reason.substr(0, 15) }} ..</span>
                        <span v-else class="text-center">-</span>
                    </template>
                </b-table>
                  </div>
                </div>
              </div>
            </div>
</template>

<script>
    import $axios from "../../api";
    import Vue from 'vue'
    import moment from 'moment'

    export default {
      data() {
        return {
          expense: [],
          fields: [
                { key: 'description', label: 'Permintaan' },
                { key: 'price', label: 'Biaya' },
                { key: 'note', label: 'Catatan' },
                { key: 'user', label: 'Admin' },
                { key: 'status', label: 'Status' },
                { key: 'reason', label: 'Alasan' },
            ],
        }
      },
      mounted() {
        expense: {
          $axios.get(`getExpenseDashboard`).then((response) => {
            this.expense = response.data.data;
          }).catch((error) => {
            console.log(error)
          })
        }
      },
      filters: {
        formatDate(val) {
            return moment(new Date(val)).fromNow()
        }
      }
    }
</script>