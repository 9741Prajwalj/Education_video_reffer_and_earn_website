<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch all notifications from the database
        $notifications = Notification::all();

        return response()->json($notifications);
    }

    /**
     * Store a newly created notification in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'image_url' => 'nullable|string|url',
        ]);

        // Create and save the notification
        $notification = Notification::create($validated);

        return response()->json($notification, 201); // Return created notification
    }

    /**
     * Display the specified notification.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        return response()->json($notification);
    }

    /**
     * Update the specified notification in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'image_url' => 'nullable|string|url',
        ]);

        // Update notification fields
        $notification->update($validated);

        return response()->json($notification);
    }

    /**
     * Remove the specified notification from the database.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        // Delete the notification
        $notification->delete();

        return response()->json(['message' => 'Notification deleted successfully']);
    }
}
