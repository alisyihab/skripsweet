<template>
        <div class="card">
            <div class="card-header">
                <h6>Data Product</h6>
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
                <b-table striped hover bordered responsive :items="products.data" :fields="fields" show-empty>
                    <template v-slot:cell(laundry_type)="row">
                        {{ row.item.type.name }}
                    </template>
                    <template v-slot:cell(user_id)="row">
                        {{ row.item.user.name }}
                    </template>
                    <template v-slot:cell(price)="row">
                        {{ row.item.price | currency('Rp.', '2', { spaceBetweenAmountAndSymbol: true }) }}
                    </template>
                    <template v-slot:cell(actions)="row">
                        <div class="dropdown">
                            <button type="button" class="btn btn-icon btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                                <div class="dropdown-menu dropdown-menu-right">
                                <div class="dropdown-arrow"></div>
                                <router-link
                                :to="{ name: 'products.edit', params: {id: row.item.id} }"
                                class="dropdown-item has-icon">
                                Edit Data
                                </router-link>
                                <button
                                    class="dropdown-item has-icon" @click="deleteProduct(row.item.id)">
                                    Hapus Data
                                </button>
                            </div>
                        </div>
                    </template>
                    <template v-slot:cell(service)="row">
                        {{ row.item.service }} {{ row.item.service_type }}
                    </template>
                </b-table>

                <div class="row">
                    <div class="col-md-6">
                        <p v-if="products.data">
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
</template>

<script>
    import {mapActions, mapState} from "vuex";
    import Vue from 'vue'
    import Vue2Filters from 'vue2-filters'

    Vue.use(Vue2Filters);

    export default {
        name: "DataCourier",
        created() {
            this.getProducts()
        },
        data() {
            return {
                fields: [
                    {key: 'name', label: 'Nama Item', sortable: true},
                    {key: 'unit_type', label: 'Tipe', sortable: true},
                    {key: 'laundry_type', label: 'Jenis jasa', sortable: true},
                    {key: 'price', label: 'Harga', sortable: true},
                    {key: 'user_id', label: 'Admin', sortable: true},
                    {key: 'service', label: 'Lama Pengerjaan', sortable: true},
                    {key: 'actions', label: 'Aksi'}
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
        },
        components: {
            mixins: [Vue2Filters.mixin],
        }
    }
</script>
