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

    $seele = new \App\Seele\Seele;
    $seele->getBalance(new \App\Seele\User(
        '0x12393d2bd0eb32be084ed0b51971e6f8b072a891',
        '0x8b28b18526a78f6bd2f269ca1c0c86bb1f302183bcf42f064e53f71676db20a2'
    ));

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
