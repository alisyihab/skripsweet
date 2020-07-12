<template>
<div>
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                 <div class="section-header-back">
                    <router-link :to="{ name: 'transactions.list' }" class="btn btn-icon" v-show="$route.name !== 'transactions.list' ">
                        <i class="fas fa-arrow-left"></i>
                    </router-link>
                </div>
                <h1>{{ $route.meta.title }}</h1>
                <div class="section-header-button">
                    <router-link :to="{ name: 'transactions.add' }"
                        class="btn btn-primary"
                        v-if="$can('create transaction')"
                        v-show="$route.name == 'transactions.list'">
                        Tambah Data
                    </router-link>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <router-link :to="{ name: 'home' }"><i class="fa fa-dashboard"></i> Home</router-link>
                    </div>
                    <div class="breadcrumb-item active">{{ $route.meta.title }}</div>
                </div>
            </div>
            <div class="section-body">
                <router-view></router-view>
            </div>
        </section>

    </div>
</div>
</template>

<script>
    import Page404 from '../../components/Page404';

    export default {
        name: "IndexTransaction",
        components: {
            'page404': Page404
        },
        watch: {
            $route: {
                immediate: true,
                handler(to, from) {
                    document.title = to.meta.title;
                }
            }
        },
    }
</script>
