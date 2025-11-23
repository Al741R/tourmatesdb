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
        return response()->json(Notification::all());
    }

    public function store(NotificationRequest $request)
    {
        $notification = Notification::create($request->validated());
        return response()->json($notification, 201);
    }

    public function show(Notification $notification)
    {
        return response()->json($notification);
    }

    public function update(NotificationRequest $request, Notification $notification)
    {
        $notification->update($request->validated());
        return response()->json($notification);
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return response()->json(null, 204);
    }
}
