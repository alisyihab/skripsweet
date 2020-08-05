<template>
    <div>
        <div class="form-group" :class="{ 'is-invalid': errors.name }">
            <label>Nama Lengkap</label>
            <input type="text" placeholder="Nama" class="form-control" v-model="persons.name">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'is-invalid': errors.email }">
            <label>Email</label>
            <input type="email" placeholder="Email" class="form-control" v-model="persons.email">
            <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'is-invalid': errors.password }">
            <label>Passowrd</label>
            <input type="password" placeholder="Password" class="form-control" v-model="persons.password">
            <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
            <p class="text-warning" v-show="$route.name != 'persons.add'">
                Biarkan kosong bila tidak ingin mengganti passsword
            </p>
        </div>
        <div class="form-group" :class="{ 'is-invalid': errors.role }">
            <label>Role</label>
            <select v-model="persons.role" class="custom-select">
                <option disabled value="">Pilih</option>
                <option value="0">Super Admin</option>
                <option value="1">Admin</option>
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