<template>
    <div class="main-content">
        <section class="section" v-if="$can('create transaction')">
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
                        v-show="$route.name !== 'transactions.add' && $route.name !== 'transactions.view'">
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
        name: "IndexTransaction",
    }
</script>
