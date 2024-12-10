<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'referral' => 'nullable|string|max:255',
        ]);
    
        // Log::info('Form data:', $request->all()); // Logs all form data
    
        try {
            // Create a new user in the database
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'referral' => $request->referral,
            ]);
    
            // Log the user details after insertion, excluding sensitive data
            // Log::info('New user created:', [
            //     'id' => $user->id,
            //     'username' => $user->username,
            //     'email' => $user->email,
            //     'phone_number' => $user->phone_number,
            //     'referral' => $user->referral,
            // ]);
    
            return redirect()->route('login')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            // Log::error('User creation failed:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Registration failed. Please try again.');
        }
    }
    
}
