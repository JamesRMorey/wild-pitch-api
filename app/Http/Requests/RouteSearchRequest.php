<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RouteSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query' => 'sometimes|nullable|string',
            'latitude' => 'sometimes|numeric|required_with:longitude',
            'longitude' => 'sometimes|numeric|required_with:latitude',
            'radius' => 'sometimes|numeric'
        ];
    }
}
