<template>
  <div class="card">
    <div class="card-header">
      <h6>Data Pengguna</h6>
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
      <b-table striped hover bordered responsive :items="person.data" :fields="fields" show-empty>
        <template v-slot:cell(role)="row">
          <span class="badge badge-success" v-if="row.item.role == 0">Super Admin</span>
          <span class="badge badge-primary" v-else-if="row.item.role == 1">Admin</span>
          <span class="badge badge-info" v-else-if="row.item.role == 2">Keuangan</span>
          <span class="badge badge-default" v-else>Belum di set</span>
        </template>
        <template v-slot:cell(actions)="row">
          <div class="dropdown">
            <button type="button" class="btn btn-icon btn-light" data-toggle="dropdown" aria-expanded="false"><i
              class="fa fa-ellipsis-v"></i></button>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-arrow"></div>
              <router-link
                :to="{ name: 'persons.edit', params: {id: row.item.id} }"
                class="dropdown-item has-icon">
                Edit Data
              </router-link>
              <button
                class="dropdown-item has-icon"
                @click="deletePerson(row.item.id)">
                Hapus Data
              </button>
            </div>
          </div>
        </template>
      </b-table>
      
      <div class="row">
        <div class="col-md-6">
          <p v-if="person.data" style="padding-left: 20px;">
            <i class="fa fa-bars"></i>
            {{ person.data.length }} item dari {{ person.meta.total }}
            total data
          </p>
        </div>
        <div class="col-md-6">
          <div class="pull-right">
            <b-pagination-nav
              v-model="page"
              :total-rows="person.meta.total"
              :per-page="person.meta.per_page"
              aria-controls="couriers"
              v-if="person.data && person.data.length > 0"
              align="right">
            </b-pagination-nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import {mapActions, mapState} from 'vuex'

  export default {
    name: 'DataPersons',
    created() {
      this.getPerson()
    },
    data() {
      return {
        fields: [
          {key: 'name', label: 'Nama'},
          {key: 'email', label: 'Email'},
          {key: 'role', label: 'role'},
          {key: 'actions', label: 'Aksi'}
        ],
        search: ''
      }
    },
    computed: {
      ...mapState('person', {
        person: state => state.person
      }),
      page: {
        get() {
          return this.$store.state.person.page
        },
        set(val) {
          this.$store.commit('person/SET_PAGE', val)
        }
      }
    },
    watch: {
      page() {
        this.getPerson()
      },
      search() {
        this.getPerson(this.search)
      }
    },
    methods: {
      ...mapActions('person', ['getPerson', 'removePerson']),
      deletePerson(id) {
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
              'Data Berhasil Dihapus!',
              'success'
            );
            this.removePerson(id)
          }
        })
      }
    }
  }
</script>