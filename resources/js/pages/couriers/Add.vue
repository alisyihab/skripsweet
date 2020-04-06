<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Add New Courier</h4>
            </div>
            <div class="card-body">
                <courier-form ref="formCourier"></courier-form>
                <div class="form-group">
                    <router-link :to="{ name: 'couriers.data' }" class="btn btn-outline-secondary">
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
    import { mapActions, mapState, mapMutations } from  'vuex'
    import FormCourier from './Form.vue'

    export default {
        name: "AddCourier",
        methods: {
            submit() {
                this.$refs.formCourier.submit()
            }
        },
        components: {
            'courier-form': FormCourier
        }
    }
</script>

<style scoped>

</style>