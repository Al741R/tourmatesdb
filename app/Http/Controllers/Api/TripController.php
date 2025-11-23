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
        return response()->json(Trip::with(['destination','accommodations','itineraries','packLists'])->get());
    }

    public function show(Trip $trip)
    {
        $trip->load(['destination','accommodations','itineraries','packLists']);
        return response()->json($trip);
    }

    public function store(TripRequest $request)
    {
        $data = $request->validated();
        $trip = Trip::create($data);
        return response()->json($trip, 201);
    }

    public function update(TripRequest $request, Trip $trip)
    {
        $trip->update($request->validated());
        return response()->json($trip);
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();
        return response()->json(null, 204);
    }
}
