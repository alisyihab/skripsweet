<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Buat Data Baru</h4>
            </div>
            <div class="card-body">
                <customer-form ref="formCustomer"></customer-form>
                <div class="form-group">
                    <router-link :to="{ name: 'customers.data' }" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-alt-circle-left"></i>
                        Kembali
                    </router-link>
                    <button class="btn btn-outline-primary" @click.prevent="submit">
                        <i class="fa fa-save"></i> Tambah Baru
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import FormCustomer from './Form.vue'

    export default {
        name: 'AddCustomer',
        methods: {
            submit() {
                this.$refs.formCustomer.submit()
            }
        },
        components: {
            'customer-form': FormCustomer
        }
    }
</script>
