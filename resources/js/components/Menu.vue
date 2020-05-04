<template>
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <router-link to="/">Laundry</router-link>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <router-link to="/">LD</router-link>
        </div>
        <ul class="sidebar-menu">
            <li>
                <router-link class="nav-link active" to="/">
                    <i class="fas fa-laptop"></i>
                    <span>Dashboard</span>
                </router-link>
            </li>
            <!-- <li v-if="$can('read outlets')">
                <router-link class="nav-link" :to="{ name: 'outlets.data'}">
                    <i class="fas fa-store"></i>
                    <span>Outlet</span>
                </router-link>
            </li> -->
            <li v-if="$can('read products')">
                <router-link class="nav-link active" :to="{ name: 'products.data' }">
                    <i class="fas fa-cube"></i>
                    <span>Produk</span>
                </router-link>
            </li>
            <li>
                <router-link class="nav-link active" :to="{ name: 'expenses.data' }">
                    <i class="fas fa-dollar-sign"></i>
                    <span>Keuangan</span>
                </router-link>
            </li>
            <li>
                <router-link class="nav-link active" :to="{ name: 'customers.data' }">
                    <i class="fas fa-users"></i>
                    <span>Pelanggan</span>
                </router-link>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Trasaksi</span>
                </a>
                <ul class="dropdown-menu">
                    <li><router-link class="nav-link" :to="{ name: 'transactions.list' }">List Transaksi</router-link></li>
                    <li><router-link class="nav-link" :to="{name: 'transactions.add'}">Buat Transaksi</router-link></li>
                </ul>
            </li>
            <li v-if="authenticated.role == 0">
                <router-link class="nav-link active" :to="{ name: 'persons.data' }">
                    <i class="fas fa-user"></i>
                    <span>Manage User</span>
                </router-link>
            </li>
            <li v-if="authenticated.role == 0"> 
                <router-link class="nav-link" :to="{name: 'role.permissions'}">
                    <i class="fas fa-cog"></i>
                    <span>Hak Akses</span>
                </router-link>
            </li>   
        </ul>
    </aside>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        computed: {
            ...mapState('user', {
                authenticated: state => state.authenticated
            }),
        }
    }
</script>
