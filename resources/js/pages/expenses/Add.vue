<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Buat Permohonan</h4>
            </div>
            <div class="card-body">
                <expenses-form ref="formExpense"></expenses-form>
                <div class="form-group">
                    <router-link :to="{ name: 'expenses.data' }" class="btn btn-outline-secondary">
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
    import FormExpenses from './Form.vue'

    export default {
        name: 'AddExpenses',
        methods: {
            submit() {
                this.$refs.formExpense.submit()
            }
        },
        components: {
            'expenses-form': FormExpenses
        }
    }
</script>