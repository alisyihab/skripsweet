<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', 'Auth\LoginController@login');
Route::post('reset-password', 'Auth\ForgotPasswordController@sendPasswordResetLink');
Route::post('reset/password', 'Auth\ResetPasswordController@callResetPassword');

Route::group(['middleware' => 'auth:api'], function () {
    // outlet
    Route::resource('/outlets', 'API\OutletController')->except(['show']);

    // users management
    Route::resource('/users', 'API\UserController')->except(['create', 'show']);

    // product
    Route::resource('product', 'API\ProductController')->except(['create', 'show']);
    Route::get('/product/laundry-type', 'API\ProductController@getLaundryType');
    Route::post('/product/laundry-type', 'API\ProductController@storeLaundryType');

    // role permission
    Route::get('roles', 'API\RolePermissionController@getAllRole')->name('roles');
    Route::get('permissions', 'API\RolePermissionController@getAllPermission')->name('permission');
    Route::post('role-permission', 'API\RolePermissionController@getRolePermission')->name('role_permission');
    Route::post('set-role-permission', 'API\RolePermissionController@setRolePermission')->name('set_role_permission');
    Route::post('set-role-user', 'API\RolePermissionController@setRoleUser')->name('user.set_role');
    Route::get('user-authenticated', 'API\UserController@getUserLogin')->name('user.authenticated');
    Route::get('user-lists', 'API\UserController@userLists')->name('user.index');

    // expenses
    Route::resource('expenses', 'API\ExpensesController')->except(['create', 'show']);
    Route::post('expenses/accept', 'API\ExpensesController@accept')->name('expenses.accept');
    Route::post('expenses/cancel', 'API\ExpensesController@cancleRequest')->name('expenses.cancel');
    
    //notification
    Route::resource('notification', 'API\NotificationController')->except(['create', 'destroy']);
    Route::get('list-notif', 'API\NotificationController@notificationList');

    // Customer
    Route::resource('/customer', 'API\CustomerController')->except(['create', 'show', 'update']);
    Route::post('/customer/{id}', 'API\CustomerController@update')->name('customers.update');

    // transactions
    Route::resource('transaction', 'API\TransactionController')->except(['create', 'show']);
    Route::post('transaction/complete-item', 'API\TransactionController@completeItem');
    Route::post('transaction/payment', 'API\TransactionController@makePayment');
    Route::post('transaction/accept', 'API\TransactionController@accept')->name('payment.accept');

    // dashboard
    Route::get('chart', 'API\DashboardController@chart');
    Route::get('export', 'API\DashboardController@exportData');
    Route::get('data', 'API\DashboardController@data');
    Route::get('expanse', 'API\DashboardController@getExpanse');
    Route::get('last-trans', 'API\DashboardController@getTransaction');
    Route::get('getExpenseDashboard', 'API\DashboardController@getExpenseDashboard');

    // profile Customer
    Route::get('total_transaction', 'API\DashboardController@countTrans');

    // finace
    Route::get('finace', 'API\FinaceController@chart');
    Route::get('export-finace', 'API\FinaceController@exportData');
});
