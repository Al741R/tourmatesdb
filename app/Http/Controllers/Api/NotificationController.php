<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NotificationRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function index()
    {
        $notifications = Notification::where('user_id', auth('sanctum')->id())->latest()->get();
        return response()->json($notifications);
    }

    public function store(NotificationRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth('sanctum')->id();
        $notification = Notification::create($data);
        return response()->json($notification, 201);
    }

    public function show(Notification $notification)
    {
        if ($notification->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        return response()->json($notification);
    }

    public function update(NotificationRequest $request, Notification $notification)
    {
        if ($notification->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $notification->update($request->validated());
        return response()->json($notification);
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $notification->delete();
        return response()->json(null, 204);
    }
}
