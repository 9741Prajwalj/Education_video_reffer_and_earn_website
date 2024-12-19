<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ReferralList;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

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

}
