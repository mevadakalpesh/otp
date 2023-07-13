<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpVerificationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
  return view('welcome');
});

Auth::routes();

Route::middleware(['OtpVerify'])->group(function() {
  Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::middleware(['auth'])->group(function() {
  Route::get('give-otp', [OtpVerificationController::class, 'giveOtp'])->name('giveOtp');
  Route::post('submit-otp', [OtpVerificationController::class, 'submitOtp'])->name('submitOtp');
  Route::post('resend-otp', [OtpVerificationController::class, 'resendOtp'])->name('resendOtp');
});