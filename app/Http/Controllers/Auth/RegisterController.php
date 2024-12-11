<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Import Log facade

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Log incoming registration request data (sensitive information like password should be excluded)
        // Log::info('Registration request received', [
        //     'username' => $request->username,
        //     'email' => $request->email,
        //     'phone_number' => $request->phone_number,
        //     'referral' => $request->referral,
        // ]);

        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'referral' => 'nullable|string|max:255',
        ]);

        try {
            // Log the successful validation of the request
            // Log::info('Request validation successful');

            // Create a new user in the database
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'referral' => $request->referral,
            ]);

            // Log the successful user creation
            // Log::info('User successfully created', ['user_id' => $user->id]);

            return redirect()->route('login')->with('success', 'Registration successful! Please login.');
        } catch (\Exception $e) {
            // Log the error and exception details
            // Log::error('Registration failed', [
            //     'error_message' => $e->getMessage(),
            //     'stack_trace' => $e->getTraceAsString(),
            // ]);

            return redirect()->back()->with('error', 'Registration failed. Please try again.');
        }
    }
}
