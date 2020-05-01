<template>
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ $route.meta.title }}</h1>
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
        name: 'IndexSetting',
    }
</script>