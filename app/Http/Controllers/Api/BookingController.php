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
        return response()->json(Booking::with(['accommodation','trip','user'])->get());
    }

    public function show(Booking $booking)
    {
        $booking->load(['accommodation','trip','user']);
        return response()->json($booking);
    }

    public function store(BookingRequest $request)
    {
        $booking = Booking::create($request->validated());
        return response()->json($booking, 201);
    }

    public function update(BookingRequest $request, Booking $booking)
    {
        $booking->update($request->validated());
        return response()->json($booking);
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json(null, 204);
    }
}
