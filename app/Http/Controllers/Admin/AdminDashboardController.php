<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralList;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\alert;

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
    public function addUser(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'required|string|max:15|unique:users,phone_number',
                'password' => 'required|string|min:6',
                'points' => 'required|integer|min:0',
            ]);

            // Create the user
            $user = User::create([
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'phone_number' => $validatedData['phone_number'],
                'password' => Hash::make($validatedData['password']),
                'points' => $validatedData['points'],
            ]);

            // Commit the transaction
            DB::commit();

            // Redirect with success message
            // return redirect()->back()->with('success', 'User added successfully!');
            return response()->json(['success' => true, 'message' => 'User added successfully']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation exceptions
            DB::rollBack();
            Log::error('Validation Error: ' . $e->getMessage());
            // return redirect()->back()->withErrors($e->errors())->withInput();
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()]);
        } catch (\Exception $e) {
            // Handle general exceptions
            DB::rollBack();
            Log::error('Error adding user: ' . $e->getMessage());
            // return redirect()->back()->with('error', 'An error occurred while adding the user. Please try again.');
            return response()->json(['success' => false, 'message' => 'Server error occurred']);
        }
    }
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id); // Find the user by ID
            $user->delete(); // Delete the user from the database

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'User not found or deletion failed'], 500);
        }
    }
}