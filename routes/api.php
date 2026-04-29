<?php

use App\Http\Controllers\API\App\v1\AppOrderController;
use App\Http\Controllers\API\App\v1\CartController;
use App\Http\Controllers\API\App\v1\MenuController;
use App\Http\Controllers\API\App\v1\SeatController;
use App\Http\Controllers\API\App\v1\TableController;
use App\Http\Controllers\API\App\V1\BillController;
use App\Http\Controllers\API\App\V1\NotificationController;
use App\Http\Controllers\API\Cashier\CashierController;
use App\Http\Controllers\API\Cashier\CashierDiscountController;
use App\Http\Controllers\API\Cashier\CashierPaymentController;
use App\Http\Controllers\API\Customer\CustomerController;
use App\Http\Controllers\API\Customer\CustomerOrderController;
use App\Http\Controllers\API\Manager\ManagerController;
use App\Http\Controllers\API\SocialiteController;
use App\Http\Controllers\API\user\AccountController;
use App\Http\Controllers\API\user\ProfileController;
use App\Http\Controllers\API\UserAuthController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(UserAuthController::class)->prefix('user')->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');

    // Resend Otp
    Route::post('resend-otp', 'resendOtp');

    // Forget Password
    Route::post('forget-password', 'forgetPassword');
    Route::post('verify-otp-password', 'verifyOtp');
    Route::post('reset-password', 'resetPassword');
    // Social login
    Route::post('/social/login', 'socialLogin');
});
Route::any('/callback', [SocialiteController::class, 'callback'])->name('socialite.callback');


Route::group(['prefix' => 'user', 'middleware' => 'jwt.verify'], function () {
    Route::get('me', [UserAuthController::class, 'me']);
    Route::post('logout', [UserAuthController::class, 'logout']);

    // Account routes
    Route::controller(AccountController::class)->prefix('account')->group(function () {
        Route::get('/', 'index');
        Route::post('/update', 'update');
        Route::post('/change-password', 'changePassword');
        Route::post('/delete', 'destroy');
    });
});
// Profile
Route::controller(ProfileController::class)->prefix('profile')->group(function () {
    Route::get('/', 'index');
    Route::post('/update', 'update');
});
