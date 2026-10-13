<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment_Card_Details;
use App\Http\Requests\PaymentCardDetailsRequest;

class PaymentCardDetailsController extends Controller
{
    public function index()
    {
        // Only return cards belonging to the authenticated user
        $userId = auth('sanctum')->id();

        // Debug: Return user info if you want to verify authentication
        // return response()->json(['user_id' => $userId, 'authenticated' => auth('sanctum')->check()]);

        $cards = Payment_Card_Details::where('user_id', $userId)->latest()->get();
        return response()->json($cards);
    }

    public function store(PaymentCardDetailsRequest $request)
    {
        // Ensure the card is created for the authenticated user
        $data = $request->validated();
        $data['user_id'] = auth('sanctum')->id();

        $card = Payment_Card_Details::create($data);
        return response()->json($card, 201);
    }

    public function show(string $id)
    {
        // Only allow user to view their own card
        $card = Payment_Card_Details::where('user_id', auth('sanctum')->id())
            ->where('payment_card_id', $id)
            ->firstOrFail();
        return response()->json($card);
    }

    public function update(PaymentCardDetailsRequest $request, string $id)
    {
        // Only allow user to update their own card
        $card = Payment_Card_Details::where('user_id', auth('sanctum')->id())
            ->where('payment_card_id', $id)
            ->firstOrFail();
        $card->update($request->validated());
        return response()->json($card);
    }

    public function destroy(string $id)
    {
        // Only allow user to delete their own card
        $card = Payment_Card_Details::where('user_id', auth('sanctum')->id())
            ->where('payment_card_id', $id)
            ->firstOrFail();
        $card->delete();
        return response()->json(['deleted' => true]);
    }
}
