<template>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Tambah Data Kurir</h3>
            </div>
            <div class="panel-body">
                <courier-form ref="formCourier"></courier-form>
                <div class="form-group">
                    <router-link :to="{name: 'couriers.data'}" class="btn btn-primary btn-sm btn-flat">
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
    import FormCourier from './Form.vue'

    export default {
        name: 'AddCourier',
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