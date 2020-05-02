<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Data Pengguna</h4>
                <div class="card-header-action">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search" v-model="search">
                        <div class="input-group-btn">
                            <button class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
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
                        <router-link
                            v-b-tooltip.hover.top="'Edit'"
                            :to="{ name: 'persons.edit', params: {id: row.item.id} }"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-pen-square"></i>
                        </router-link>
                        <button
                            v-b-tooltip.hover.top="'Hapus'"
                            class="btn btn-danger btn-sm"
                            @click="deletePerson(row.item.id)">
                            <i class="fa fa-trash"></i>
                        </button>
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
    </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
    name: 'DataPersons',
    created() {
        this.getPerson()
    },
    data() {
        return {
            fields: [
                { key: 'name', label: 'Nama' },
                { key: 'email', label: 'Email' },
                { key: 'role', label: 'role' },
                { key: 'actions', label: 'Aksi' }
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