<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackListRequest;
use App\Models\PackList;
use Illuminate\Http\Request;

class PackListController extends Controller
{
    public function index()
    {
        $packLists = PackList::with('trip')
            ->get()
            ->filter(function($packList) {
                return $packList->trip?->user_id === auth('sanctum')->id();
            })
            ->values();
        return response()->json($packLists);
    }

    public function show(PackList $packList)
    {
        $packList->load('trip');
        if ($packList->trip?->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        return response()->json($packList);
    }

    public function store(PackListRequest $request)
    {
        $packList = PackList::create($request->validated());
        $packList->load('trip');
        if ($packList->trip?->user_id !== auth('sanctum')->id()) {
            $packList->delete();
            abort(403, 'Unauthorized');
        }
        return response()->json($packList, 201);
    }

    public function update(PackListRequest $request, PackList $packList)
    {
        $packList->load('trip');
        if ($packList->trip?->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $packList->update($request->validated());
        return response()->json($packList);
    }

    public function destroy(PackList $packList)
    {
        $packList->load('trip');
        if ($packList->trip?->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $packList->delete();
        return response()->json(null, 204);
    }
}
