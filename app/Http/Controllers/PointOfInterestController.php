<?php

namespace App\Http\Controllers;

use App\Http\Requests\PointOfInterestRequest;
use App\Http\Requests\RouteRequest;
use App\Http\Requests\RouteSearchRequest;
use App\Http\Resources\PointOfInterestResource;
use App\Http\Resources\RouteResource;
use App\Http\Resources\RouteSearchResultResource;
use App\Models\PointOfInterest;
use App\Models\Route;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PointOfInterestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();
        $pointsOfInterest = $user->pointsOfInterest;

        return response()->json(PointOfInterestResource::collection($pointsOfInterest));
    }

    public function store(PointOfInterestRequest $request): JsonResponse
    {
        $user = auth()->user();
        $data = $request->validated();

        $poi = $user->pointsOfInterest()->create($data);

        return response()->json(new PointOfInterestResource($poi));
    }

    public function update(PointOfInterestRequest  $request, PointOfInterest $pointOfInterest): JsonResponse
    {
        $data = $request->validated();

        $pointOfInterest->update($data);

        return response()->json(new PointOfInterestResource($pointOfInterest));
    }

    public function destroy(PointOfInterest $pointOfInterest): Response
    {
        if ($pointOfInterest->isPublic()) {
            $pointOfInterest->user()->dissociate();
            $pointOfInterest->save();
        }
        else {
            $pointOfInterest->delete();
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
}
