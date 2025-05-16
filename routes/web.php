<?php

// For User 🤖
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ForgotPasswordController;
use App\Http\Controllers\User\ResetPasswordController;
use App\Http\Controllers\User\ReferralController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\UserNotificationController;
use App\Mail\OtpMail;  // Add this line to import the OtpMail class
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


//For Admin 🧑‍🏫
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Mail\SendOtpMail;

Route::get('/', function () {
    return view('welcome');
});

// For User 🤖
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard/update-referral', [DashboardController::class, 'updateReferral'])->name('update.referral');

Route::post('/update-referral', [ReferralController::class, 'updateReferral'])->name('update.referral');

// Forgot Password Routes
// Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Reset Password Routes
// Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
// Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::post('/logout', function () {Auth::logout();return redirect('/login');})->name('logout');

// For Changing Password in User page
Route::post('/password/change', [DashboardController::class, 'changePassword'])->name('password.change');

// Define a route for showing the referral list
Route::get('/referrals', [ReferralController::class, 'getReferralList'])->name('referral.list');

// Route to fetch notifications
Route::get('/notifications/fetch', [UserNotificationController::class, 'fetchNotifications']);
Route::post('/notifications/mark-seen', [UserNotificationController::class, 'markAsSeen']);

Route::get('/referral-received', [DashboardController::class, 'getReferralReceived'])->name('get.referral');
Route::post('/store-referral', [DashboardController::class, 'storeReferral'])->name('store.referral');
//
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
Route::get('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('password.request');
Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp']);
Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword']);
//
// Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.forgot');
// Route::post('/password/forgot', function (Request $request) {
//     // Generate OTP here
//     $otp = rand(100000, 999999);

//     // Send OTP email
//     Mail::to($request->email)->send(new SendOtpMail($otp));

//     // Return a response
//     return response()->json(['message' => 'OTP sent successfully']);
// });
// Route::get('/password/verify', [ForgotPasswordController::class, 'showOtpVerificationForm'])->name('password.verify.form');
// Route::post('/password/verify', [ForgotPasswordController::class, 'verifyOtp'])->name('otp.verify.post');
// Route::get('/password/reset', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset.form');
// Route::post('/password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// For Admin 🧑‍🏫

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

//admin notification api
Route::middleware('auth:admin')->group(function () {
    Route::post('/admin/send-notification', [AdminDashboardController::class, 'sendNotification']);
});

// Route to send the notification
Route::post('/admin/send-notification', [AdminDashboardController::class, 'sendNotification'])->name('admin.sendNotification');
