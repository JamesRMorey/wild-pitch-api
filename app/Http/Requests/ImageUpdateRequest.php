<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order' => 'nullable|sometimes|numeric',
        ];
    }
}
