<?php

namespace App\Traits;

use App\Models\Member;
use Illuminate\Database\Eloquent\Builder;

trait HasSearch
{
    public function scopeSearch(Builder $query, $search, $columns)
    {
        $searchQuery = trim($search);

        return $query
            ->when(! empty($searchQuery), function ($query) use ($columns, $searchQuery) {
                $query->where(function ($query) use ($columns, $searchQuery) {
                    foreach ($columns as $column) {
                        if ($query->getModel()::class == Member::class && $column == 'name') {
                            $query->searchByMemberName($searchQuery);

                            continue;
                        }

                        $query->orWhere($column, 'like', '%'.$searchQuery.'%');
                    }
                });
            });
    }

    public function scopeSearchByMemberName($query, $search)
    {
        $searchQuery = explode(' ', $search);

        $first_name = $searchQuery[0];
        $last_name = $searchQuery[1] ?? null;

        return $query
            ->when(! empty($first_name), function ($query) use ($first_name) {
                $query->where('first_name', 'like', '%'.$first_name.'%');
            })
            ->when(! empty($last_name), function ($query) use ($last_name) {
                $query->where('last_name', 'like', '%'.$last_name.'%');
            });
    }
}
