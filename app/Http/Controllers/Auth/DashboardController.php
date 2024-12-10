<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard with static data.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

      // return view('auth.dashboard');
        // Static data for the dashboard
        $user = (object) [
            'name' => 'John Doe',
            'phone_number' => '123-456-7890'
        ];
        $points = 10; // Static points value

        // Return the 'dashboard' view with static data
        return view('auth.dashboard', [
            'user' => $user,
            'points' => $points,
        ]);
    }
}
