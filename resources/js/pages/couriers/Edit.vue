<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Edit Data Kurir</h4>
            </div>
            <div class="card-body">
                <courier-form ref="courierForm"></courier-form>
                <div class="form-group">
                    <router-link :to="{ name: 'couriers.data' }" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-alt-circle-left"></i>
                        Kembali
                    </router-link>
                    <button class="btn btn-outline-primary" @click.prevent="submit">
                        <i class="fa fa-save"></i>
                        Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapState } from 'vuex'
    import FormCourier from './Form.vue'
    export default {
        name: "EditCourier",
        methods: {
            submit() {
                this.$refs.courierForm.submit()
            }
        },
        components: {
            'courier-form': FormCourier
        }
    }
</script>
