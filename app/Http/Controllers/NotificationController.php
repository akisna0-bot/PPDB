<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public static function create($userId, $title, $message, $type = 'info')
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'is_read' => false
        ]);
    }
    
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);
            
        return view('notifications.index', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return response()->json(['success' => true]);
    }
    
    public function getUnreadCount()
    {
        $count = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();
            
        return response()->json(['count' => $count]);
    }
    
    public function getNotifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->limit(10)
            ->get();
            
        return response()->json(['notifications' => $notifications]);
    }
}