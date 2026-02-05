<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class AccountController extends Controller
{
    public function destroy(): Response
    {
        $user = auth()->user();
        if ($user->id == 1) {
            return response()->noContent();
        }

        $user->delete();

        return response()->noContent();
    }
}
