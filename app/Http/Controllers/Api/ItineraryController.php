<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItineraryRequest;
use App\Models\Itinerary;
use Illuminate\Http\Request;

class ItineraryController extends Controller
{
    public function index()
    {
        $itineraries = Itinerary::with('trip')
            ->get()
            ->filter(function($itinerary) {
                return $itinerary->trip?->user_id === auth('sanctum')->id();
            })
            ->values();
        return response()->json($itineraries);
    }

    public function show(Itinerary $itinerary)
    {
        $itinerary->load('trip');
        if ($itinerary->trip?->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        return response()->json($itinerary);
    }

    public function store(ItineraryRequest $request)
    {
        $itinerary = Itinerary::create($request->validated());
        $itinerary->load('trip');
        if ($itinerary->trip?->user_id !== auth('sanctum')->id()) {
            $itinerary->delete();
            abort(403, 'Unauthorized');
        }
        return response()->json($itinerary, 201);
    }

    public function update(ItineraryRequest $request, Itinerary $itinerary)
    {
        $itinerary->load('trip');
        if ($itinerary->trip?->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $itinerary->update($request->validated());
        return response()->json($itinerary);
    }

    public function destroy(Itinerary $itinerary)
    {
        $itinerary->load('trip');
        if ($itinerary->trip?->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $itinerary->delete();
        return response()->json(null, 204);
    }
}
