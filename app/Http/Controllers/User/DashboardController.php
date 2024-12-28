<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
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
        $user = User::user();
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
    public function changePassword(Request $request)
    {
        // Validate input
        $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:6',
        ]);
        // Get the currently authenticated user
        $user = Auth::user(); // Correct way to get the authenticated user
        // Verify current password
        if (!Hash::check($request->currentPassword, $user->password)) {
            // Log error and return validation error
            Log::error('Current password mismatch for user ID: ' . $user->id);
            return back()->withErrors(['currentPassword' => 'The current password is incorrect.']);
        }
        try {
            // Update the password
            $user->password = Hash::make($request->newPassword);
            // Save the updated user model
            $user->save(); // Save the updated password
            // Log success message
            Log::info('Password updated successfully for user ID: ' . $user->id);
            return back()->with('success', 'Password changed successfully!');
        } catch (\Exception $e) {
            // Log error message
            Log::error('Error updating password for user ID: ' . $user->id . '. Error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the password. Please try again.');
        }
    }

     // Get notifications for the logged-in user
      // Get notifications for the logged-in user
    // public function getNotifications()
    // {
    //     $notifications = Auth::user()->notifications; // Retrieve notifications for the logged-in user
    //     return response()->json($notifications, 200);
    // }
    // public function showNotifications()
    // {
    //     if (Auth::check()) {
    //         $user = Auth::user(); // Get the authenticated user
    //         $notifications = $user->notifications; // Fetch notifications

    //         // Check if the notifications are fetched
    //         dd($notifications);

    //         // Return notifications as JSON or to view
    //         return view('auth.dashboard', compact('notifications'));
    //     } else {
    //         return redirect()->route('login'); // Redirect to login page if user is not authenticated
    //     }
    // }
     
}