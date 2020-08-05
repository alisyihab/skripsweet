<template>
    <div>
        <div class="form-group">
            <label>Nama Item</label>
            <input type="text" 
                class="form-control"
                :class="{ 'is-invalid': errors.name }" 
                v-model="product.name" 
                placeholder="Kemeja"
            >
            <div class="invalid-feedback" v-if="errors.name">
                <i class="fa fa-exclamation-circle fa-fw"></i> 
                {{ errors.name[0] }} 
            </div>
        </div>
        <div class="form-group">
            <label>Tipe</label>
            <select v-model="product.unit_type" class="form-control" :class="{ 'is-invalid': errors.unit_type }">
                <option value="">Pilih</option>
                <option value="Kilogram">Kilogram</option>
                <option value="Potong">Potong</option>
            </select>
            <div class="invalid-feedback" v-if="errors.unit_type">
                <i class="fa fa-exclamation-circle fa-fw"></i> 
                {{ errors.unit_type[0] }} 
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
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
                    <select v-model="product.laundry_type" class="form-control" :class="{ 'is-invalid': errors.laundry_type }">
                        <option value="">Pilih</option>
                        <option v-for="(row, index) in laundry_types" :key="index" :value="row.id">{{ row.name }}</option>
                    </select>
                    <div class="invalid-feedback" v-if="errors.laundry_type">
                        <i class="fa fa-exclamation-circle fa-fw"></i> 
                        {{ errors.laundry_type[0] }} 
                    </div>
                </div>
            </div>

            <div class="col-md-6" v-if="showForm">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="input-group input-group">
                        <div class="input-group-prepend">
                            <button class="btn btn-warning" @click="cancleAdd">Batal</button>
                        </div>
                        <input 
                            type="text" 
                            placeholder="Cuci Kering + Setrika" 
                            v-model="laundry_type" 
                            class="form-control"
                            :class="{ 'is-invalid': errors.name_laundry_type }"
                        >
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-sm" @click="addNewLaundryType">
                                Simpan
                            </button>
                        </div>
                    </div>
                    <div class="invalid-feedback" v-if="errors.name_laundry_type">
                        <i class="fa fa-exclamation-circle fa-fw"></i> 
                        {{ errors.name_laundry_type[0] }} 
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Harga</label>
            <money 
                class="form-control"
                :class="{ 'is-invalid': errors.price }"
                type="tel"
                v-model.lazy="product.price"
                v-bind="money" 
            />
            <div class="invalid-feedback" v-if="errors.price">
                <i class="fa fa-exclamation-circle fa-fw"></i> 
                {{ errors.price[0] }} 
            </div>
            <p class="text-danger" v-if="errors.price">{{ errors.price[0] }}</p>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Lama Pengerjaan</label>
                    <input type="number" class="form-control" :class="{'is-invalid': errors.service}" v-model="product.service">
                    <div class="invalid-feedback" v-if="errors.service">
                        <i class="fa fa-exclamation-circle fa-fw"></i> 
                        {{ errors.service[0] }} 
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Satuan</label>
                    <select class="form-control" :class="{'is-invalid': errors.service_type}" v-model="product.service_type">
                        <option value="">Pilih</option>
                        <option value="Hari">Hari</option>
                        <option value="Jam">Jam</option>
                    </select>
                    <div class="invalid-feedback" v-if="errors.service_type">
                        <i class="fa fa-exclamation-circle fa-fw"></i> 
                        {{ errors.service_type[0] }} 
                    </div>
                </div>
            </div>
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
                    unit_type: res.data.unit_type,
                    price: res.data.price,
                    laundry_type: res.data.laundry_type_id,
                    service: res.data.service,
                    service_type: res.data.service_type
                }
            })
        }
    },
    data() {
        return {
            product: {
                name: '',
                unit_type: '',
                price: '',
                laundry_type: '',
                service: '',
                service_type: ''
            },
            laundry_type: '',
            showForm: false,
            money: {
                decimal: ',',
                thousands: '.',
                prefix: 'Rp. ',
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
        cancleAdd() {
            this.showForm = false
        },
        clearForm() {
          this.product = {
              name: '',
              unit_type: '',
              price: '',
              laundry_type: '',
              service: '',
              service_type: ''
          }
        },
        submit() {
            if (this.$route.name === 'products.add') {
                this.addProductLaundry(this.product).then(() => {
                    this.product = {
                        name: '',
                        unit_type: '',
                        price: '',
                        laundry_type: ''
                    };
                    this.$swal.fire(
                        'Success!',
                        'Data Berhasil Disimpan.',
                        'success'
                    );
                    this.clearForm();
                    this.$router.push({ name: 'products.data' })
                })
            } else if (this.$route.name === 'products.edit') {
                Object.assign(this.product, { id: this.$route.params.id })
                this.updateCourier(this.product).then(() => {
                    this.product = {
                        name: '',
                        unit_type: '',
                        price: '',
                        laundry_type: ''
                    };
                    this.$swal.fire(
                        'Success!',
                        'Data Berhasil Diubah!.',
                        'success'
                    );
                    this.clearForm();
                    this.$router.push({ name: 'products.data' })
                })
            }
        }
    },
    components: {Money}
}
</script>
