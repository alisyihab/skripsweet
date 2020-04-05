<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Add New Outlet</h4>
            </div>
            <div class="card-body">
                <outlet-form></outlet-form>
                <div class="form-group">
                    <router-link :to="{ name: 'outlets.data' }" class="btn btn-outline-secondary">
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
    import {mapActions, mapState, mapMutations} from 'vuex'
    import FormOutlets from './Form.vue'

    export default {
        name: 'AddOutlet',
        data() {
            return {}
        },
        methods: {
            ...mapActions('outlet', ['submitOutlet']),
            submit() {
                this.submitOutlet().then(() => {
                    this.$swal.fire(
                        'Success!',
                        'Data Berhasil Disimpan.',
                        'success'
                    );
                    this.$router.push({name: 'outlets.data'})
                })
            }
        },
        components: {
            'outlet-form': FormOutlets
        }
    }
</script>
