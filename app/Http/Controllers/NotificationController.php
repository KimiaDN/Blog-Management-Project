<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController
{

    public function index(): JsonResponse
    {
        $user = Auth::user();
        $user_notifications = $user->notifications->pluck(['data']);
        $notifications = [];
        foreach($user_notifications as $notification)
        {
            $notifications[] = [
                'subject' => $notification['subject'],
                'message' => $notification['message'],
                'url' => $notification['url'],
            ];
        }
        return response()->json($notifications, 200);
    }
}
