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
            'server_id' => $this->id,
            'name' => $this->name,
            'user_id' => $this->user_id,
            'notes' => $this->notes,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'distance' => $this->distance,
            'status' => $this->status ?? 'PRIVATE',
            'elevation_gain' => $this->elevation_gain,
            'elevation_loss' => $this->elevation_loss,
            'published_at' => $this->published_at ? $this->published_at->format('Y-m-d H:i:s') : null,
            'type' => $this->type ?? 'UNKNOWN',
            'difficulty' => $this->difficulty ?? 'UNKNOWN',
            'creation_type' => $this->creation_type ?? 'CREATED',
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'markers' => $this->whenLoaded('markers') ? RouteMarkerResource::collection($this->markers) : [],
            'user' => $this->whenLoaded('user') ? new PublicUserResource($this->user) : null,
        ];
    }
}
