<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment_Card_Details;
use App\Http\Requests\PaymentCardDetailsRequest;

class PaymentCardDetailsController extends Controller
{
    public function index()
    {
        $cards = Payment_Card_Details::query()->latest()->get();
        return response()->json($cards);
    }

    public function store(PaymentCardDetailsRequest $request)
    {
        $card = Payment_Card_Details::create($request->validated());
        return response()->json($card, 201);
    }

    public function show(string $id)
    {
        $card = Payment_Card_Details::findOrFail($id);
        return response()->json($card);
    }

    public function update(PaymentCardDetailsRequest $request, string $id)
    {
        $card = Payment_Card_Details::findOrFail($id);
        $card->update($request->validated());
        return response()->json($card);
    }

    public function destroy(string $id)
    {
        $card = Payment_Card_Details::findOrFail($id);
        $card->delete();
        return response()->json(['deleted' => true]);
    }
}
