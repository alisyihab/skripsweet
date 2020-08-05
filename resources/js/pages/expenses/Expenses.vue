<template>
  <div class="card">
    <div class="card-header">
      <h6>Data Permohonan</h6>
        <div class="float-right" style="width:40%">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <span class="oi oi-magnifying-glass"></span>
                    </span>
                </div>
                <input type="text" class="form-control" placeholder="Cari..." v-model="search">
            </div>
        </div>
    </div>
    <div class="card-body">
      <b-table striped hover responsive :items="expenses.data" :fields="fields" show-empty>
        <template v-slot:cell(status)="row">
          <span class="badge badge-success" v-if="row.item.status == 1">Diterima</span>
          <span class="badge badge-warning" v-else-if="row.item.status == 0">Diproses</span>
          <span class="badge badge-danger" v-else>Ditolak</span>
        </template>
        <template v-slot:cell(user)="row">
          {{ row.item.user.name }}
        </template>
        <template v-slot:cell(reason)="row">
          <span v-if="row.item.reason !== null">{{ row.item.reason.substr(0, 15) }} ..</span>
          <span v-else class="text-center">-</span>
        </template>
        <template v-slot:cell(actions)="row">
            <div class="dropdown">
                <button type="button" class="btn btn-icon btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-arrow"></div>
                    <router-link
                        :to="{ name: 'expenses.edit', params: {id: row.item.id} }"
                        class="dropdown-item has-icon" v-if="row.item.status == 0">
                        Edit Data
                    </router-link>
                    <router-link
                        :to="{ name: 'expenses.view', params: {id: row.item.id} }"
                        class="dropdown-item has-icon">
                    Lihat Data
                    </router-link>
                    <button
                        class="dropdown-item has-icon" @click="deleteExpenses(row.item.id)"
                        v-if="row.item.status == 0">
                        Hapus Data
                    </button>
                </div>
            </div>
        </template>
      </b-table>

      <div class="row">
        <div class="col-md-6">
          <p v-if="expenses.data" style="padding-left: 20px;">
            <i class="fa fa-bars"></i>
            {{ expenses.data.length }} item dari {{ expenses.meta.total }}
            total data
          </p>
        </div>
        <div class="col-md-6">
          <div class="pull-right">
            <b-pagination
                v-model="page"
                :total-rows="expenses.meta.total"
                :per-page="expenses.meta.per_page"
                aria-controls="couriers"
                v-if="expenses.data && expenses.data.length > 0"
                align="right">
            </b-pagination>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions, mapState} from 'vuex'

export default {
  name: 'DataExpenses',
  created() {
    this.getExpenses()
  },
  data() {
    return {
      fields: [
        {key: 'description', label: 'Permintaan'},
        {key: 'price', label: 'Biaya'},
        {key: 'note', label: 'Catatan'},
        {key: 'user', label: 'Admin'},
        {key: 'status', label: 'Status'},
        {key: 'reason', label: 'Alasan'},
        {key: 'actions', label: 'Aksi'}
      ],
      search: ''
    }
  },
  computed: {
    ...mapState('expenses', {
      expenses: state => state.expenses
    }),
    page: {
      get() {
        return this.$store.state.expenses.page
      },
      set(val) {
        this.$store.commit('expenses/SET_PAGE', val)
      }
    }
  },
  watch: {
    page() {
      this.getExpenses()
    },
    search() {
      this.getExpenses(this.search)
    }
  },
  methods: {
    ...mapActions('expenses', ['getExpenses', 'removeExpenses']),
    deleteExpenses(id) {
      this.$swal({
        title: 'Kamu Yakin?',
        text: "Tindakan ini akan menghapus secara permanent!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Iya, Lanjutkan!'
      }).then((result) => {
        if (result.value) {
          this.$swal.fire(
              'Success!',
              'Data Berhasil Dihapus.',
              'success'
          );
          this.removeExpenses(id)
        }
      })
    }
  }
}
</script>
