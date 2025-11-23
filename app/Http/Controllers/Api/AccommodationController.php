<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccommodationRequest;
use App\Models\Accommodation;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{
    public function index()
    {
        return response()->json(Accommodation::all());
    }

    public function show(Accommodation $accommodation)
    {
        return response()->json($accommodation);
    }

    public function store(AccommodationRequest $request)
    {
        $accommodation = Accommodation::create($request->validated());
        return response()->json($accommodation, 201);
    }

    public function update(AccommodationRequest $request, Accommodation $accommodation)
    {
        $accommodation->update($request->validated());
        return response()->json($accommodation);
    }

    public function destroy(Accommodation $accommodation)
    {
        $accommodation->delete();
        return response()->json(null, 204);
    }
}
