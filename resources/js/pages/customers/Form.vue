<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.nik }">
            <label>Nik</label>
            <input type="number" class="form-control" v-model="customer.nik" :disabled="$route.name == 'customers.edit'">
            <p class="text-danger" v-if="errors.name">{{ errors.nik[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label>Nama Lengkap</label>
            <input type="text" class="form-control" v-model="customer.name">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.email }">
            <label>Email</label>
            <input type="email" class="form-control" v-model="customer.email" :disabled="$route.name == 'customers.edit'">
            <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.password }">
            <label>Password</label>
            <input type="password" class="form-control" v-model="customer.password">
            <p class="text-warning" v-show="$route.name === 'customers.edit'">
                Biarkan kosong bila tidak ingin mengganti password.
            </p>
            <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
        </div>
         <div class="form-group" :class="{ 'has-error': errors.address }">
            <label>Alamat</label>
             <editor
                v-model="customer.address"
                api-key="ass6mxswiyrquok43dpktqx703k9dmrul7awy97gwfmakzjg"
                :init="{
                    height: 200,
                    menubar: false,
                    plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                    ],
                    toolbar:
                    'undo redo | formatselect | bold italic backcolor'
                }"
            />
            <!-- <textarea cols="10" rows="5" class="form-control" v-model="customer.address"></textarea> -->
            <p class="text-danger" v-if="errors.address">{{ errors.address[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.phone }">
            <label>No Telp</label>
            <the-mask
                type="tel"
                :mask="['####-####-####']"
                class="form-control"
                v-model="customer.phone" 
            />
            <p class="text-danger" v-if="errors.phone">{{ errors.phone[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.photo }">
            <label>Foto</label>
            <input type="file" class="form-control" accept="image/*" @change="uploadImage($event)" id="file-input">
            <div id="preview">
                <img class="img-responsive" v-if="url" :src="url"/>
            </div>
            <p class="text-warning" v-show="$route.name === 'customers.edit'">
                Biarkan kosong bila tidak ingin mengganti photo.
            </p>
            <p class="text-danger" v-if="errors.photo">{{ errors.photo[0] }}</p>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions, mapMutations } from 'vuex'
import {TheMask} from 'vue-the-mask'
import Editor from '@tinymce/tinymce-vue'


export default {
    name: 'FormCustomer',
    created() {
        if (this.$route.name === 'customers.edit') {
            this.editCustomer(this.$route.params.id).then((res) => {
                this.customer = {
                    nik: res.data.nik,
                    name: res.data.name,
                    email: res.data.email,
                    address: res.data.address,
                    phone: res.data.phone,
                    password: '',
                    photo: '',
                }
            })
        }
    },
    data() {
        return {
            url: null,
            customer: {
                nik: '',
                name: '',
                email: '',
                password: '',
                address: '',
                phone: '',
                photo: ''
            },
        }
    },
    computed: {
        ...mapState(['errors']),
    },
    methods: {
        ...mapActions('customer', ['submitCustomer', 'editCustomer', 'updateCustomer']),
        ...mapMutations('customer', ['SET_ID_UPDATE']),
        onFileChange(event) {
            const file = event.target.files[0];
            this.url = URL.createObjectURL(file);
        },
        uploadImage(event) {
            this.customer.photo = event.target.files[0];
            this.url = URL.createObjectURL(this.customer.photo)
        },
        submit() {
            let form = new FormData();
            form.append('nik', this.customer.nik);
            form.append('name', this.customer.name);
            form.append('email', this.customer.email);
            form.append('password', this.customer.password);
            form.append('address', this.customer.address);
            form.append('phone', this.customer.phone);
            form.append('photo', this.customer.photo)

            if (this.$route.name === 'customers.add') {
                this.submitCustomer(form).then(() => {
                    this.customer = {
                        nik: '',
                        name: '',
                        email: '',
                        password: '',
                        address: '',
                        phone: '',
                        photo: ''
                    };
                     this.$swal.fire(
                        'Success!',
                        'Data Berhasil Disimpan.',
                        'success'
                    );
                    this.$router.push({ name: 'customers.data' })
                })
            } else if (this.$route.name === 'customers.edit') {
                this.SET_ID_UPDATE(this.$route.params.id);
                this.updateCustomer(form).then(() => {
                    this.customer = {
                        nik: '',
                        name: '',
                        email: '',
                        password: '',
                        address: '',
                        phone: '',
                        photo: ''
                    };
                     this.$swal.fire(
                        'Success!',
                        'Data Berhasil Diubah!.',
                        'success'
                    );
                    this.$router.push({ name: 'customers.data' })
                })
            }
        }
    },
    components: {
        'editor': Editor,
        TheMask
    }
}
</script>

<style>
    #preview {
        padding-top: 20px;
        padding-bottom: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #preview img {
        max-width: 400px;
    }

    @media only screen and (max-width: 768px) {
        /* For mobile phones: */
        #preview img {
            width: 70%;
        }
    }
</style>
