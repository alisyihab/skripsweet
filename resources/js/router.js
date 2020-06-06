import Vue from "vue";
import Router from "vue-router";
import Home from "./pages/Home";
import Login from "./pages/Login";
import store from "./store";
import ForgotPassword from "./pages/ForgotPassword";
import ResetPasswordForm from './pages/ResetPasswordForm';

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
import Income from "./pages/expenses/Income";

import IndexCustomer from "./pages/customers/Index";
import DataCustomer from "./pages/customers/Customer";
import AddCustomer from "./pages/customers/Add";
import EditCustomer from "./pages/customers/Edit";

import IndexTransaction from "./pages/transaction/Index";
import AddTransaction from "./pages/transaction/Add";
import InvoiceTrasaction from "./pages/transaction/Invoice";
import ListTransaction from "./pages/transaction/List";
import ViewTransaction from "./pages/transaction/View";

import IndexUsers from "./pages/users/Index";
import DataPersons from "./pages/users/Users";
import AddPerson from "./pages/users/Add";
import EditPerson from "./pages/users/Edit";

import IndexNotification from "./pages/notification/Index";
import DataNotification from "./pages/notification/Notification";

import Page404 from "./pages/Page404";

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '*',
            component: Page404,
        },
        {
            path: '/',
            redirect: '/login'
        },
        {
            path: '/reset-password',
            name: 'reset-password',
            component: ForgotPassword,
        },
        {
            path: '/reset-password/:token', 
            name: 'reset-password-form', 
            component: ResetPasswordForm,
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/dashboard',
            name: 'home',
            component: Home,
            meta: { requiresAuth: true }
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
                {
                    path: 'income',
                    name: 'income',
                    component: Income,
                    meta: { title: 'Pendapatan Bersih' }
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
                    path: 'invoice/:id',
                    name: 'transactions.invoice',
                    component: InvoiceTrasaction,
                    meta: { title: 'Invoice' }
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
        {
            path: '/notification',
            component: IndexNotification,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'notifications.data',
                    component: DataNotification,
                    meta: { title: 'Daftar Notification' }
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
