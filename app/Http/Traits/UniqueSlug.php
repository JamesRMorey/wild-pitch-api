<?php

namespace App\Http\Traits;

use Illuminate\Support\Str;

trait UniqueSlug
{

    public static function generateSlug($userId, $name): string
    {
        $baseSlug = $userId . '-' . Str::slug($name);
        $slug = $baseSlug;
        $i = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        return $slug;
    }
}
