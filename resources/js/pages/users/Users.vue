<template>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <router-link :to="{ name: 'persons.add' }" class="btn btn-primary btn-sm btn-flat">Tambah</router-link>
                <div class="pull-right">
                    <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                </div>
            </div>
            <div class="panel-body">
                <b-table striped hover bordered responsive :items="person.data" :fields="fields" show-empty>
                    <template v-slot:cell(role)="row">
                        <span class="label label-success" v-if="row.item.role == 0">Super Admin</span>
                        <span class="label label-primary" v-else-if="row.item.role == 1">Admin</span>
                        <span class="label label-default" v-else-if="row.item.role == 2">Keuangan</span>
                        <span class="label label-default" v-else>Kurir</span>
                    </template>
                    <template v-slot:cell(actions)="row">
                        <router-link :to="{ name: 'persons.edit', params: {id: row.item.id} }"
                            class="btn btn-warning btn-sm">
                            <i class="fa fa-pencil"></i>
                        </router-link>
                        <button class="btn btn-danger btn-sm" @click="deletePerson(row.item.id)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </template>
                </b-table>

                <div class="row">
                    <div class="col-md-6">
                        <p v-if="person.data">
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
                                    aria-controls="person"
                                    v-if="person.data && person.data.length > 0">
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
                    this.removePerson(id)
                }
            })
        }
    }
}
</script>