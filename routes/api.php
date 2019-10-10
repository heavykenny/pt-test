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
Route::get('get-user-details', 'API\UserController@getAuthenticatedUser');

Route::group(['prefix' => 'user', 'middleware' => ['jwt.verify']], function () {
    Route::get('get-user-hobby', 'API\HobbyController@getAllUserHobby');
    Route::get('get-hobby-details', 'API\HobbyController@getHobbyDetails');
    Route::post('user-add-hobby', 'API\HobbyController@addHobby');
    Route::post('user-edit-hobby', 'API\HobbyController@editHobby');
    Route::post('user-delete-hobby', 'API\HobbyController@deleteHobby');
});
