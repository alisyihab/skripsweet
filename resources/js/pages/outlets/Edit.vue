<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Edit Outlet</h4>
            </div>
            <div class="card-body">
                <outlet-form></outlet-form>
                <div class="form-group">
                     <router-link :to="{ name: 'outlets.data' }" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-alt-circle-left"></i>
                        Kembali
                    </router-link>
                    <button class="btn btn-outline-primary" @click.prevent="submit">
                        <i class="fa fa-save"></i> Ubah
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapState } from 'vuex'
    import FormOutlets from './Form.vue'
    export default {
        name: 'EditOutlet',
        created() {
            this.editOutlet(this.$route.params.id)
        },
        methods: {
            ...mapActions('outlet', ['editOutlet', 'updateOutlet']),
            submit() {
                this.updateOutlet(this.$route.params.id).then(() => {
                    this.$swal.fire(
                        'Success!',
                        'Data Berhasil Diubah!',
                        'success'
                    );
                    this.$router.push({ name: 'outlets.data' })
                })
            }
        },
        components: {
            'outlet-form': FormOutlets
        },
    }
</script>
