<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Auth\referral_list;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    /**
     * Display the user dashboard with dynamic data.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the logged-in user
        $user = Auth::user();
        $points = $user->points ?? 5; // Default points value if not set

        // Retrieve the referral list for the current user
        $referralList = DB::table('referral_list')
            ->where('user_id', $user->id)
            ->get(['referral_name', 'referral_phone']); // Adjust column names if needed

        // Return the 'dashboard' view with user data, points, and referral list
        return view('auth.dashboard', [
            'username' => $user->username,
            'points' => $points,
            'referralList' => $referralList,
        ]);
    }

    /**
     * Handle the update of referral details and decrement points.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateReferral(Request $request)
    {
        // Validate the input
        $request->validate([
            'referral_username' => 'required|string|max:55',
            'referral_phone' => 'required|string|max:10',
        ]);

        // Get the logged-in user
        $user = Auth::user();

        // Decrement points
        /** @var \App\Models\User $user */
        if ($user->points > 0) {
            $user->decrement('points', 1); // Decrease points by 1
        } 

    // Save referral details to 'referral_list' table
    DB::table('referral_list')->insert([
        'referral_count' => $user->id, // Replace with the actual foreign key column if different
        'referral_name' => $request->referral_username,
        'referral_phone' => $request->referral_phone,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

        // Here, you could also save referral information to the database if needed

        return redirect()->route('dashboard')->with('success', 'Referral submitted and points updated.');
    }
    // public function showDashboard()
    // {
    //     $points = auth()->user()->points; // Retrieve the user's points from the database
    //     return view('dashboard', compact('points'));
    // }

}
