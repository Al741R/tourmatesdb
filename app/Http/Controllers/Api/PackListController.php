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
        return response()->json(PackList::all());
    }

    public function show(PackList $packList)
    {
        return response()->json($packList);
    }

    public function store(PackListRequest $request)
    {
        $packList = PackList::create($request->validated());
        return response()->json($packList, 201);
    }

    public function update(PackListRequest $request, PackList $packList)
    {
        $packList->update($request->validated());
        return response()->json($packList);
    }

    public function destroy(PackList $packList)
    {
        $packList->delete();
        return response()->json(null, 204);
    }
}
