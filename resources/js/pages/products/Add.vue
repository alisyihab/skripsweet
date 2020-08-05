<template>
    <div class="card">
        <div class="card-header">
            <h6>Tambah Produk</h6>
        </div>
        <div class="card-body">
            <product-form ref="formProduct"></product-form>
            <div class="form-group">
                <router-link :to="{ name: 'products.data' }" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-alt-circle-left"></i>
                    Kembali
                </router-link>
                <button class="btn btn-outline-primary" @click.prevent="submit">
                    <i class="fa fa-save"></i> Tambah Baru
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapState, mapMutations } from 'vuex'
    import FormProduct from './Form.vue'

    export default {
        name: 'AddProduct',
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