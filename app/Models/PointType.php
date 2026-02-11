<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PointType extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'colour'
    ];
    protected $hidden = [

    ];

    protected function casts(): array
    {
        return [
        ];
    }

    public function pointsOfInterest(): BelongsToMany
    {
        return $this->belongsToMany(PointOfInterest::class);
    }
}
