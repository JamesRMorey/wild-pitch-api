<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PointOfInterestResource extends JsonResource
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
            'status' => $this->status ?? 'PRIVATE',
            'elevation' => $this->elevation,
            'published_at' => $this->published_at ? Carbon::parse($this->published_at)->format('Y-m-d H:i:s') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'user' => $this->whenLoaded('user') ? new PublicUserResource($this->user) : null,
        ];
    }
}
