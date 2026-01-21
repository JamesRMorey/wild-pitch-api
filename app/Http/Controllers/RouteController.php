<?php

namespace App\Http\Controllers;

use App\Http\Requests\RouteRequest;
use App\Http\Requests\RouteSearchRequest;
use App\Http\Resources\RouteResource;
use App\Models\Route;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    public function saved (): JsonResponse
    {
        $user = auth()->user();
        $routes = $user->routes;

        return response()->json(RouteResource::collection($routes));
    }

    public function search (RouteSearchRequest $request): JsonResponse
    {
        $data = $request->validated();
        $limit = $data['limit'] ?? 100;
        $routes = Route::search($data, min($limit, 100));

        return response()->json(RouteResource::collection($routes));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RouteRequest  $request, Route $route): JsonResponse
    {
        $data = $request->validated();

        $markers = $data['markers'];
        unset($data['markers']);

        $route->update($data);

        $route->markers()->delete();

        // Add new markers
        foreach ($markers as $marker) {
            $route->markers()->create($marker);
        }

        $route->load('markers');

        return response()->json(new RouteResource($route));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route): Response
    {
        $route->delete();

        return response()->noContent();
    }
}
