<?php

namespace App\Models\Views;

use App\Models\loyalty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VisitView extends Model
{
    use HasFactory;

    protected $table = "visits_view";
    protected $primaryKey = "id";

    public function loyalties(): hasMany
    {
        return $this->hasMany(Loyalty::class , 'visit_id' , 'id');
    }

}
