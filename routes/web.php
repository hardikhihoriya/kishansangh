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

Route::get('/', function () {
    if (auth()->id() > 0) {
        return redirect()->to('/home');
    }
    return view('auth.login');
});

// Login Routes...
Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', ['as' => 'login.post', 'uses' => 'Auth\LoginController@login']);
Route::any('logout', ['as' => 'logout', 'uses' => 'Admin\UserController@logout']);

// Registration Routes...
//Route::get('register', ['as' => 'register', 'uses' => 'Auth\RegisterController@showRegistrationForm']);
//Route::post('register', ['as' => 'register.post', 'uses' => 'Auth\RegisterController@register']);

// Password Reset Routes...
Route::get('password/reset', ['as' => 'password.reset', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
Route::post('password/email', ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
Route::get('password/reset/{token?}', ['as' => 'password.reset.token', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
Route::post('password/reset', ['as' => 'password.reset.post', 'uses' => 'Auth\ResetPasswordController@reset']);

Route::group([ 'middleware' => ['auth']], function () {
    
    Route::get('/home', 'HomeController@index')->name('home');

    // User Module
    Route::get('/admin/sanghusers', 'Admin\UserController@index');
    Route::post('/admin/sanghusers/list-ajax', 'Admin\UserController@listAjax');
    Route::any('/admin/sanghusers/new', 'Admin\UserController@create');
    Route::any('/admin/sanghusers/user-{id}', array('as' => 'user.edit', 'uses' => 'Admin\UserController@update'));
    Route::post('/admin/sanghusers/set', 'Admin\UserController@set');

    // Customer Module
    Route::get('/admin/customer', 'Admin\CustomerController@index');
    Route::post('/admin/customer/list-ajax', 'Admin\CustomerController@listAjax');
    Route::any('/admin/customer/new', 'Admin\CustomerController@create');
    Route::any('/admin/customer/customer-{id}', array('as' => 'customer.edit', 'uses' => 'Admin\CustomerController@update'));
    Route::post('/admin/customer/set', 'Admin\CustomerController@set');

    // Vendor Module
    Route::get('/admin/vendor', 'Admin\VendorController@index');
    Route::post('/admin/vendor/list-ajax', 'Admin\VendorController@listAjax');
    Route::any('/admin/vendor/new', 'Admin\VendorController@create');
    Route::any('/admin/vendor/vendor-{id}', array('as' => 'vendor.edit', 'uses' => 'Admin\VendorController@update'));
    Route::post('/admin/vendor/set', 'Admin\VendorController@set');

    // Shop Type Module
    Route::get('/admin/shop-type', 'Admin\ShopTypeController@index');
    Route::post('/admin/shop-type/list-ajax', 'Admin\ShopTypeController@listAjax');
    Route::any('/admin/shop-type/new', 'Admin\ShopTypeController@create');
    Route::any('/admin/shop-type/shop-type-{id}', array('as' => 'shop-type.edit', 'uses' => 'Admin\ShopTypeController@update'));
    Route::post('/admin/shop-type/set', 'Admin\ShopTypeController@set');

    // Shop Marketing Module
    Route::get('/admin/shop-marketing', 'Admin\ShopMarketingController@index');
    Route::post('/admin/shop-marketing/list-ajax', 'Admin\ShopMarketingController@listAjax');
    Route::any('/admin/shop-marketing/new', 'Admin\ShopMarketingController@create');
    Route::any('/admin/shop-marketing/shop-marketing-{id}', array('as' => 'shop-marketing.edit', 'uses' => 'Admin\ShopMarketingController@update'));
    Route::post('/admin/shop-marketing/set', 'Admin\ShopMarketingController@set');

    // Shop Package Module
    Route::get('/admin/shop-package', 'Admin\ShopPackageController@index');
    Route::post('/admin/shop-package/list-ajax', 'Admin\ShopPackageController@listAjax');
    Route::any('/admin/shop-package/new', 'Admin\ShopPackageController@create');
    Route::any('/admin/shop-package/shop-package-{id}', array('as' => 'shop-package.edit', 'uses' => 'Admin\ShopPackageController@update'));
    Route::post('/admin/shop-package/set', 'Admin\ShopPackageController@set');

    // Shop Module
    Route::get('/admin/shops', 'Admin\ShopController@index');
    Route::post('/admin/shops/list-ajax', 'Admin\ShopController@listAjax');
    Route::any('/admin/shops/new', 'Admin\ShopController@create');
    Route::any('/admin/shops/shops-{id}', array('as' => 'shop.edit', 'uses' => 'Admin\ShopController@update'));
    Route::post('/admin/shops/set', 'Admin\ShopController@set');
});