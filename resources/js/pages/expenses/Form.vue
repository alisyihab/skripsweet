<template>
    <div>
        <div class="form-group">
            <label>Permintaan</label>
            <input type="text" class="form-control" :class="{ 'is-invalid': errors.description }" v-model="expenses.description">
            <div class="invalid-feedback" v-if="errors.description">
                <i class="fa fa-exclamation-circle fa-fw"></i> 
                {{ errors.description[0] }} 
            </div>
        </div>
        <div class="form-group">
            <label>Biaya</label>
            <input type="number" class="form-control" :class="{ 'is-invalid': errors.price }" v-model="expenses.price">
             <div class="invalid-feedback" v-if="errors.price">
                <i class="fa fa-exclamation-circle fa-fw"></i> 
                {{ errors.price[0] }} 
            </div>
        </div>
        <div class="form-group">
            <label>Catatan</label>
            <textarea cols="5" rows="5" class="form-control" :class="{ 'is-invalid': errors.note }" v-model="expenses.note"></textarea>
            <div class="invalid-feedback" v-if="errors.note">
                <i class="fa fa-exclamation-circle fa-fw"></i> 
                {{ errors.note[0] }} 
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState, mapActions} from 'vuex'

    export default {
        name: 'FormExpenses',
        created() {
            if (this.$route.name === 'expenses.edit') {
                this.editExpenses(this.$route.params.id).then((res) => {
                    this.expenses = {
                        description: res.data.description,
                        price: res.data.price,
                        note: res.data.note
                    }
                })
            }
        },
        data() {
            return {
                expenses: {
                    description: '',
                    price: '',
                    note: ''
                }
            }
        },
        computed: {
            ...mapState(['errors'])
        },
        methods: {
            ...mapActions('expenses', ['submitExpense', 'editExpenses', 'updateExpenses']),
            submit() {
                if (this.$route.name === 'expenses.edit') {
                    let data = Object.assign({id: this.$route.params.id}, this.expenses)
                    this.updateExpenses(data).then(() => {
                        this.$swal.fire(
                        'Success!',
                        'Data Berhasil Diubah!.',
                        'success'
                    );
                        this.$router.push({name: 'expenses.data'})
                    })
                } else {
                    this.submitExpense(this.expenses).then(() => {
                        this.$swal.fire(
                        'Success!',
                        'Data Berhasil Disimpan.',
                        'success'
                        );
                        this.$router.push({name: 'expenses.data'})
                    })
                }
            }
        },
        destroyed() {
            this.expenses = {
                description: '',
                price: '',
                note: ''
            }
        }
    }
</script>
