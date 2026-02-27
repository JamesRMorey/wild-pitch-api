<?php

namespace App\Http\Controllers;

use App\Http\Services\UploadService;
use App\Models\Image;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function store( Request $request ): Response
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
