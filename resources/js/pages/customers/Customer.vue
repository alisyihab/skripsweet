<template>
     <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Data Pelanggan</h4>
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
                <b-table striped hover bordered responsive :items="customers.data" :fields="fields" show-empty>
                    <template v-slot:cell(photo)="row">
                        <img :src="'/storage/users/' + row.item.photo" :width="80" :alt="row.item.name">
                    </template>
                    <template v-slot:cell(deposit)="row">
                        {{ row.item.deposit | currency('IDR', '2', { spaceBetweenAmountAndSymbol: true }) }}
                    </template>
                    <template v-slot:cell(actions)="row">
                        <router-link :to="{ name: 'customers.edit', params: {id: row.item.id} }" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i>
                        </router-link>
                        <button class="btn btn-danger btn-sm" @click="deleteCustomer(row.item.id)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </template>
                </b-table>

                <div class="row">
                    <div class="col-md-6">
                        <p v-if="customers.data" style="padding-left: 20px;">
                            <i class="fa fa-bars"></i>
                            {{ customers.data.length }} item dari {{ customers.meta.total }}
                            total data
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <b-pagination-nav
                                    v-model="page"
                                    :total-rows="customers.meta.total"
                                    :per-page="customers.meta.per_page"
                                    aria-controls="couriers"
                                    v-if="customers.data && customers.data.length > 0"
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
    import {mapActions, mapState} from 'vuex'
    import Vue2Filters from 'vue2-filters'

    export default {
        name: 'DataCustomer',
        created() {
            this.getCustomers()
        },
        data() {
            return {
                fields: [
                    {key: 'photo', label: '#'},
                    {key: 'nik', label: 'NIK'},
                    {key: 'name', label: 'Nama Lengkap'},
                    {key: 'email', label: 'Email'},
                    {key: 'address', label: 'Alamat'},
                    {key: 'phone', label: 'No Hp'},
                    {key: 'point', label: 'Poin'},
                    {key: 'deposit', label: 'Deposit'},
                    {key: 'actions', label: 'Aksi'}
                ],
                search: ''
            }
        },
        computed: {
            ...mapState('customer', {
                customers: state => state.customers
            }),
            page: {
                get() {
                    return this.$store.state.customer.page
                },
                set(val) {
                    this.$store.commit('customer/SET_PAGE', val)
                }
            }
        },
        watch: {
            page() {
                this.getCustomers()
            },
            search() {
                this.getCustomers(this.search)
            }
        },
        methods: {
            ...mapActions('customer', ['getCustomers', 'removeCustomer']),
            deleteCustomer(id) {
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
                        this.removeCustomer(id);
                    }
                })
            }
        },
        components: {
            mixins: [Vue2Filters.mixin]
        }
    }
</script>
