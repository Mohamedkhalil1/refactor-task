<?php

namespace App\Http\Resources;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Visit
 */
class VisitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'receipt'    => $this->receipt,
            'loyalty'    => LoyaltyResource::make($this->whenLoaded('loyalty')),
            'member'     => MemberResource::make($this->whenLoaded('member')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'points'    => $this->points,
            'cashier'   => $this->cashier,
            'member'   => $this->member,
        ];
    }
}
