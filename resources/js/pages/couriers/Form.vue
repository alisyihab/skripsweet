<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label>Nama Lengkap</label>
            <input type="text" class="form-control" v-model="courier.name" :readonly="$route.name == 'outlets.edit'">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.email }">
            <label>Email</label>
            <input type="text" class="form-control" v-model="courier.email">
            <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.password }">
            <label>Password</label>
            <input type="password" class="form-control" v-model="courier.password">
            <p class="text-warning" v-show="$route.name === 'couriers.edit'">
                Leave blank if you don't want to change password
            </p>
            <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.outlet_id }">
            <label>Outlet</label>
            <select name="outlet_id" class="form-control" v-model="courier.outlet_id">
                <option value="">Pilih</option>
                <option v-for="(row, index) in outlets.data" :value="row.id" :key="index">{{ row.name }}</option>
            </select>
            <p class="text-danger" v-if="errors.outlet_id">{{ errors.outlet_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.photo }">
            <label>Foto</label>
            <input type="file" class="form-control" accept="image/*" @change="uploadImage($event)" id="file-input">
            <div id="preview">
                <img class="img-responsive" v-if="url" :src="url"/>
            </div>
            <p class="text-warning" v-show="$route.name === 'couriers.edit'">
                Leave blank if you don't want to change photo
            </p>
            <p class="text-danger" v-if="errors.photo">{{ errors.photo[0] }}</p>
        </div>
    </div>
</template>

<style>
    #preview {
        padding-top: 20px;
        padding-bottom: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #preview img {
        max-width: 400p;
    }

    @media only screen and (max-width: 768px) {
        /* For mobile phones: */
        #preview img {
            width: 70%;
        }
    }
</style>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'

export default {
    name: 'FormCourier',
    created() {
        this.getOutlets();
        if (this.$route.name === 'couriers.edit') {
            this.editCourier(this.$route.params.id).then((res) => {
                this.courier = {
                    name: res.data.name,
                    email: res.data.email,
                    password: '',
                    photo: '',
                    outlet_id: res.data.outlet_id
                }
            })
        }
    },
    data() {
        return {
            url: null,
            courier: {
                name: '',
                email: '',
                password: '',
                photo: '',
                outlet_id: ''
            }
        }
    },
    computed: {
        ...mapState(['errors']),
        ...mapState('outlet', {
            outlets: state => state.outlets
        })
    },
    methods: {
        ...mapActions('outlet', ['getOutlets']),
        ...mapActions('courier', ['submitCourier', 'editCourier', 'updateCourier']),
        ...mapMutations('courier', ['SET_ID_UPDATE']),
        onFileChange(event) {
            const file = event.target.files[0];
            this.url = URL.createObjectURL(file);
        },
        uploadImage(event) {
            this.courier.photo = event.target.files[0];
            this.url = URL.createObjectURL(this.courier.photo)
        },
        submit() {
            let form = new FormData();
            form.append('name', this.courier.name);
            form.append('email', this.courier.email);
            form.append('password', this.courier.password);
            form.append('outlet_id', this.courier.outlet_id);
            form.append('photo', this.courier.photo);

            if (this.$route.name === 'couriers.add') {
                this.submitCourier(form).then(() => {
                    this.courier = {
                        name: '',
                        email: '',
                        password: '',
                        photo: '',
                        outlet_id: ''
                    };
                     this.$swal.fire(
                        'Success!',
                        'Data Berhasil Disimpan.',
                        'success'
                    );
                    this.$router.push({ name: 'couriers.data' })
                })
            } else if (this.$route.name === 'couriers.edit') {
                this.SET_ID_UPDATE(this.$route.params.id);
                this.updateCourier(form).then(() => {
                    this.courier = {
                        name: '',
                        email: '',
                        password: '',
                        photo: '',
                        outlet_id: ''
                    };
                     this.$swal.fire(
                        'Success!',
                        'Data Berhasil Diubah!.',
                        'success'
                    );
                    this.$router.push({ name: 'couriers.data' })
                })
            }
        }
    }
}
</script>
