<?php

namespace App\Http\Controllers;

use App\Http\Services\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload( Request $request ): JsonResponse
    {
        $user = auth()->user();

        $path = UploadService::base64Image( base64: $request->image, folder: 'images');

        $user->images()->create([
            'user_id' => $user->id,
            'uri' => $path,
            'path' => $path,
        ]);

        return response()->json();
    }
}
