<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    //
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())->get();
        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        if ($notification->user_id == auth()->id()) {
            $notification->update(['read' => true]);
            return response()->json(['message' => 'Notification marked as read.']);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
