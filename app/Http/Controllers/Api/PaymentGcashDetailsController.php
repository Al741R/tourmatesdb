<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment_Gcash_Details;
use App\Http\Requests\PaymentGcashDetailRequest;

class PaymentGcashDetailsController extends Controller
{
    public function index()
    {
        // Get user_id from the payment_method through the payment relationship
        $gcash = Payment_Gcash_Details::with('payment.booking.user')
            ->get()
            ->filter(function($detail) {
                return $detail->payment?->booking?->user_id === auth('sanctum')->id();
            })
            ->values();
        return response()->json($gcash);
    }

    public function store(PaymentGcashDetailRequest $request)
    {
        $gcash = Payment_Gcash_Details::create($request->validated());

        // Verify the booking belongs to the authenticated user
        $booking = $gcash->payment?->booking;
        if (!$booking || $booking->user_id !== auth('sanctum')->id()) {
            $gcash->delete();
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($gcash, 201);
    }

    public function show(string $id)
    {
        $gcash = Payment_Gcash_Details::with('payment.booking')
            ->where('payment_gcash_id', $id)
            ->firstOrFail();

        // Verify the booking belongs to the authenticated user
        if ($gcash->payment?->booking?->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }

        return response()->json($gcash);
    }

    public function update(PaymentGcashDetailRequest $request, string $id)
    {
        $gcash = Payment_Gcash_Details::with('payment.booking')
            ->where('payment_gcash_id', $id)
            ->firstOrFail();

        // Verify the booking belongs to the authenticated user
        if ($gcash->payment?->booking?->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }

        $gcash->update($request->validated());
        return response()->json($gcash);
    }

    public function destroy(string $id)
    {
        $gcash = Payment_Gcash_Details::with('payment.booking')
            ->where('payment_gcash_id', $id)
            ->firstOrFail();

        // Verify the booking belongs to the authenticated user
        if ($gcash->payment?->booking?->user_id !== auth('sanctum')->id()) {
            abort(403, 'Unauthorized');
        }

        $gcash->delete();
        return response()->json(['deleted' => true]);
    }
}
