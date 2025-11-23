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
        return response()->json(Detail::all());
    }

    public function show(Detail $detail)
    {
        return response()->json($detail);
    }

    public function store(DetailRequest $request)
    {
        $detail = Detail::create($request->validated());
        return response()->json($detail, 201);
    }

    public function update(DetailRequest $request, Detail $detail)
    {
        $detail->update($request->validated());
        return response()->json($detail);
    }

    public function destroy(Detail $detail)
    {
        $detail->delete();
        return response()->json(null, 204);
    }
}
