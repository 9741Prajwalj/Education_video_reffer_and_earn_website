<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ReferralList;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use App\Models\User;

class ReferralController extends Controller
{
    /**
     * Get the referral details for a specific user ID.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReferralDetails($userId)
    {
        // Fetch referral details for the given user ID
        $referrals = ReferralList::where('user_id', $userId)->get(['referral_name', 'referral_phone']);

        // Check if any referrals are found
        if ($referrals->isEmpty()) {
            return response()->json([
                'message' => 'No referral details found for this user ID.'
            ], 404);
        }

        // Return the referral data as a JSON response
        return response()->json($referrals, 200);
    }

    public function updateReferral(Request $request)
    {
        $request->validate([
            'referral_name' => 'required|string|max:255',
            'referral_phone' => 'required|string|max:15',
            'user_id' => 'required|exists:users,id',
        ]);

        ReferralList::updateOrCreate(
            ['user_id' => $request->user_id, 'referral_name' => $request->referral_name],
            ['referral_phone' => $request->referral_phone]
        );

        // Increment the referral count for the user
        $user = User::find($request->user_id);
        if ($user) {
            $user->increment('referral_count');
        }
        // Find the user and decrement points
        $user = User::find($request->user_id);
        if ($user) {
            // Assuming 'points' is a field in the 'users' table
            $user->decrement('points', 1); // Decrements points by 1; adjust as needed
        }
        return redirect()->route('dashboard')->with('success', 'Referral information updated successfully.');
    }

    public function getReferrals()
    {
        $referrals = ReferralList::all();
        return response()->json($referrals);
    }

     // Method to show the referral list
    //  public function getReferralList()
    //  {
    //      // Fetch all the referral records from the database
    //     $referralList = ReferralList::all();
    //     // Get the current authenticated user
    //     $userId = auth()->user() ? auth()->user()->id : null; // Fetch the current logged-in user's ID, or null if not authenticated
    //     // Check if the user is authenticated
    //     if (!$userId) {
    //         return response()->json(['error' => 'User not authenticated'], 401);
    //     }
    //     // Fetch only the referrals for the current user
    //     $referralList = DB::table('referral_list')
    //                     ->where('user_id', $userId) // Filter referrals by the logged-in user's ID
    //                     ->get();

    //     // Return the filtered data as JSON for frontend usage
    //     return response()->json($referralList);
    //  }
}
