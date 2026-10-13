<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TripRequest;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::where('user_id', auth('sanctum')->id())
            ->with(['destination','accommodations','itineraries','packLists'])
            ->get();
        return response()->json($trips);
    }

    public function show(Trip $trip)
    {
        if ($trip->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $trip->load(['destination','accommodations','itineraries','packLists']);
        return response()->json($trip);
    }

    public function store(TripRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth('sanctum')->id();
        $trip = Trip::create($data);
        return response()->json($trip, 201);
    }

    public function update(TripRequest $request, Trip $trip)
    {
        if ($trip->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $trip->update($request->validated());
        return response()->json($trip);
    }

    public function destroy(Trip $trip)
    {
        if ($trip->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $trip->delete();
        return response()->json(null, 204);
    }
}
