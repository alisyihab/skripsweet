<template>
    <div id="app">
        <div class="main-wrapper">
            <app-header v-show="isAuth == true" />
            <div class="main-sidebar" v-show="isAuth == true">
                <app-menu />
            </div>
            <router-view></router-view>
            <app-footer v-show="isAuth == true" />
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
