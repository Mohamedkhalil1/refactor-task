<?php

namespace App\Http\Resources;

use App\Http\Resources\Members\MemberResource;
use App\Models\Visit;
use Carbon\Carbon;
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
            'receipt' => (int)$this->receipt,
            'member_name' => $this->member_full_name ?? '' ,
            'cashier_name' => $this->cashier_name ?? '' ,
            'max_loyalty_points' =>(int)$this->max_points ?? 0 ,
            'loyalties' => $this->whenLoaded('loyalties', function () {
                return $this->loyalties->map(function ($loyalty) {
                    return [
                        'id' => $loyalty['id'],
                        'points' => (int)$loyalty['points'],
                    ];
                });
            }),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
