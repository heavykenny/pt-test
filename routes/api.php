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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('user-register', 'API\UserController@userRegistration');
Route::post('user-login', 'API\UserController@userLogin');

Route::group(['prefix' => 'user', 'middleware' => ['jwt.verify']], function () {
    Route::get('get-user-hobby', 'API\HobbyController@getAllUserHobby');
    Route::post('user-add-hobby', 'API\HobbyController@addHobby');
    Route::post('user-edit-hobby', 'API\HobbyController@editHobby');
    Route::delete('user-delete-hobby', 'API\HobbyController@deleteHobby');
});
