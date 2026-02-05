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
            'bounds' => 'sometimes|array|min:2|max:2',
            'radius' => 'sometimes|numeric',
            'max_distance' => 'sometimes|numeric',
            'min_distance' => 'sometimes|numeric',
            'type' => 'sometimes|in:UNKNOWN,CIRCULAR,POINT_TO_POINT,OUT_AND_BACK',
            'difficulty' => 'sometimes|in:UNKNOWN,EASY,MODERATE,CHALLENGING,DIFFICULT',
        ];
    }
}
