<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label>Nama Lengkap</label>
            <input type="text" class="form-control" v-model="persons.name">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.email }">
            <label>Email</label>
            <input type="email" class="form-control" v-model="persons.email">
            <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.password }">
            <label>Passowrd</label>
            <input type="password" class="form-control" v-model="persons.password">
            <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
            <p class="text-warning" v-show="$route.name != 'persons.add'">
                Biarkan kosong bila tidak ingin mengganti passsword
            </p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.role }">
            <label>Role</label>
            <select class="form-control" v-model="persons.role">
                <option value="">Pilih</option>
                <option value="0">Super Admin</option>
                <option value="1">Admin</option>
                <option value="2">Keuangan</option>
            </select>
            <p class="text-danger" v-if="errors.role">{{ errors.role[0] }}</p>
        </div>
    </div>
</template>>

<script>
    import { mapState, mapMutations } from 'vuex'

    export default {
        name: 'FormPerson',
        computed: {
            ...mapState(['errors']),
            ...mapState('person', {
                persons: state => state.persons
            })
        },
        methods: {
            ...mapMutations('person', ['CLEAR_FORM'])
        },
        destroyed() {
            this.CLEAR_FORM()
        }
    }
</script>