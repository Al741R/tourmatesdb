<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// Backwards-compatible wrapper that extends the new TripRequest
class PlanTripRequest extends TripRequest
{
    // intentionally empty: kept for backward compatibility
}
