<?php

namespace App\Http\Resources\Members;

use App\Http\Resources\VisitResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name ?? '',
            'last_name' => $this->last_name ?? '',
            'email' => $this->email ?? '',
            'phone' => $this->phone ?? '',
            'last_visit_date' => $this->last_visit_date ?? '',
            'max_receipt' => (int)$this->max_receipt ?? 0,
            'total_receipt' => (int)$this->total_receipt ?? 0,
            'total_points' => (int)$this->total_points ?? 0,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
