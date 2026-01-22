<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteSearchResultResource extends JsonResource
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
            'notes' => $this->notes,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'distance' => $this->distance,
            'elevation_gain' => $this->elevation_gain,
            'elevation_loss' => $this->elevation_loss,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'user' => $this->whenLoaded('user') ? new PublicUserResource($this->user) : null,
        ];
    }
}
