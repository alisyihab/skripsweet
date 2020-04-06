<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Data Outlet</h4>
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
            <div class="card-body p-0">
                <b-table striped hover bordered responsive :items="outlets.data" :fields="fields" show-empty>
                    <template v-slot:cell(status)="row">
                        <span class="badge badge-success" v-if="row.item.status == 1">Active</span>
                        <span class="badge badge-secondary" v-else>Inactive</span>
                    </template>
                    <template v-slot:cell(actions)="row">
                        <router-link
                            :to="{ name: 'outlets.edit', params: {id: row.item.code} }"
                            class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </router-link>
                        <button class="btn btn-danger btn-sm" @click="deleteOutlet(row.item.id)"><i
                            class="fa fa-trash"></i></button>
                    </template>
                </b-table>

                <div class="row">
                    <div class="col-md-6">
                        <p v-if="outlets.data" style="padding-left: 20px;">
                            <i class="fa fa-bars"></i>
                            {{ outlets.data.length }} item dari {{ outlets.meta.total }}
                            total data
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <b-pagination
                                    v-model="page"
                                    :total-rows="outlets.meta.total"
                                    :per-page="outlets.meta.per_page"
                                    aria-controls="outlets"
                                    v-if="outlets.data && outlets.data.length > 0" align="right">
                            </b-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapState} from 'vuex'

    export default {
        name: 'DataOutlet',
        created() {
            this.getOutlets()
        },
        data() {
            return {
                fields: [
                    {key: 'code', label: 'Kode Outlet'},
                    {key: 'name', label: 'Nama Outlet'},
                    {key: 'address', label: 'Alamat'},
                    {key: 'phone', label: 'Telp'},
                    {key: 'status', label: 'Status'},
                    {key: 'actions', label: 'Aksi'}
                ],
                search: ''
            }
        },
        computed: {
            ...mapState('outlet', {
                outlets: state => state.outlets
            }),
            page: {
                get() {
                    return this.$store.state.outlet.page
                },
                set(val) {
                    this.$store.commit('outlet/SET_PAGE', val)
                }
            }
        },
        watch: {
            page() {
                this.getOutlets()
            },
            search() {
                this.getOutlets(this.search)
            }
        },
        methods: {
            ...mapActions('outlet', ['getOutlets', 'removeOutlet']),
            deleteOutlet(id) {
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
                            'Deleted!',
                            'Data Berhasil Dihapus.',
                            'success'
                        );
                        this.removeOutlet(id)
                    }
                })
            }
        }
    }
</script>
