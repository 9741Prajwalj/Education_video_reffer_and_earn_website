<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Mail\OtpMail; // Ensure this is the correct path to your Mailable class
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store OTP in the database
        Otp::updateOrCreate(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'created_at' => now(),
                'expires_at' => Carbon::now()->addMinutes(10)
            ]
        );

        // Send OTP via email
        Mail::to($request->email)->send(new \App\Mail\SendOtpMail($otp));

        return response()->json(['message' => 'OTP sent to your email.']);
    }

    public function showOtpVerificationForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric'
        ]);

        $otpRecord = Otp::where('email', $request->email)->where('otp', $request->otp)->first();

        if (!$otpRecord) {
            return response()->json(['message' => 'Invalid OTP.'], 400);
        }

        if (Carbon::now()->greaterThan($otpRecord->expires_at)) {
            return response()->json(['message' => 'OTP has expired.'], 400);
        }

        // OTP is valid - Proceed with password reset
        return response()->json(['message' => 'OTP verified.']);
    }


    public function showResetPasswordForm(Request $request)
    {
        return view('auth.reset-password', ['email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['message' => 'Password reset successfully.']);
    }

}
