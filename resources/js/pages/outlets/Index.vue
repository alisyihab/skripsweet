<template>
<div>
    <div class="main-content" v-if="$can('read users')">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <router-link :to="{ name: 'outlets.data' }" class="btn btn-icon" v-show="$route.name !== 'outlets.data' ">
                        <i class="fas fa-arrow-left"></i>
                    </router-link>
                </div>
                <h1>{{ $route.meta.title }}</h1>
                <div class="section-header-button">
                    <router-link :to="{ name: 'outlets.add' }" class="btn btn-primary" v-show="$route.name !== 'outlets.add' ">
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
        </section>

        <section class="section-body">
            <router-view></router-view>
        </section>
    </div>

    <div class="main-content" v-if="!$can('read users')">
        <page404 />
    </div>
</div>
</template>

<script>
    import Page404 from '../../components/Page404';

    export default {
        name: 'IndexOutlet',
        components: {
            'page404': Page404
        }
    }
</script>
