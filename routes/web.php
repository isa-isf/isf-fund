<?php

use App\Http\Controllers as C;

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

Route::get('/donations/{uuid}/checkout', [C\DonationsController::class, 'checkout']);
Route::post('/_/donations', [C\DonationsController::class, 'store']);
Route::post('/donations/{uuid}/first-callback', [C\Donations\CallbackController::class, 'first']);
Route::post('/donations/{uuid}/period-callback', [C\Donations\CallbackController::class, 'period']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/_/donations', [C\DonationsController::class, 'index']);
    Route::put('/_/donations/{id}/archive', [C\DonationsController::class, 'archive']);
    Route::get('/manage/{vueCapture?}', [C\ManageController::class, 'vueHandler'])->where('vueCapture', '.*');
});
