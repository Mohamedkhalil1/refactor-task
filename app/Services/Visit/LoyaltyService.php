<?php

namespace App\Services\Visit;

use App\Models\Setting;
use App\Models\Visit;
use LogicException;

class LoyaltyService
{
    private Visit $visit;

    private Setting $setting;

    public function setVisit(Visit $visit): self
    {
        $this->visit = $visit;
        $cashier = $this->visit->cashier;

        throw_if(! $cashier->settings, new LogicException('No settings found for cashier', 404));

        $this->setting = $cashier->settings;

        return $this;
    }

    public function handelCreation()
    {
        if ($this->setting->factor == 0) {
            throw new LogicException('Factor value can not be zero', 404);
        }

        if ($this->setting->loyalty_model == 'first_model' && ($this->visit->receipt >= $this->setting->min_points)) {
            return $this->createFirstModelLoyalty();
        } elseif ($this->setting->loyalty_model == 'second_model' && $this->visit->receipt < $this->setting->min_points) {
            return $this->createSecondModelLoyalty();
        }

        throw new LogicException('No loyalty model found', 404);
    }

    private function createFirstModelLoyalty()
    {
        return $this->visit->loyalty()->create([
            'points' => $this->visit->receipt * $this->setting->factor,
        ]);
    }

    // second model
    private function createSecondModelLoyalty()
    {
        return $this->visit->loyalty()->create([
            'points' => $this->visit->receipt / $this->setting->factor,
        ]);
    }

    public function deleteLoyalty()
    {
        $this->visit->loyalty()->delete();

        return $this;
    }
}
