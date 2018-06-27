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

Route::get('/auth0/callback', '\Auth0\Login\Auth0Controller@callback')->name('auth0-callback');

Route::get('/login', 'Auth\Auth0IndexController@login')->name('login');
Route::post('/logout', 'Auth\Auth0IndexController@logout')->name('logout')->middleware('auth');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/_/donations', 'DonationsController@index');
    Route::get('/manage/{vueCapture?}', [\App\Http\Controllers\ManageController::class, 'vueHandler'])->where('vueCapture', '.*');
});
