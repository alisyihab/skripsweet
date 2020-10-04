<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
        $user = request()->user();

        return response()->json([
            'status' => 'success',
            'data' => $user->unreadNotifications
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $user->unreadNotifications()
            ->where('id', $request->id)
            ->update(['read_at' => now()]);

        return response()->json([
           'status' => 'success'
        ]);
    }

    public function notificationList()
    {
        $user = request()->user();

        $notifications = 
            $user->notifications()
            ->orderBy('created_at', 'DESC')
            ->paginate(10)
        ;

        return response()->json([
            'status' => 'success',
            'result' => $notifications
        ]);
    }
}
