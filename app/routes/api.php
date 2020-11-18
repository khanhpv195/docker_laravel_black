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
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'middleware' => ['auth:api','cors'],
], function () {

    Route::resource('customer', 'Customer\CustomerController');
    Route::resource('room', 'Room\RoomController')->middleware('auth:api');
    Route::resource('folio', 'Folio\FolioController');
    Route::resource('room_type', 'Room\RoomTypeController');
    Route::resource('rate_code', 'Rate\RateCodeController')->middleware('auth:api');
    Route::resource('user', 'User\UserController')->middleware('auth:api');
    Route::put('change-password/{id}', 'User\UserController@changePass')->middleware('auth:api');
    Route::get('/get_room_type/{id}', 'Room\RoomController@showRoomByType')->middleware('auth:api');
    Route::post('/add_customer_folio', 'Folio\FolioController@updateCustomerToFolio')->middleware('auth:api');
    Route::get('/search-customer-by-folio/{id}', 'Customer\CustomerController@searchByFolio')->name('searchByFolio');
    Route::post('/search-customer-by-name', 'Customer\CustomerController@searchByName')->name('searchByName');
    Route::resource('service', 'Service\ServiceController');
});

