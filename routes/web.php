<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ReferralController;
use Illuminate\Support\Facades\Auth;

//For Admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard/update-referral', [DashboardController::class, 'updateReferral'])->name('update.referral');

Route::post('/update-referral', [ReferralController::class, 'updateReferral'])->name('update.referral');

// Forgot Password Routes
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Reset Password Routes
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::post('/logout', function () {Auth::logout();return redirect('/login');})->name('logout');



//For Admin

// Admin Registration
Route::get('/admin/register', [AdminController::class, 'create'])->name('admin.register');
Route::post('/admin/register', [AdminController::class, 'store'])->name('admin.store');

// Admin Login
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// Admin Dashboard
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->middleware('auth')->name('admin.dashboard');

// Route for viewing the referrals of a specific user
Route::get('/admin/referrals', [AdminDashboardController::class, 'getAllReferrals'])->middleware('auth')->name('admin.referrals');

// Define the route for updating points
Route::post('/admin/dashboard/update-points/{userId}', [AdminDashboardController::class, 'updatePoints'])->middleware('auth:admin')->name('admin.dashboard.updatePoints');

// Route for viewing the referrals of a specific user
Route::get('/admin/referrals/{userId}', [AdminDashboardController::class, 'showReferrals'])->middleware('auth:admin')->name('admin.referrals');

// Route for Adding the User in Admin Dashboard
Route::post('/admin/dashboard/add-user', [AdminDashboardController::class, 'addUser'])->middleware('auth:admin')->name('admin.add-user');

// Route to delete a user
Route::delete('/admin/dashboard/delete-user/{id}', [AdminDashboardController::class, 'deleteUser'])->middleware('auth:admin')->name('admin.delete-user');










