<?php

namespace App\Services;

use App\Models\Loyalty;
use App\Models\Member;

class LoyaltyPointsCalculator
{
    private $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    public function calculateTotalPoints()
    {
        $totalPoints = 0;

        // get all visits by the member
        $visits = $this->member->visits;

        // loop through each visit
        foreach ($visits as $visit) {

            // get loyalty points associated with the visit
            $loyaltyPoints = $visit->loyalty->points;

            // get cashier settings for the visit
            $cashierSettings = $visit->cashier->settings;

            // calculate points based on the cashier's settings
            if ($cashierSettings->loyalty_model == 'first_model') {
                $points = $cashierSettings->factor * $loyaltyPoints;
            } elseif ($cashierSettings->loyalty_model == 'second_model') {
                $points = max($cashierSettings->factor * $loyaltyPoints, $cashierSettings->min_points);
            }

            // add calculated points to total
            $totalPoints += $points;
        }

        return $totalPoints;
    }
}
