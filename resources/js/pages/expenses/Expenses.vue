<template>
        <div class="card">
            <div class="card-header">
                <h4>Data Permohonan</h4>
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
                        <a href="#" data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 20px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <div class="dropdown-title">Aksi</div>
                            <router-link
                                :to="{ name: 'expenses.edit', params: {id: row.item.id} }"
                                class="dropdown-item has-icon" v-if="row.item.status == 0">
                                <i class="fas fa-pen"></i>Edit Data
                            </router-link>
                            <router-link
                                :to="{ name: 'expenses.view', params: {id: row.item.id} }"
                                class="dropdown-item has-icon">
                                <i class="fas fa-eye"></i> Lihat Data
                            </router-link>
                            <button
                                class="dropdown-item has-icon" @click="deleteExpenses(row.item.id)"
                                v-if="row.item.status == 0">
                                <i class="fa fa-trash-alt text-danger"></i>Hapus Data
                            </button>
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
import { mapActions, mapState } from 'vuex'

export default {
    name: 'DataExpenses',
    created() {
        this.getExpenses()
    },
    data() {
        return {
            fields: [
                { key: 'description', label: 'Permintaan' },
                { key: 'price', label: 'Biaya' },
                { key: 'note', label: 'Catatan' },
                { key: 'user', label: 'Admin' },
                { key: 'status', label: 'Status' },
                { key: 'reason', label: 'Alasan' },
                { key: 'actions', label: 'Aksi' }
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
