<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//put ar or en before dashboard/check
//mcamara package

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){
    Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function(){
        Route::get('/index', 'DashboardController@index');

        //categories route
        Route::resource('/categories', 'CategoryController')->except(['show']);
        //products route
        Route::resource('/products', 'ProductController')->except(['show']);
        //client routes
        Route::resource('/clients', 'ClientController')->except(['show']);
        //order client route
        Route::resource('clients.orders', 'Client\OrderController')->except(['show']);
        //order route
        Route::resource('/orders', 'OrderController')->except(['show']);
        //get orders product
        Route::get('/orders/{order}/products', 'OrderController@products')->name('orders.products');

        //users routes
        Route::resource('/users', 'UserController')->except(['show']);
    });
});


Route::get('/', function () {
    return view('dashboard.index');
});
//disappear register to make admin adding only
Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');


