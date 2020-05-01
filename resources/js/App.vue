<template>
    <div id="app">
        <div class="main-wrapper">
            <app-header v-if="isAuth" />
            <div class="main-sidebar" v-if="isAuth">
                <app-menu />
            </div>
            <router-view></router-view>
            <app-footer v-if="isAuth" />
        </div>
    </div>
</template>
<script>
    import {mapState, mapGetters} from 'vuex'
    import Header from './components/Header.vue'
    import Footer from './components/Footer.vue'
    import Menu from  './components/Menu';

    export default {
        computed: {
            ...mapState(['token']),
            ...mapGetters(['isAuth'])
        },
        components: {
            'app-header': Header,
            'app-footer': Footer,
            'app-menu': Menu
        }
    }
</script>
