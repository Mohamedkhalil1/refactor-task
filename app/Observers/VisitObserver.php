<?php

namespace App\Observers;

use App\Models\Visit;

class VisitObserver
{
    /**
     * Handle the Visit "created" event.
     */
    public function created(Visit $visit): void
    {
        $visit->member->increment('visit_count');
    }

    /**
     * Handle the Visit "deleted" event.
     */
    public function deleted(Visit $visit): void
    {
        $visit->member->decrement('visit_count');
    }
}
