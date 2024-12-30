<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Http\Controllers\Controller;

class UserNotificationController extends Controller
{
    // Fetch notifications for the authenticated user
    public function getNotifications()
    {
        $userId = Auth::id(); // Get current logged-in user ID
        $notifications = Notification::where('user_id', $userId)->latest()->get(); // Fetch notifications for the user
        return response()->json($notifications);
    }

    // Fetch notifications for the logged-in user
    public function fetchNotifications()
    {
        $userId = Auth::id();
        $notifications = Notification::where('user_id', $userId)->latest()->get(['title', 'message', 'seen_at']);
        $unseenCount = Notification::where('user_id', $userId)->whereNull('seen_at')->count();

        return response()->json([
            'notifications' => $notifications,
            'unseen_count' => $unseenCount,
        ]);
    }

    // Mark notifications as seen
    public function markAsSeen(Request $request)
    {
        $userId = Auth::id();
        Notification::where('user_id', $userId)->whereNull('seen_at')->update(['seen_at' => now()]);

        return response()->json(['message' => 'Notifications marked as seen.']);
    }


}
