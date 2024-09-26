<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
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
Route::get('/email/verify', function () {
    return view('verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/login'); // or wherever you want to redirect after verification
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/',[LoginController::class, 'index'])->name('login');
Route::get('login',[LoginController::class, 'index'])->name('login');

Route::get('registration', [LoginController::class,'registration'])->name('registration');

Route::post('validate_registration', [LoginController::class, 'validate_registration'])->name('sample.validate_registration');

Route::post('validate_login', [LoginController::class,'validate_login'])->name('sample.validate_login')->middleware('throttle:5,1');

Route::middleware(['auth', 'verified'])->group(function(){

    Route::get('logout', [LoginController::class,'logout'])->name('logout');

    Route::middleware(['checkRole:Administrator'])->group(function() {
        Route::get('administrator/dashboard', [LoginController::class, 'administratorDashboard'])->name('administrator.dashboard');

        Route::get('/userList', [LoginController::class, 'userList'])->name('userList');
    });
    
    Route::middleware(['checkRole:Parent'])->group(function() {
        Route::get('parent/dashboard', [LoginController::class, 'parentDashboard'])->name('parent.dashboard');
    });
    
    Route::middleware(['checkRole:Teacher'])->group(function() {
        Route::get('teacher/dashboard', [LoginController::class, 'teacherDashboard'])->name('teacher.dashboard');
    });
    
    Route::middleware(['checkRole:Student'])->group(function() {
        Route::get('student/dashboard', [LoginController::class, 'studentDashboard'])->name('student.dashboard');
    });

    Route::get('profile', [LoginController::class,'profile'])->name('profile');

    Route::put('/update-profile', [LoginController::class, 'updateProfile'])->name('update_profile');

    Route::put('/update-password', [LoginController::class, 'updatePassword'])->name('update_password');
   
    Route::post('/login-as', [LoginController::class, 'loginAs'])->name('login-as')->middleware('checkSuperAdmin');

});

Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::post('/users', [LoginController::class, 'storeUser'])->name('users.store');
Route::get('/users/{id}/edit', [LoginController::class, 'editUser'])->name('users.edit');
Route::put('/user/upadte/{id}', [LoginController::class, 'updateUser'])->name('users.update');
Route::delete('/user/delete/{id}', [LoginController::class, 'deleteUser'])->name('users.destroy');