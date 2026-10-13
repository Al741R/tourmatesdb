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
        $methods = Payment_Method::with('booking.user')
            ->get()
            ->filter(function($method) {
                return $method->booking?->user_id === auth('sanctum')->id();
            })
            ->values();
        return response()->json($methods);
    }

    public function store(PaymentMethodRequest $request)
    {
        $method = Payment_Method::create($request->validated());
        $method->load('booking');
        if ($method->booking?->user_id !== auth('sanctum')->id()) {
            $method->delete();
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return response()->json($method, 201);
    }

    public function show(string $id)
    {
        $method = Payment_Method::with('booking')->findOrFail($id);
        if ($method->booking?->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        return response()->json($method);
    }

    public function update(PaymentMethodRequest $request, string $id)
    {
        $method = Payment_Method::with('booking')->findOrFail($id);
        if ($method->booking?->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $method->update($request->validated());
        return response()->json($method);
    }

    public function destroy(string $id)
    {
        $method = Payment_Method::with('booking')->findOrFail($id);
        if ($method->booking?->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }
        $method->delete();
        return response()->json(['deleted' => true]);
    }
}
