<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestinationRequest;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Destination::all());
    }

    public function show(Destination $destination)
    {
        return response()->json($destination);
    }

    public function store(DestinationRequest $request)
    {
        $data = $request->validated();
        $destination = Destination::create($data);
        return response()->json($destination, 201);
    }

    public function update(DestinationRequest $request, Destination $destination)
    {
        $destination->update($request->validated());
        return response()->json($destination);
    }

    public function destroy(Destination $destination)
    {
        $destination->delete();
        return response()->json(null, 204);
    }
}
