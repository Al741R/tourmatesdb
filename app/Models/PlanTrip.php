<?php

namespace App\Models;

// Backwards-compatible alias: keep PlanTrip class extant but delegate to Trip
class PlanTrip extends Trip
{
    // intentionally empty — extends Trip for backward compatibility
}
