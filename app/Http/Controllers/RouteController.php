<?php

namespace App\Http\Controllers;

use App\Http\Requests\RouteRequest;
use App\Http\Requests\RouteSearchRequest;
use App\Http\Resources\ImageResource;
use App\Http\Resources\RouteResource;
use App\Http\Resources\RouteSearchResultResource;
use App\Http\Services\UploadService;
use App\Models\Route;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();
        $routes = $user->routes;

        return response()->json(RouteResource::collection($routes));
    }

    public function store(RouteRequest $request): JsonResponse
    {
        $user = auth()->user();
        $data = $request->validated();

        return DB::transaction(function () use ($user, $data) {
            $markers = $data['markers'];
            unset($data['markers']);

            $route = $user->routes()->create($data);

            foreach ($markers as $marker) {
                $route->markers()->create($marker);
            }

            $route->load('markers');

            return response()->json(new RouteResource($route));
        });
    }

    public function update(RouteRequest  $request, Route $route): JsonResponse
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data, $route) {
            $markers = $data['markers'];
            unset($data['markers']);

            $route->update($data);

            $route->markers()->delete();
            foreach ($markers as $marker) {
                $route->markers()->create($marker);
            }

            $route->load('markers');

            return response()->json(new RouteResource($route));
        });
    }

    public function destroy(Route $route): Response
    {
        if ($route->isPublic()) {
            $route->user()->dissociate();
            $route->save();
        }
        else {
            $route->delete();
        }

        return response()->noContent();
    }

    public function featured(RouteSearchRequest $request): JsonResponse
    {
        $data = $request->validated();
        $limit = $data['limit'] ?? 100;
        $routes = Route::featured($data, min($limit, 5));

        return response()->json(RouteSearchResultResource::collection($routes));
    }

    public function bookmarked(): JsonResponse
    {
        $user = auth()->user();
        $routes = $user->bookmarkedRoutes;

        return response()->json(RouteResource::collection($routes));
    }

    public function find(Route $route): JsonResponse
    {
        return response()->json(new RouteResource($route));
    }

    public function search(RouteSearchRequest $request): JsonResponse
    {
        $data = $request->validated();
        $limit = $data['limit'] ?? 100;
        $routes = Route::search($data, min($limit, 100));

        return response()->json(RouteSearchResultResource::collection($routes));
    }

    public function bookmark(Route $route): Response
    {
        $user = auth()->user();
        if ($route->belongsToUser($user)) {
            return response()->noContent();
        }

        $user->bookmarkedRoutes()->syncWithoutdetaching($route->id);

        return response()->noContent();
    }

    public function removeBookmark(Route $route): JsonResponse
    {
        $user = auth()->user();
        $user->bookmarkedRoutes()->detach($route);

        return response()->json(RouteResource::collection($user->bookmarkedRoutes));
    }

    public function makePublic(Route $route): JsonResponse
    {
        $route->makePublic();
        $route->load('markers');

        return response()->json(new RouteResource($route));
    }

    public function uploadImage( Request $request, Route $route ): JsonResponse
    {
        $user = auth()->user();

        $path = UploadService::base64Image(
            base64: $request->image,
            folder: "images/routes/$route->id"
        );

        $image = $route->images()->create([
            'user_id' => $user->id,
            'uri' => env('AWS_S3_PATH') . '/' . $path,
            'path' => $path
        ]);

        return response()->json(new ImageResource($image));
    }
}
