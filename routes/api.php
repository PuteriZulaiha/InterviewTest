<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::middleware('guest')->group(function() {
	Route::prefix('auth')->group(function () {
		Route::post('login', 'AuthController@login')->name('auth.login');
	});
});

Route::middleware('auth:api')->group(function() {
	Route::get('listing', 'ListingController@showByEmployeeId');
});

