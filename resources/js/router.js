import Vue from "vue";
import Router from "vue-router";
import Home from "./pages/Home.vue";
import Login from "./pages/Login.vue";
import store from "./store.js";

import IndexOutlet from "./pages/outlets/Index";
import DataOutlet from "./pages/outlets/Outlet";
import AddOutlet from "./pages/outlets/Add";
import EditOutlet from "./pages/outlets/Edit";

import IndexProduct from './pages/products/Index'
import DataProduct from './pages/products/Product'
import AddProduct from "./pages/products/Add";
import EditProduct from "./pages/products/Edit";

import SetPermission from "./pages/setting/roles/SetPermission.vue";
import Setting from "./pages/setting/Index.vue";

import IndexExpenses from "./pages/expenses/Index";
import DataExpenses from "./pages/expenses/Expenses";
import CreateExpenses from "./pages/expenses/Add";
import ViewExpenses from "./pages/expenses/View";
import EditExpenses from "./pages/expenses/Edit";
import ExportExpanses from "./pages/expenses/Export";

import IndexCustomer from "./pages/customers/Index";
import DataCustomer from "./pages/customers/Customer";
import AddCustomer from "./pages/customers/Add";
import EditCustomer from "./pages/customers/Edit";

import IndexTransaction from "./pages/transaction/Index";
import AddTransaction from "./pages/transaction/Add";
import ViewTransaction from "./pages/transaction/View";
import ListTransaction from "./pages/transaction/List";

import IndexUsers from "./pages/users/Index";
import DataPersons from "./pages/users/Users";
import AddPerson from "./pages/users/Add";
import EditPerson from "./pages/users/Edit";

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            redirect: '/login'
        },
        {
            path: '/dashboard',
            name: 'home',
            component: Home,
            meta: { requiresAuth: true }
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/outlets',
            component: IndexOutlet,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'outlets.data',
                    component: DataOutlet,
                    meta: { title: 'Manage Outlets' }
                },
                {
                    path: 'add',
                    name: 'outlets.add',
                    component: AddOutlet,
                    meta: { title: 'Add Outlets' }
                },
                {
                    path: 'edit/:id',
                    name: 'outlets.edit',
                    component: EditOutlet,
                    meta: { title: 'Edit Outlet' }
                }
            ]
        },
        {
            path: "/product",
            component: IndexProduct,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'products.data',
                    component: DataProduct,
                    meta: { title: 'Manage Produk Laundry' }
                },
                {
                    path: 'add',
                    name: 'products.add',
                    component: AddProduct,
                    meta: { title: 'Tambah Produk Laundry' }
                },
                {
                    path: 'edit/:id',
                    name: 'products.edit',
                    component: EditProduct,
                    meta: { title: 'Edit Produk Laundry' }
                }
            ]
        },
        {
            path: '/setting',
            component: Setting,
            meta: { requiresAuth: true },
            children: [
                {
                    path: 'role-permission',
                    name: 'role.permissions',
                    component: SetPermission,
                    meta: { title: 'Set Hak Akses' }
                },
            ]
        },
        {
            path: '/expanses',
            component: IndexExpenses,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'expenses.data',
                    component: DataExpenses,
                    meta: { title: 'Permintaan Biaya' }
                },
                {
                    path: 'add',
                    name: 'expenses.create',
                    component: CreateExpenses,
                    meta: { title: 'Tambah Data Biaya' }
                },
                {
                    path: 'edit/:id',
                    name: 'expenses.edit',
                    component: EditExpenses,
                    meta: { title: 'Edit Expenses' }
                },
                {
                    path: 'view/:id',
                    name: 'expenses.view',
                    component: ViewExpenses,
                    meta: { title: 'Lihat Permintaan' }
                },
            ]
        },
        {
            path: '/customers',
            component: IndexCustomer,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'customers.data',
                    component: DataCustomer,
                    meta: { title: 'Manage Pelanggan' }
                },
                {
                    path: 'add',
                    name: 'customers.add',
                    component: AddCustomer,
                    meta: { title: 'Buat Pelanggan Baru' }
                },
                {
                    path: 'edit/:id',
                    name: 'customers.edit',
                    component: EditCustomer,
                    meta: { title: 'Edit Customer' }
                }
            ]
        },
        {
            path: '/transactions',
            component: IndexTransaction,
            meta: { requiresAuth: true },
            children: [
                {
                    path: 'create',
                    name: 'transactions.add',
                    component: AddTransaction,
                    meta: { title: 'Buat Transaksi' }
                },
                {
                    path: 'view/:id',
                    name: 'transactions.view',
                    component: ViewTransaction,
                    meta: { title: 'Detail Transaksi' }
                },
                {
                    path: 'list',
                    name: 'transactions.list',
                    component: ListTransaction,
                    meta: { title: 'Daftar Transaksi' }
                },
            ]
        },
        {
            path: '/users',
            component: IndexUsers,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'persons.data',
                    component: DataPersons,
                    meta: { title: 'Daftar Pengguna' }
                },
                {
                    path: '/add',
                    name: 'persons.add',
                    component: AddPerson,
                    meta: { title: 'Tambah Pengguna' }
                },
                {
                    path: '/edit/:id',
                    name: 'persons.edit',
                    component: EditPerson,
                    meta: { title: 'Ubah Pengguna' }
                }
            ]
        },
    ]
});

router.beforeEach((to, from, next) => {
    store.commit('CLEAR_ERRORS');
    if (to.matched.some(record => record.meta.requiresAuth)) {
        let auth = store.getters.isAuth;
        if (!auth) {
            next({ name: 'login' })
        } else {
            next()
        }
    } else {
        next()
    }
});

export default router
