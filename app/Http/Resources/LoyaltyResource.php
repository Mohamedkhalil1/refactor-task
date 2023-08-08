<?php

namespace App\Http\Resources;

use App\Models\loyalty;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin loyalty
 */
class LoyaltyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'points' => $this->points,
            'visit' => new VisitResource($this->whenLoaded('visit')),
            'member' => new MemberResource($this->whenLoaded('member')),
        ];
    }
}
