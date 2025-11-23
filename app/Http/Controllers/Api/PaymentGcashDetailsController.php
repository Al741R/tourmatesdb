<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment_Gcash_Details;
use App\Http\Requests\PaymentGcashDetailRequest;

class PaymentGcashDetailsController extends Controller
{
    public function index()
    {
        $gcash = Payment_Gcash_Details::query()->latest()->get();
        return response()->json($gcash);
    }

    public function store(PaymentGcashDetailRequest $request)
    {
        $gcash = Payment_Gcash_Details::create($request->validated());
        return response()->json($gcash, 201);
    }

    public function show(string $id)
    {
        $gcash = Payment_Gcash_Details::findOrFail($id);
        return response()->json($gcash);
    }

    public function update(PaymentGcashDetailRequest $request, string $id)
    {
        $gcash = Payment_Gcash_Details::findOrFail($id);
        $gcash->update($request->validated());
        return response()->json($gcash);
    }

    public function destroy(string $id)
    {
        $gcash = Payment_Gcash_Details::findOrFail($id);
        $gcash->delete();
        return response()->json(['deleted' => true]);
    }
}
