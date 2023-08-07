<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->whenNotNull($this->phone),
            'last_visit_date' => $this->last_visit_date,
            'max_receipt' => (float) $this->max_receipt,
            'total_receipt' => (float) $this->total_receipt,
            'total_points' => (float) $this->total_points,
            'visits' => VisitResource::collection($this->whenLoaded('visits')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
