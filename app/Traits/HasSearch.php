<?php

namespace App\Traits;

use App\Models\Member;
use Illuminate\Database\Eloquent\Builder;

trait HasSearch
{
    public function scopeSearch(Builder $query, $search = null, array $columns = []): Builder
    {
        $searchQuery = trim($search);

        if (empty($searchQuery)) {
            return $query;
        }

        return $query->where(function ($query) use ($columns, $searchQuery) {
            foreach ($columns as $column) {
                $this->addColumnToSearch($query, $column, $searchQuery);
            }
        });
    }

    private function addColumnToSearch($query, string $column, string $searchQuery): void
    {
        if ($this->isMemberNameColumn($query, $column)) {
            $query->searchByMemberName($searchQuery);
        } else {
            $query->orWhere($column, 'like', '%'.$searchQuery.'%');
        }
    }

    private function isMemberNameColumn($query, string $column): bool
    {
        return $this->modelIsMember($query) && $this->isColumnName($column);
    }

    private function modelIsMember($query): bool
    {
        return $query->getModel() instanceof Member;
    }

    private function isColumnName(string $column): bool
    {
        return $column === 'name';
    }

    public function scopeSearchByMemberName($query, string $search): Builder
    {
        [$first_name, $last_name] = $this->splitName($search);

        $this->addFirstNameFilter($query, $first_name);
        $this->addLastNameFilter($query, $last_name);

        return $query;
    }

    private function splitName(string $search): array
    {
        $searchQuery = explode(' ', $search, 2);

        return [$searchQuery[0], $searchQuery[1] ?? null];
    }

    private function addFirstNameFilter($query, ?string $first_name): void
    {
        if (! empty($first_name)) {
            $query->where('first_name', 'like', '%'.$first_name.'%');
        }
    }

    private function addLastNameFilter($query, ?string $last_name): void
    {
        if (! empty($last_name)) {
            $query->where('last_name', 'like', '%'.$last_name.'%');
        }
    }
}
