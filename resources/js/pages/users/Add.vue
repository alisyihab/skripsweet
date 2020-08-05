<template>
    <div class="card">
        <div class="card-header">
            <h6>Tambah Pengguna</h6>
        </div>
        <div class="card-body">
            <person-form ref="formCourier"></person-form>
            <div class="form-group">
                <router-link :to="{name: 'persons.data'}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-circle-left"></i> Kembali
                </router-link>
                <button class="btn btn-outline-primary" @click.prevent="submit">
                    <i class="fa fa-save"></i> Tambah Data
                </button>
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
