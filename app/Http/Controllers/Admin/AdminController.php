<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\ReferralList;
use App\Models\User;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function create()
    {
        return view('admin.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'phone' => 'required|string|max:15|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.login')->with('success', 'Registration successful!');
    }
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:referral_list,id',
            'sent' => 'required|in:sent,not sent'
        ]);

        $referral = ReferralList::findOrFail($validated['id']);
        $referral->sent = $validated['sent'];
        $referral->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully!']);
    }
}
