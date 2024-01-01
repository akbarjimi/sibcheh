<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flight>
 */
class FlightFactory extends Factory
{
    public function definition(): array
    {
        return [
            'from_location_id' => Location::factory(),
            'to_location_id' => Location::factory(),
            'user_id' => User::factory(),
            'departure_time' => now()->toDateTimeString(),
            'arrival_time' => now()->addHours(2)->toDateTimeString(),
        ];
    }
}
