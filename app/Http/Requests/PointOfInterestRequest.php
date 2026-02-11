<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PointOfInterestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'notes' => 'sometimes|nullable|string',
            'point_type_id' => 'required|numeric',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'elevation' => 'nullable|numeric',
            'status' => 'sometimes|in:PRIVATE,PUBLIC',
            'updated_at' => 'sometimes|date',
        ];
    }
}
