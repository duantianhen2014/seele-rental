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
    Route::get('/notifications', 'MemberController@showNotificationPage')->name('member.notification');

    Route::get('/change_password', 'MemberController@showChangePasswordPage')->name('member.change_password');
    Route::post('/change_password', 'MemberController@changePasswordHandler');

    Route::get('/balance', 'MemberController@showBalance')->name('member.balance');
    Route::get('/balance/withdraw', 'MemberController@showWithdrawPage')->name('member.withdraw');
    Route::post('/balance/withdraw', 'MemberController@withdrawHandler');

    Route::get('/products', 'MemberController@products')->name('member.products');
    Route::get('/products/create', 'ProductController@create')->name('product.create');
    Route::post('/products/create', 'ProductController@store');

    // A APPLY
    Route::get('/rental/product/{id}/apply', 'RentalController@showApplyPage')->name('rental.apply');
    Route::post('/rental/product/{id}/apply', 'RentalController@applyHandler');

    // B CONFIRM
    Route::get('/rental/product/{id}/b_confirm', 'RentalController@showBConfirmPage')->name('rental.b_confirm');
    Route::post('/rental/product/{id}/b_confirm', 'RentalController@bConfirmHandler');

    // A CONFIRM
    Route::get('/rental/product/{id}/a_confirm', 'RentalController@showAConfirmPage')->name('rental.a_confirm');
    Route::post('/rental/product/{id}/a_confirm', 'RentalController@aConfirmHandler');

    // A COMPLETE
    Route::get('/rental/product/{id}/a_complete', 'RentalController@showACompletePage')->name('rental.a_complete');
    Route::post('/rental/product/{id}/a_complete', 'RentalController@aCompleteHandler');

    // B COMPLETE
    Route::get('/rental/product/{id}/b_complete', 'RentalController@showBCompletePage')->name('rental.b_complete');
    Route::post('/rental/product/{id}/b_complete', 'RentalController@bCompleteHandler');

    Route::get('/rentals', 'MemberController@rentals')->name('member.rentals');
    Route::get('/join_rentals', 'MemberController@joinRentals')->name('member.join_rentals');

    Route::get('/rental/{id}', 'MemberController@show')->name('rental.show');
});