<template>
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <router-link :to="{ name: 'couriers.data' }" class="btn btn-icon"
                                 v-show="$route.name !== 'couriers.data' ">
                        <i class="fas fa-arrow-left"></i>
                    </router-link>
                </div>
                <h1>{{ $route.meta.title }}</h1>
                <div class="section-header-button">
                    <router-link :to="{ name: 'couriers.add' }"
                                 class="btn btn-primary"
                                 v-show="$route.name !== 'couriers.add' && $route.name !== 'couriers.edit' ">
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
</template>

<script>
    export default {
        name: 'IndexCourier'
    }
</script>
