import Vue from 'vue'
import Router from 'vue-router'
import Home from './pages/Home.vue'
import Login from './pages/Login.vue'
import store from './store.js'

import IndexOutlet from './pages/outlets/Index.vue'
import DataOutlet from './pages/outlets/Outlet.vue'
import AddOutlet from './pages/outlets/Add.vue'
import EditOutlet from './pages/outlets/Edit.vue'

import IndexCourier from './pages/couriers/Index'
import DataCourier from './pages/couriers/Courier'
import AddCourier from './pages/couriers/Add'
import EditCourier from './pages/couriers/Edit'

import IndexProduct from './pages/products/Index.vue'
import DataProduct from './pages/products/Product.vue'
import AddProduct from './pages/products/Add.vue'
import EditProduct from './pages/products/Edit.vue'

import SetPermission from "./pages/setting/roles/SetPermission.vue";
import Setting from "./pages/setting/Index.vue"

import IndexExpenses from "./pages/expenses/Index"
import DataExpenses from "./pages/expenses/Expenses"
import CreateExpenses from "./pages/expenses/Add"
import ViewExpenses from  "./pages/expenses/View"
import EditExpenses from "./pages/expenses/Edit"

import IndexCustomer from './pages/customers/Index'
import DataCustomer from './pages/customers/Customer'
import AddCustomer from './pages/customers/Add'
import EditCustomer from './pages/customers/Edit'

import IndexTransaction from './pages/transaction/Index'
import AddTransaction from './pages/transaction/Add'
import ViewTransaction from './pages/transaction/View'
import ListTransaction from './pages/transaction/List'

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
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
                    meta: { title: 'Manage Outlets'}
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
            path: '/couriers',
            component: IndexCourier,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'couriers.data',
                    component: DataCourier,
                    meta: { title: 'Manage Kurir' }
                },
                {
                    path: 'add',
                    name: 'couriers.add',
                    component: AddCourier,
                    meta: { title: 'Tambah Kurir' }
                },
                {
                    path: 'edit/:id',
                    name: 'couriers.edit',
                    component: EditCourier,
                    meta: { title: 'Edit Kurir' }
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
                    meta: {title: 'Manage Produk Laundry'}
                },
                {
                    path: 'add',
                    name: 'products.add',
                    component: AddProduct,
                    meta: { title: 'Tambah Produk Laundry'}
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
                    meta: {title: 'Manage Biaya'}
                },
                {
                    path: 'add',
                    name: 'expenses.create',
                    component: CreateExpenses,
                    meta: {title: 'Tambah Data Biaya'}
                },
                {
                    path: 'edit/:id',
                    name: 'expenses.edit',
                    component: EditExpenses,
                    meta: {title: 'Edit Expenses'}
                },
                {
                    path: 'view/:id',
                    name: 'expenses.view',
                    component: ViewExpenses,
                    meta: {title: 'View Expenses'}
                },
            ]
        },
        {
            path: '/customers',
            component: IndexCustomer,
            meta: {requiresAuth: true},
            children: [
                {
                    path: '',
                    name: 'customers.data',
                    component: DataCustomer,
                    meta: {title: 'Manage Customers'}
                },
                {
                    path: 'add',
                    name: 'customers.add',
                    component: AddCustomer,
                    meta: {title: 'Tambah Customer'}
                },
                {
                    path: 'edit/:id',
                    name: 'customers.edit',
                    component: EditCustomer,
                    meta: { title: 'Edit Customer' }
                },
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
                    meta: {title: 'Buat Transaksi'}
                },
                {
                    path: 'view/:id',
                    name: 'transactions.view',
                    component: ViewTransaction,
                    meta: {title: 'View Transaction'}
                },
                {
                    path: 'list',
                    name: 'transactions.list',
                    component: ListTransaction,
                    meta: { title: 'List Transaction' }
                },
            ]
        }
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
