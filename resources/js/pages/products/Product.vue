<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Data Tipe Laundry</h4>
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
                <b-table striped hover bordered responsive :items="products.data" :fields="fields" show-empty>
                    <template v-slot:cell(laundry_type)="row">
                        {{ row.item.type.name }}
                    </template>
                    <template v-slot:cell(user_id)="row">
                        {{ row.item.user.name }}
                    </template>
                    <template v-slot:cell(actions)="row">
                        <router-link :to="{ name: 'products .edit', params: {id: row.item.id} }" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </router-link>
                        <button class="btn btn-danger btn-sm" @click="deleteProduct(row.item.id)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </template>
                </b-table>

                <div class="row">
                    <div class="col-md-6">
                        <p v-if="products.data" style="padding-left: 20px;">
                            <i class="fa fa-bars"></i>
                            {{ products.data.length }} item dari {{ products.meta.total }}
                            total data
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <b-pagination
                                    v-model="page"
                                    :total-rows="products.meta.total"
                                    :per-page="products.meta.per_page"
                                    aria-controls="couriers"
                                    v-if="products.data && products.data.length > 0"
                                    align="right">
                            </b-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapState} from "vuex";

    export default {
        name: "DataCourier",
        created() {
            this.getProducts()
        },
        data() {
            return {
                fields: [
                    { key: 'name', label: 'Nama Item' },
                    { key: 'unit_types', label: 'Tipe' },
                    { key: 'laundry_type', label: 'Jenis jasa' },
                    { key: 'price', label: 'Harga' },
                    { key: 'user_id', label: 'Admin' },
                    { key: 'actions', label: 'Aksi' }
                ],
                search: ''
            }
        },
        computed: {
            ...mapState('product', {
                products: state => state.products,
            }),
            page: {
                get() {
                    return this.$store.state.product.page
                },
                set(val) {
                    this.$store.commit('product/SET_PAGE', val)
                }
            }
        },
        watch: {
            page() {
                this.getProducts()
            },
            search() {
                this.getProducts(this.search)
            }
        },
        methods: {
            ...mapActions('product', ['getProducts', 'removeProduct']),
            deleteProduct(id) {
                this.$swal({
                    title: 'Kamu Yakin?',
                    text: 'Tindakan ini akan menghapus secara permanen!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, Lanjutkan!'
                }).then((result) => {
                    if (result.value) {
                        this.$swal.fire(
                            'Deleted!',
                            'Data berhasil dihapus!',
                            'success'
                        );
                        this.removeProduct(id)
                    }
                })
            }
        }
    }
</script>
