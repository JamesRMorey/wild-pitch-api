<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'notes' => $this->notes,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'distance' => $this->distance,
            'status' => $this->status,
            'elevation_gain' => $this->elevation_gain,
            'elevation_loss' => $this->elevation_loss,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'markers' => $this->whenLoaded('markers') ? RouteMarkerResource::collection($this->markers) : [],
            'user' => $this->whenLoaded('user') ? new PublicUserResource($this->user) : null,
        ];
    }
}
