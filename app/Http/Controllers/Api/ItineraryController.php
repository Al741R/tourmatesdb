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
        return response()->json(Itinerary::all());
    }

    public function show(Itinerary $itinerary)
    {
        return response()->json($itinerary);
    }

    public function store(ItineraryRequest $request)
    {
        $itinerary = Itinerary::create($request->validated());
        return response()->json($itinerary, 201);
    }

    public function update(ItineraryRequest $request, Itinerary $itinerary)
    {
        $itinerary->update($request->validated());
        return response()->json($itinerary);
    }

    public function destroy(Itinerary $itinerary)
    {
        $itinerary->delete();
        return response()->json(null, 204);
    }
}
