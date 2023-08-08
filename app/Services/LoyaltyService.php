<?php

namespace App\Services;

class LoyaltyService
{
    public function createLoyaltyPoints($visit, $settings, $request)
    {
        if ($settings->loyalty_model == 'first_model' && $request['receipt'] >= $settings->min_amount) {
            $visit->loyalty()->create([
                'points' => $request['receipt'] * $settings->factor,
            ]);
        } elseif ($settings->loyalty_model == 'second_model' && $request['receipt'] >= $settings->min_amount) {
            $visit->loyalty()->create([
                'points' => $settings->factor != 0 ? $request['receipt'] / $settings->factor : 0,
            ]);
        }
    }
}