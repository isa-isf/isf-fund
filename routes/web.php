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
    return view('saisenbako');
});

Route::get('/donations/{uuid}/checkout', 'DonationsController@checkout');
Route::post('/_/donations', 'DonationsController@store');
Route::post('/donations/{uuid}/first-callback', 'Donations\CallbackController@first');
Route::post('/donations/{uuid}/period-callback', 'Donations\CallbackController@period');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/_/donations', 'DonationsController@index');
    Route::put('/_/donations/{id}/archive', 'DonationsController@archive');
    Route::get('/manage/{vueCapture?}', [\App\Http\Controllers\ManageController::class, 'vueHandler'])->where('vueCapture', '.*');
});
