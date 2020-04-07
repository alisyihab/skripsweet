<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label>Nama Item</label>
            <input type="text" class="form-control" v-model="product.name" placeholder="Kemeja">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.unit_types }">
            <label>Tipe</label>
            <select v-model="product.unit_types" class="form-control">
                <option value="">Pilih</option>
                <option value="Kilogram">Kilogram</option>
                <option value="Potong">Potong</option>
            </select>
            <p class="text-danger" v-if="errors.unit_types">{{ errors.unit_types[0] }}</p>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" :class="{ 'has-error': errors.laundry_type }">
                    <label>
                        Jenis Jasa
                        <sup>
                            <a @click="showForm = true"
                               href="javascript:void(0)"
                               v-if="!showForm"
                               v-show="$route.name === 'products.add'">
                                Add New
                            </a>
                        </sup>
                    </label>
                    <select v-model="product.laundry_type" class="form-control">
                        <option value="">Pilih</option>
                        <option v-for="(row, index) in laundry_types" :key="index" :value="row.id">{{ row.name }}</option>
                    </select>
                    <p class="text-danger" v-if="errors.laundry_type">{{ errors.laundry_type[0] }}</p>
                </div>
            </div>

            <div class="col-md-6" v-if="showForm">
                <div class="form-group" :class="{ 'has-error': errors.name_laundry_type }">
                    <label>&nbsp;</label>
                    <div class="input-group">
                        <input type="text" placeholder="Cuci Kering + Setrika" v-model="laundry_type" class="form-control">
                        <a href="javascript:void(0)" class="input-group-addon btn btn-primary btn-sm" id="basic-addon2" @click="addNewLaundryType">Save</a>
                    </div>
                    <p class="text-danger" v-if="errors.name_laundry_type">{{ errors.name_laundry_type[0] }}</p>
                </div>
            </div>
        </div>

        <div class="form-group" :class="{ 'has-error': errors.price }">
            <label>Harga</label>
            <money class="form-control"
                   type="tel"
                   v-model.lazy="product.price"
                   v-bind="money" />
            <p class="text-danger" v-if="errors.price">{{ errors.price[0] }}</p>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import {Money} from 'v-money'

export default {
    name: 'FormCourier',
    created() {
        this.getLaundryType();
        if (this.$route.name === 'products.edit') {
            this.editProduct(this.$route.params.id).then((res) => {
                this.product = {
                    name: res.data.name,
                    unit_types: res.data.unit_types,
                    price: res.data.price,
                    laundry_type: res.data.laundry_type_id
                }
            })
        }
    },
    data() {
        return {
            product: {
                name: '',
                unit_types: '',
                price: '',
                laundry_type: ''
            },
            laundry_type: '',
            showForm: false,
            money: {
                decimal: ',',
                thousands: '.',
                prefix: 'IDR ',
                suffix: '',
                precision: 0,
                masked: false /* doesn't work with directive */
            }
        }
    },
    computed: {
        ...mapState(['errors']),
        ...mapState('product', {
            laundry_types: state => state.laundry_types
        })
    },
    methods: {
        ...mapActions('product', ['getLaundryType', 'addLaundryType', 'addProductLaundry', 'editProduct', 'updateCourier']),

        addNewLaundryType() {
            this.addLaundryType({ name_laundry_type: this.laundry_type }).then(() => {
                this.getLaundryType().then(() => {
                    this.$swal.fire(
                        'Success!',
                        'Data Berhasil Disimpan.',
                        'success'
                    );
                    this.showForm = false;
                    this.laundry_type = ''
                })
            })
        },
        submit() {
            if (this.$route.name === 'products.add') {
                this.addProductLaundry(this.product).then(() => {
                    this.product = {
                        name: '',
                        unit_types: '',
                        price: '',
                        laundry_type: ''
                    };
                    this.$swal.fire(
                        'Success!',
                        'Data Berhasil Disimpan.',
                        'success'
                    );
                    this.$router.push({ name: 'products.data' })
                })
            } else if (this.$route.name === 'products.edit') {
                Object.assign(this.product, { id: this.$route.params.id })
                this.updateCourier(this.product).then(() => {
                    this.product = {
                        name: '',
                        unit_types: '',
                        price: '',
                        laundry_type: ''
                    };
                    this.$swal.fire(
                        'Success!',
                        'Data Berhasil Diubah!.',
                        'success'
                    );
                    this.$router.push({ name: 'products.data' })
                })
            }
        }
    },
    components: {Money}
}
</script>
