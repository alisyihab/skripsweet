<template>
    <div>
        <div class="form-group">
            <label>Nik</label>
            <input type="number" class="form-control" :class="{ 'is-invalid': errors.nik }" v-model="customer.nik" :disabled="$route.name == 'customers.edit'">
            <div class="invalid-feedback" v-if="errors.nik">
                <i class="fa fa-exclamation-circle fa-fw"></i> 
                {{ errors.nik[0] }} 
            </div>
        </div>
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" class="form-control" :class="{ 'is-invalid': errors.name }" v-model="customer.name">
            <div class="invalid-feedback" v-if="errors.name">
                <i class="fa fa-exclamation-circle fa-fw"></i> 
                {{ errors.name[0] }} 
            </div>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" :class="{ 'is-invalid': errors.email }" v-model="customer.email" :disabled="$route.name == 'customers.edit'">
            <div class="invalid-feedback" v-if="errors.email">
                <i class="fa fa-exclamation-circle fa-fw"></i> 
                {{ errors.email[0] }} 
            </div>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" :class="{ 'is-invalid': errors.password }" v-model="customer.password">
            <p class="text-warning" v-show="$route.name === 'customers.edit'">
                Biarkan kosong bila tidak ingin mengganti password.
            </p>
            <div class="invalid-feedback" v-if="errors.password">
                <i class="fa fa-exclamation-circle fa-fw"></i> 
                {{ errors.password[0] }} 
            </div>
        </div>
         <div class="form-group">
            <label>Alamat</label>
             <editor
                v-model="customer.address"
                :class="{ 'is-invalid': errors.address }"
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
        <div class="form-group">
            <label>No Telp</label>
            <the-mask
                type="tel"
                :mask="['####-####-####']"
                class="form-control"
                :class="{ 'is-invalid': errors.phone }"
                v-model="customer.phone" 
            />
            <div class="invalid-feedback" v-if="errors.phone">
                <i class="fa fa-exclamation-circle fa-fw"></i> 
                {{ errors.phone[0] }} 
            </div>
        </div>
        <div class="form-group">
            <label>Foto</label>
            <input type="file" class="form-control" :class="{ 'is-invalid': errors.photo }" accept="image/*" @change="uploadImage($event)" id="file-input">
            <div id="preview">
                <img class="img-responsive" v-if="url" :src="url"/>
            </div>
            <p class="text-warning" v-show="$route.name === 'customers.edit'">
                Biarkan kosong bila tidak ingin mengganti photo.
            </p>
            <div class="invalid-feedback" v-if="errors.photo">
                <i class="fa fa-exclamation-circle fa-fw"></i> 
                {{ errors.photo[0] }} 
            </div>
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
