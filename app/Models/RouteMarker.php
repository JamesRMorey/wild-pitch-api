<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteMarker extends Model
{
    protected $fillable = [
        'route_id',
        'latitude',
        'longitude',
        'elevation',
    ];
    protected $hidden = [

    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'elevation' => 'float',
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }
}
