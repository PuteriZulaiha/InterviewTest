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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::middleware('action')->prefix('users')->group(function(){
    Route::get('/', 'UserController@index')->name('users');
    Route::post('/', 'UserController@store')->name('users');
    Route::get('/{user}', 'UserController@edit')->name('users.form');
    Route::put('/{user}', 'UserController@update')->name('users.form');
    Route::delete('/{user}', 'UserController@delete')->name('users.form');
});

Route::prefix('listings')->group(function(){
    Route::get('/', 'ListingController@index')->name('listings');
    Route::post('/', 'ListingController@store')->name('listings');
    Route::get('/{list}', 'ListingController@edit')->name('listings.form');
    Route::put('/{list}', 'ListingController@update')->name('listings.form');
    Route::delete('/{list}', 'ListingController@delete')->name('listings.form');
});
