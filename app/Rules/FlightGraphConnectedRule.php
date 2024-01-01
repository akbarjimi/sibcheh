<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FlightGraphConnectedRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->passes($value)) {
            $fail("validation.flight_path.genuine")->translate();
        }
    }

    public function passes($value): bool
    {
        foreach ($value as $index => $path) {
            if ($index < count($value) - 1) {
                $currentDestination = $path['to'];
                $nextOrigin = $value[$index + 1]['from'];

                if ($currentDestination !== $nextOrigin) {
                    return false;
                }
            }
        }

        return true;
    }
}
