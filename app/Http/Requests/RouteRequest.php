<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RouteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'notes' => 'sometimes|nullable|string',
            'markers' => 'required|array',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'distance' => 'nullable|numeric',
            'status' => 'sometimes|in:PRIVATE,PUBLIC',
            'difficulty' => 'sometimes|nullable|in:EASY,MODERATE,CHALLENGING,DIFFICULT,UNKNOWN',
            'type' => 'sometimes|nullable|in:CIRCULAR,POINT_TO_POINT,OUT_AND_BACK,UNKNOWN',
            'elevation_gain' => 'sometimes|nullable|numeric',
            'elevation_loss' => 'sometimes|nullable|numeric',
            'updated_at' => 'sometimes|date',
        ];
    }
}
