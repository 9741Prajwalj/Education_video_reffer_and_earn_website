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

    // Method in AdminDashboardController.php to handle points update
    public function updatePoints(Request $request, $userId)
    {
        try {
            // Validate input
            $request->validate([
                'points' => 'required|integer|min:0',
            ]);

            // Find user
            $user = User::find($userId);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            // Update points
            $user->points = $request->input('points');
            $user->save();

            // JSON response for AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Points updated successfully.',
                ]);
            }

            // Redirect back to dashboard for regular requests
            return redirect()->route('admin.dashboard')->with('success', 'Points updated successfully.');
        } catch (\Exception $e) {
            // JSON response for AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage(),
                ], 500);
            }

            // Redirect back to dashboard with error message for regular requests
            return redirect()->route('admin.dashboard')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function getAllReferrals()
    {
        $referrals = ReferralList::all(); // Fetch all referrals
        return response()->json($referrals); // Return all referrals as JSON
    }

    // Show referrals for a specific user
    public function showReferrals($userId)
    {
        $referrals = ReferralList::where('user_id', $userId)->get(); // Filter referrals by user ID
        return response()->json($referrals); // Return filtered referrals as JSON
    }

}
