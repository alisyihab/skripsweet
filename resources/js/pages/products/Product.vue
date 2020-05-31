<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Data Product</h4>
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
                        <a href="#" data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 20px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <div class="dropdown-title">Aksi</div>
                            <router-link
                                :to="{ name: 'products.edit', params: {id: row.item.id} }"
                                class="dropdown-item has-icon">
                                <i class="fas fa-pen" style="color:#6777ef"></i>Edit Data
                            </router-link>
                            <button
                                class="dropdown-item has-icon" @click="deleteProduct(row.item.id)">
                                <i class="fa fa-trash-alt" style="color:red"></i>Hapus Data
                            </button>
                        </div>
                    </template>
                    <template v-slot:cell(service)="row">
                        {{ row.item.service }} {{ row.item.service_type }}
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
                            <b-pagination-nav
                                    v-model="page"
                                    :total-rows="products.meta.total"
                                    :per-page="products.meta.per_page"
                                    aria-controls="couriers"
                                    v-if="products.data && products.data.length > 0"
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
