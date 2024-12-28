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
}
