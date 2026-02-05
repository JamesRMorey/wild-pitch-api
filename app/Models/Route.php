<?php

namespace App\Models;

use App\Http\Traits\IsPublic;
use App\Http\Traits\UniqueSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Route extends Model
{
    use UniqueSlug, IsPublic;

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
        'type',
        'creation_type',
        'difficulty',
        'updated_at'
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
        $bounds = $filters['bounds'] ?? null;
        $difficulty = $filters['difficulty'] ?? null;
        $maxDistance = $filters['max_distance'] ?? null;
        $minDistance = $filters['min_distance'] ?? null;
        $type = $filters['type'] ?? null;

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
                ->orderBy('distance');
        }
        elseif ($bounds && isset($bounds['ne']) && isset($bounds['sw'])) {
            $ne = $bounds['ne'];
            $sw = $bounds['sw'];

            $query->select('*')
                ->whereBetween('latitude', [$sw[1], $ne[1]])
                ->whereBetween('longitude', [$sw[0], $ne[0]]);
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($difficulty) {
            $query->where('difficulty', $difficulty);
        }

        if ($maxDistance && $minDistance) {
            $query->whereBetween('distance', [$minDistance, $maxDistance]);
        }
        else if ($maxDistance) {
            $query->where('distance', '<=', $maxDistance);
        }
        else if ($minDistance) {
            $query->where('distance', '>=', $minDistance);
        }

        $query->where('status', 'PUBLIC');
        $query->with(['user']);

        return $query->limit($limit)->get();
    }

    public static function featured( array $filters, int $limit=5 ): Collection
    {
        return self::search($filters, $limit);
    }

    public function makePublic(): Route
    {
        if ($this->isPublic()) return $this;

        $this->status = 'PUBLIC';
//        $this->published_at = Carbon::now();
        $this->save();

        return $this;
    }

    public function belongsToUser (User $user): bool
    {
        return $this->user?->id === $user->id;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function markers(): HasMany
    {
        return $this->hasMany(RouteMarker::class);
    }
}
