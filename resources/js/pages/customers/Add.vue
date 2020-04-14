<template>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Tambah Data Customer</h3>
            </div>
            <div class="panel-body">
                <customer-form ref="formCourier"></customer-form>
                <div class="form-group">
                    <router-link :to="{name: 'customers.data'}" class="btn btn-primary btn-sm btn-flat">
                        <i class="fa fa-arrow-circle-left"></i> Kembali
                    </router-link>
                    <button class="btn btn-primary btn-sm btn-flat" @click.prevent="submit">
                        <i class="fa fa-save"></i> Tambah Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapState, mapMutations } from 'vuex'
    import FormCustomer from './Form.vue'

    export default {
        name: 'AddCusomer',
        data() {
            return {}
        },
        methods: {
            ...mapActions('customer', ['submitCustomer']),
            submit() {
                this.submitCustomer().then(() => {
                     this.$swal.fire(
                        'Success!',
                        'Data Berhasil Disimpan.',
                        'success'
                    );
                    this.$router.push({name: 'customers.data'})
                })
            }
        },
        components: {
            'customer-form': FormCustomer
        }
    }
</script>
