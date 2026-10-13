<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DetailRequest;
use App\Models\Detail;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    public function index()
    {
        $details = Detail::with('trip')
            ->get()
            ->filter(function($detail) {
                return $detail->trip?->user_id === auth('sanctum')->id();
            })
            ->values();
        return response()->json($details);
    }

    public function show(Detail $detail)
    {
        $detail->load('trip');
        if ($detail->trip?->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        return response()->json($detail);
    }

    public function store(DetailRequest $request)
    {
        $detail = Detail::create($request->validated());
        $detail->load('trip');
        if ($detail->trip?->user_id !== auth('sanctum')->id()) {
            $detail->delete();
            abort(403, 'Unauthorized');
        }
        return response()->json($detail, 201);
    }

    public function update(DetailRequest $request, Detail $detail)
    {
        $detail->load('trip');
        if ($detail->trip?->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $detail->update($request->validated());
        return response()->json($detail);
    }

    public function destroy(Detail $detail)
    {
        $detail->load('trip');
        if ($detail->trip?->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $detail->delete();
        return response()->json(null, 204);
    }
}
