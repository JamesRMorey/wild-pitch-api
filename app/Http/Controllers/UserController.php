<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function getUser(): JsonResponse
    {
        $user = auth()->user();

        return response()->json(new UserResource($user));
    }

    public function completeTutorial(): JsonResponse
    {
        $user = auth()->user();

        $user->completed_tutorial = true;
        $user->save();

        return response()->json(new UserResource($user));
    }
}
