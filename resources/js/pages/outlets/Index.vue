<template>
    <div class="main-content">
        <section class="section">
            <breadcrumb></breadcrumb>
        </section>

        <section class="section-body">
            <router-view></router-view>
        </section>
    </div>
</template>
<script>
    import Breadcrumb from '../../components/Breadcrumb.vue'

    export default {
        name: 'IndexOutlet',
        components: {
            'breadcrumb': Breadcrumb
        }
    }
</script>
