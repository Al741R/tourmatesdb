<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', auth('sanctum')->id())
            ->with(['accommodation','trip','user'])
            ->get();
        return response()->json($bookings);
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $booking->load(['accommodation','trip','user']);
        return response()->json($booking);
    }

    public function store(BookingRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth('sanctum')->id();
        $booking = Booking::create($data);
        return response()->json($booking, 201);
    }

    public function update(BookingRequest $request, Booking $booking)
    {
        if ($booking->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $booking->update($request->validated());
        return response()->json($booking);
    }

    public function destroy(Booking $booking)
    {
        if ($booking->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $booking->delete();
        return response()->json(null, 204);
    }
}
