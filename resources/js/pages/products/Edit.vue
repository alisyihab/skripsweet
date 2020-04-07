<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Edit Data Laundry</h4>
            </div>
            <div class="card-body">
                <product-form ref="formProduct"></product-form>
                <div class="form-group">
                    <router-link :to="{ name: 'products.data' }" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-alt-circle-left"></i>
                        Kembali
                    </router-link>
                    <button class="btn btn-outline-primary" @click.prevent="submit">
                        <i class="fa fa-save"></i> Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import FormProduct from './Form.vue'

    export default {
        name: "AddProducts",
        methods: {
            submit() {
                this.$refs.formProduct.submit()
            }
        },
        components: {
            'product-form': FormProduct
        }
    }
</script>
