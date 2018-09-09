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

Route::get('/', 'IndexController@index');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/product/{id}', 'ProductController@show')->name('product.show');

Route::group(['prefix' => '/member', 'middleware' => ['auth']], function () {
    Route::get('/change_password', 'MemberController@showChangePasswordPage')->name('member.change_password');
    Route::post('/change_password', 'MemberController@changePasswordHandler');

    Route::get('/balance', 'MemberController@showBalance')->name('member.balance');

    Route::get('/products', 'MemberController@products')->name('member.products');
    Route::get('/products/create', 'ProductController@create')->name('product.create');
    Route::post('/products/create', 'ProductController@store');

    Route::get('/rental/product/{id}', 'RentalController@showApplyPage')->name('rental.apply');
    Route::post('/rental/product/{id}', 'RentalController@applyHandler');
});