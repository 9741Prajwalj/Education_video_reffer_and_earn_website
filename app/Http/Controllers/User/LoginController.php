<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle the login request
    public function login(Request $request)
    {
        // Validate the form data
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Attempt to log in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Redirect to the intended page after successful login
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Logged in successfully!');
        }

        // If authentication fails
        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
    }
}
