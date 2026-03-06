<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUpdateRequest;
use App\Http\Requests\ImageUploadRequest;
use App\Http\Resources\ImageResource;
use App\Http\Services\UploadService;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function store( ImageUploadRequest $request ): JsonResponse
    {
        $user = auth()->user();

        $path = UploadService::base64Image( base64: $request->image, folder: 'images');

        $image = $user->images()->create([
            'user_id' => $user->id,
            'uri' => $path,
            'path' => $path,
        ]);

        return response()->json(new ImageResource($image));
    }

    public function update( ImageUpdateRequest $request, Image $image ): JsonResponse
    {
        $image->update($request->validated());

        return response()->json(new ImageResource($image));
    }

    public function destroy( Image $image ): Response
    {
        $deleted = Storage::disk('s3')->delete($image->path);

        if (!$deleted) {
            throw new \Error('Image failed to delete from s3');
        }

        $image->delete();

        return response()->noContent();
    }
}
