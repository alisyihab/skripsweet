<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.code }">
            <label>Kode Outlet</label>
            <input type="text" class="form-control" v-model="outlet.code" :readonly="$route.name == 'outlets.edit'">
            <p class="text-danger" v-if="errors.code">{{ errors.code[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label>Nama Outlet</label>
            <input type="text" class="form-control" v-model="outlet.name">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.address }">
            <label>Alamat</label>
            <textarea cols="5" rows="5" class="form-control" v-model="outlet.address"></textarea>
            <p class="text-danger" v-if="errors.address">{{ errors.address[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.phone }">
            <label>No Telp</label>
            <the-mask
                    type="tel"
                    :mask="['####-####-####']"
                    class="form-control"
                    v-model="outlet.phone" />
            <p class="text-danger" v-if="errors.phone">{{ errors.phone[0] }}</p>
        </div>
        <div class="form-group">
            <input type="checkbox" v-model="outlet.status">
            <label> Set Active</label>
        </div>
    </div>
</template>

<script>
    import {mapState, mapMutations} from 'vuex'
    import {TheMask} from 'vue-the-mask'

    export default {
        name: 'FormOutlets',
        computed: {
            ...mapState(['errors']),
            ...mapState('outlet', {
                outlet: state => state.outlet
            })
        },
        methods: {
            ...mapMutations('outlet', ['CLEAR_FORM']),
        },
        destroyed() {
            this.CLEAR_FORM()
        },
        components: {
            TheMask
        }
    }
</script>
