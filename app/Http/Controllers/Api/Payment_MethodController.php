<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment_Method;
use App\Http\Requests\PaymentMethodRequest;

class Payment_MethodController extends Controller
{
    public function index()
    {
        $methods = Payment_Method::query()->latest()->get();
        return response()->json($methods);
    }

    public function store(PaymentMethodRequest $request)
    {
        $method = Payment_Method::create($request->validated());
        return response()->json($method, 201);
    }

    public function show(string $id)
    {
        $method = Payment_Method::findOrFail($id);
        return response()->json($method);
    }

    public function update(PaymentMethodRequest $request, string $id)
    {
        $method = Payment_Method::findOrFail($id);
        $method->update($request->validated());
        return response()->json($method);
    }

    public function destroy(string $id)
    {
        $method = Payment_Method::findOrFail($id);
        $method->delete();
        return response()->json(['deleted' => true]);
    }
}
