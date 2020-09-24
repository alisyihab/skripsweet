<template>
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h4>Edit Data</h4>
      </div>
      <div class="card-body">
        <person-form ref="formPerson"></person-form>
        <div class="form-group">
          <router-link :to="{ name: 'persons.data' }" class="btn btn-outline-secondary">
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
  import { mapActions } from 'vuex'
  import FormPerson from './Form.vue'

  export default {
    name: 'EditPerson',
    created() {
      this.editPerson(this.$route.params.id)
    },
    methods: {
      ...mapActions('person', ['editPerson', 'updatePerson']),
      submit() {
        this.updatePerson(this.$route.params.id).then(() => {
          this.$swal.fire(
            'Success!',
            'Data Berhasil Disimpan.',
            'success'
          );
          this.$router.push({ name: 'persons.data' })
        })
      }
    },
    components: {
      'person-form': FormPerson
    },
  }
</script>