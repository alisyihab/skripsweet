<template>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Edit Product</h3>
            </div>
            <div class="panel-body">
                <product-form ref="formProduct"></product-form>
                <div class="form-group">
                    <router-link :to="{name: 'products.data'}" class="btn btn-primary btn-sm btn-flat">
                        <i class="fa fa-arrow-circle-left"></i> Kembali
                    </router-link>
                    <button class="btn btn-primary btn-sm btn-flat" @click.prevent="submit">
                        <i class="fa fa-save"></i> Update
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
