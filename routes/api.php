<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\UserController;
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

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware(['throttle:6,1']);
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware(['throttle:6,1'])
    ->name('password.reset');

Route::middleware(['auth:sanctum'])->prefix('auth')->group(function () {

    Route::get('/me', function (Request $request) {
        $user = $request->user()->loadCount('history');

        return $user;
    });

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['throttle:6,1']);

    Route::controller(AuthenticatedSessionController::class)->group(function () {
        Route::get('/logout', 'destroy');

    });

    Route::resource('users', UserController::class);
    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/{id}/restore', 'restore')->name('user.restore');
        Route::delete('/{id}/force', 'delete')->name('user.force.delete');
        Route::get('/all/trashed', 'trashedUsers')->name('user.trashed');
        Route::delete('/group/remove', 'usersGroupRemove')->name('user.group.delete');
        Route::post('/group/restore', 'usersGroupRestore')->name('user.group.restore');
        Route::delete('/group/delete', 'usersGroupDelete')->name('user.group.delete');
    });

});
