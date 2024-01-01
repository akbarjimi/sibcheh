<?php

namespace App\Http\Requests;

use App\Rules\FlightGraphConnectedRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFlightRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'passport' => [
                'required',
                'exists:users,passport'
            ],
            'path' => [
                'required',
                'array',
                new FlightGraphConnectedRule,
            ],
            'path.*.from' => [
                'required',
                'exists:locations,name'
            ],
            'path.*.to' => [
                'required',
                'exists:locations,name'
            ]
        ];
    }
}
