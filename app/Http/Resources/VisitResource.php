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
            'id' => $this->id,
            'receipt' => $this->receipt,
            'loyalty_points' => $this->whenLoaded('loyalty', function () {
                return $this->loyalty->points;
            }),
            'member_name' => $this->whenLoaded('member', function () {
                return $this->member->name;
            }),
            'cashier_name' => $this->whenLoaded('cashier', function () {
                return $this->cashier->name;
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
