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
Route::post('login', 'ApiController\ApiLoginController@login');


Route::group(['middleware' => ['auth:api']], function () {
    
    // Url Users model 
    Route::get('user/profile', 'ApiController\ApiUsersController@getProfilUser');
    Route::get('users', 'ApiController\ApiUsersController@getUsers');
    Route::post('users/add', 'ApiController\ApiUsersController@addUsers');
    Route::get('users/edit/{id}', 'ApiController\ApiUsersController@editUser');
    Route::get('users/delete/{id}', 'ApiController\ApiUsersController@hapusUser');

    // Bonus Model
    Route::get('bonus', 'ApiController\ApiBonusController@getBonus');
    Route::post('bonus/add', 'ApiController\ApiBonusController@postBonus');
    Route::get('bonus/claim/{id}', 'ApiController\ApiBonusController@claimBonus');

    //payment
    Route::get('payment', 'ApiController\ApiPaymentController@getPayments');


});