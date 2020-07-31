<template>
        <div class="card">
            <div class="card-header">
                <h4>Transaksi</h4>
            </div>
            <div class="card-body">
                <transaction-form ref="form"></transaction-form>
                <div class="form-group">
                    <button class="btn btn-outline-primary btn-sm" @click.prevent="submit">
                        <i class="fa fa-save"></i> Buat Transaksi
                    </button>
                    <button class="btn btn-danger btn-sm btn-flat" @click.prevent="clearForm">
                        Clear Form
                    </button>
                </div>
            </div>
        </div>
</template>

<script>
    import { mapActions, mapState, mapMutations } from 'vuex'
    import FormTransaction from './Form.vue'
    export default {
        name: 'AddTransaction',
        methods: {
            submit() {
                this.$refs.form.submit()
            },
            clearForm() {
                this.$refs.form.resetForm()
            }
        },
        components: {
            'transaction-form': FormTransaction
        }
    }
</script>
