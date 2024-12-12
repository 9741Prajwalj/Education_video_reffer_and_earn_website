<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralList;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class AdminDashboardController extends Controller
{
    
    // Render the dashboard view
    public function index()
    {
      $admin = Auth::guard('admin')->user(); // Retrieve authenticated admin details
      $users = User::all(); // Fetch all users from the database
      $referrals = ReferralList::all(); 
      return view('admin.dashboard', compact('admin','users','referrals')); // Pass admin details to the view
    }

    // Show referrals for a specific user
    public function showReferrals($userId)
    {
        $referrals = ReferralList::where('user_id', $userId)->get(); // Fetch referrals for the user
        return response()->json($referrals); // Return the referrals as JSON
    }

    // Method in AdminDashboardController.php to handle points update
    public function updatePoints(Request $request, $userId)
    {
        $validated = $request->validate([
            'points' => 'required|integer|min:0',
        ]);
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found']);
        }
        // Update the user's points
        $user->points = $request->input('points');
        $user->save();  // Save the updated user

        return response()->json(['success' => true, 'message' => 'Points updated successfully']);
    }
}
