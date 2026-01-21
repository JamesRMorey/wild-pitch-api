<?php

namespace App\Models;

use App\Http\Traits\UniqueSlug;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Route extends Model
{
    use UniqueSlug;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($route) {
            $route->slug = static::generateSlug($route->user_id, $route->name);
        });
    }

    protected $fillable = [
        'user_id',
        'name',
        'notes',
        'latitude',
        'longitude',
        'distance',
        'status',
        'elevation_gain',
        'elevation_loss',
    ];
    protected $hidden = [

    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public static function search ( array $filters, int $limit=100 ): Collection
    {
        $query = Route::query();

        $latitude = $filters['latitude'] ?? null;
        $longitude = $filters['longitude'] ?? null;
        $radius = $filters['radius'] ?? 5000;
        $search = isset($filters['query']) ? Str::replace(' ', '', $filters['query']) : null;

        if ($search) {
            $query->whereRaw("REPLACE(name, ' ', '') LIKE ?", ["%$search%"]);
        }

        if ($latitude && $longitude) {
            $earthRadius = 6371000;
            $latDelta = rad2deg($radius / $earthRadius);
            $lngDelta = rad2deg($radius / ($earthRadius * cos(deg2rad($latitude))));

            $query->select('*')
                ->whereBetween('latitude', [
                    $latitude - $latDelta,
                    $latitude + $latDelta
                ])
                ->whereBetween('longitude', [
                    $longitude - $lngDelta,
                    $longitude + $lngDelta
                ])
                ->selectRaw(
                    '(6371000 * acos(
                        cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))
                    )) AS distance',
                    [$latitude, $longitude, $latitude]
                )
                ->having('distance', '<=', $radius)
                ->orderBy('distance')
                ->get()
                ->toArray();
        }

        $query->with(['markers', 'user']);

        return $query->limit($limit)->get();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markers(): HasMany
    {
        return $this->hasMany(RouteMarker::class);
    }
}
