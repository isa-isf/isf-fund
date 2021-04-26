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

Route::get('/', [C\FrontpageController::class, '__invoke']);

Route::get('/donations/{uuid}/checkout', [C\DonationsController::class, 'checkout']);
Route::post('/_/donations', [C\DonationsController::class, 'store']);
Route::post('/donations/{uuid}/payment-info', [C\Donations\CallbackController::class, 'paymentInfo']);
Route::post('/donations/{uuid}/first-callback', [C\Donations\CallbackController::class, 'first']);
Route::post('/donations/{uuid}/period-callback', [C\Donations\CallbackController::class, 'period']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/manage', [C\ManageController::class, 'index']);
    Route::get('/manage/income-report', [C\Manage\IncomeReportController::class, '__invoke']);
    Route::get('/manage/address', [C\Manage\AddressController::class, '__invoke']);
    Route::get('/manage/donations', [C\DonationsController::class, 'index']);
    Route::get('/manage/donations/{donation}', [C\DonationsController::class, 'show']);
});

Auth::routes([
    'register' => false,
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

Route::get('/challenge', [C\Auth\ChallengeController::class, 'form'])->name('challenge');
Route::post('/challenge', [C\Auth\ChallengeController::class, 'confirm'])->name('challenge');

Route::group(['middleware' => ['signed']], static function () {
    Route::get('/users/{user}/password-setup', [C\Auth\PasswordSetupController::class, 'form'])->name('password-setup');
    Route::post('/users/{user}/password', [C\Auth\PasswordSetupController::class, 'store'])->name('password-setup-store');
});
