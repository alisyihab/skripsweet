<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Data Kurir</h4>
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
                <b-table striped hover bordered responsive :items="couriers.data" :fields="fields" show-empty>
                    <template v-slot:cell(photo)="row">
                        <img :src="'/storage/couriers/' + row.item.photo" :width="80" :height="50" :alt="row.item.name">
                    </template>
                    <template v-slot:cell(outlet_id)="row">
                        {{ row.item.outlet.name }}
                    </template>
                    <template v-slot:cell(actions)="row">
                        <router-link :to="{ name: 'couriers.edit', params: {id: row.item.id} }" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </router-link>
                        <button class="btn btn-danger btn-sm" @click="deleteCourier(row.item.id)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </template>
                </b-table>

                <div class="row">
                    <div class="col-md-6">
                        <p v-if="couriers.data" style="padding-left: 20px;">
                            <i class="fa fa-bars"></i>
                            {{ couriers.data.length }} item dari {{ couriers.meta.total }}
                            total data
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <b-pagination
                                    v-model="page"
                                    :total-rows="couriers.meta.total"
                                    :per-page="couriers.meta.per_page"
                                    aria-controls="couriers"
                                    v-if="couriers.data && couriers.data.length > 0" align="right">
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
        name: 'DataCourier',
        created() {
            this.getCouriers()
        },
        data() {
            return {
                fields: [
                    {key: 'photo', label: '#'},
                    {key: 'name', label: 'Nama Lengkap'},
                    {key: 'email', label: 'Email'},
                    {key: 'outlet_id', label: 'Outlet'},
                    {key: 'actions', label: 'Aksi'}
                ],
                search: ''
            }
        },
        computed: {
            ...mapState('courier', {
                couriers: state => state.couriers
            }),
            page: {
                get() {
                    return this.$store.state.courier.page
                },
                set(val) {
                    this.$store.commit('courier/SET_PAGE', val)
                }
            }
        },
        watch: {
            page() {
                this.getCouriers()
            },
            search() {
                this.getCouriers(this.search)
            }
        },
        methods: {
            ...mapActions('courier', ['getCouriers', 'removeCourier']),
            deleteCourier(id) {
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
                        this.removeCourier(id)
                    }
                })
            }
        }
    }
</script>
