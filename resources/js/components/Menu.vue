<template>
<aside class="app-aside app-aside-expand-md app-aside-light">
    <div class="aside-content">
      <header class="aside-header d-block d-md-none">
        <button class="btn-account" type="button" data-toggle="collapse" data-target="#dropdown-aside">
            <span class="user-avatar user-avatar-lg">
                <img
                    v-if="authenticated.photo !== null"
                    alt="image"
                    :src="'/storage/users/' + authenticated.photo"
                />
            <img v-if="authenticated.photo == null" alt="image" src="https://via.placeholder.com/160" class="rounded-circle mr-1">
            </span> 
            <span class="account-icon">
                <span class="fa fa-caret-down fa-lg"></span>
            </span> 
            <span class="account-summary">
                <span class="account-name">{{ authenticated.name }}</span> 
            </span>
        </button>
            <div id="dropdown-aside" class="dropdown-aside collapse">
              <div class="pb-3">
                    <a class="dropdown-item" href="javascript:void(0)" @click="logout">
                        <span class="dropdown-icon oi oi-account-logout"></span>
                         Logout
                    </a>
              </div>
            </div>
          </header>

          <div class="aside-menu overflow-hidden">
            <nav id="stacked-menu" class="stacked-menu">
              <ul class="menu">
                <li :class="[currentPage.includes('dashboard') ? activeClass : '', 'menu-item']">
                  <router-link to="/dashboard" class="menu-link">
                    <span class="menu-icon fas fa-home"></span> 
                    <span class="menu-text">Dashboard</span>
                  </router-link>
                </li>
                <li :class="[currentPage.includes('product') ? activeClass : '', 'menu-item']" v-show="$can('read products')">
                  <router-link :to="{ name: 'products.data' }" class="menu-link">
                    <span class="menu-icon fas fa-cube"></span> 
                    <span class="menu-text">Produk</span>
                  </router-link>
                </li>
                <li :class="[currentPage.includes('transactions') ? activeClass : '', 'menu-item has-child']">
                  <a href="#" class="menu-link">
                    <span class="menu-icon fas fa-shopping-cart"></span> 
                    <span class="menu-text">Transaksi Laundry</span> 
                  </a>
                  <ul class="menu">
                    <li :class="[currentPage.includes('ransactions/list') ? activeClass : '', 'menu-item']">
                      <router-link :to="{ name: 'transactions.list' }" class="menu-link">List Laundry</router-link>
                    </li>
                    <li :class="[currentPage.includes('ransactions/create') ? activeClass : '', 'menu-item']" v-show="$can('create transaction')">
                      <router-link :to="{ name: 'transactions.add' }" class="menu-link">Buat Transaksi</router-link>
                    </li>
                  </ul>
                </li>
                <li :class="[currentPage.includes('expanses') ? activeClass : '', 'menu-item has-child']" 
                  v-show="$can('read expense')">
                  <a href="#" class="menu-link">
                    <span class="menu-icon fas fa-dollar-sign"></span> 
                    <span class="menu-text">Keuangan</span> 
                  </a>
                  <ul class="menu">
                    <li :class="[currentPage.includes('expanses') ? activeClass : '', 'menu-item']">
                      <router-link :to="{ name: 'expenses.data' }" class="menu-link">Daftar Permohonan</router-link>
                    </li>
                    <li :class="[currentPage.includes('expanses/income') ? activeClass : '', 'menu-item']">
                      <router-link :to="{ name: 'expenses.income' }" class="menu-link">Pendapatan</router-link>
                    </li>
                  </ul>
                </li>
                <li :class="[currentPage.includes('customers') ? activeClass : '', 'menu-item']" 
                  v-show="$can('read customer')">
                  <router-link :to="{ name: 'customers.data' }" class="menu-link">
                    <span class="menu-icon fas fa-users"></span> 
                    <span class="menu-text">Pelanggan</span>
                  </router-link>
                </li>
                <li :class="[currentPage.includes('users') ? activeClass : '', 'menu-item']" 
                  v-show="$can('read users')">
                  <router-link :to="{ name: 'persons.data' }" class="menu-link">
                    <span class="menu-icon fas fa-user"></span> 
                    <span class="menu-text">User</span>
                  </router-link>
                </li>
                <li :class="[currentPage.includes('setting') ? activeClass : '', 'menu-item']"  v-show="$can('read users')">
                  <router-link :to="{ name: 'role.permissions' }" class="menu-link">
                    <span class="menu-icon fas fa-user-cog"></span> 
                    <span class="menu-text">Role Permission</span>
                  </router-link>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </aside>
</template>

<script>
    import {mapState} from "vuex";

    export default {
      data() {
        return {
          activeClass: 'has-active',
        }
      },
      computed: {
          ...mapState('user', {
              authenticated: state => state.authenticated
          }),
          currentPage() {
            return this.$route.path;
          }
      },
      methods: {
        logout() {
            return new Promise((resolve, reject) => {
                localStorage.removeItem('token');
                resolve()
            }).then(() => {
                this.$store.state.token = localStorage.getItem('token');
                this.$router.push('/login')
            })
        },
      }
    }
</script>
