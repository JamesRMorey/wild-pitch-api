<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BelongsToUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $type): Response
    {
        $model = $request->route($type);

        if (!$model || !property_exists($model, 'user_id') && !isset($model->user_id)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($request->user()->id !== $model->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
