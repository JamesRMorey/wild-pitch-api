<?php

namespace App\Http\Controllers;

use App\Http\Requests\RouteRequest;
use App\Http\Requests\RouteSearchRequest;
use App\Http\Resources\RouteResource;
use App\Http\Resources\RouteSearchResultResource;
use App\Models\Route;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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
        $user->bookmarkedRoutes()->syncWithoutdetaching($route->id);

        return response()->noContent();
    }

    public function removeBookmark(Route $route): JsonResponse
    {
        $user = auth()->user();
        $user->bookmarkedRoutes()->detach($route);

        return response()->json(RouteResource::collection($user->bookmarkedRoutes));
    }

    public function store(RouteRequest $request): JsonResponse
    {
        $user = auth()->user();
        $data = $request->validated();

        $markers = $data['markers'];
        unset($data['markers']);

        $route = $user->routes()->create($data);

        foreach ($markers as $marker) {
            $route->markers()->create($marker);
        }

        $route->load('markers');

        return response()->json(new RouteResource($route));
    }

    public function update(RouteRequest  $request, Route $route): JsonResponse
    {
        $data = $request->validated();

        $markers = $data['markers'];
        unset($data['markers']);

        $route->update($data);

        $route->markers()->delete();
        foreach ($markers as $marker) {
            $route->markers()->create($marker);
        }

        $route->load('markers');

        return response()->json(new RouteResource($route));
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
}
