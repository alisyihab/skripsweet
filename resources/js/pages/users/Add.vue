<template>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Tambah Pengguna</h3>
            </div>
            <div class="panel-body">
                <person-form ref="formCourier"></person-form>
                <div class="form-group">
                    <router-link :to="{name: 'persons.data'}" class="btn btn-primary btn-sm btn-flat">
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
    import FormPerson from "./Form";
    import { mapActions } from 'vuex';

    export default {
        name: "AddPerson",
        methods: {
            ...mapActions('person', ['submitPerson']),
            submit() {
                this.submitPerson().then(() => {
                    this.$swal.fire(
                        'Success!',
                        'Data Berhasil Disimpan.',
                        'success'
                    );
                    this.$router.push({ name: 'persons.data'})
                })
            }
        },
        components: {
            'person-form': FormPerson
        }
    }
</script>
