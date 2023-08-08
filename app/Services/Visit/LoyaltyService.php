<?php

namespace App\Services\Visit;

use App\Enums\LoyaltyModel;
use App\Models\Setting;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Model;
use LogicException;
use Throwable;

class LoyaltyService
{
    private Visit $visit;

    private Setting $setting;

    /**
     * @return $this
     *
     * @throws Throwable
     */
    public function setVisit(Visit $visit): self
    {
        $this->visit = $visit;
        $cashier = $this->visit->cashier;

        throw_if(! $cashier->settings, new LogicException('No settings found for cashier', 400));

        $this->setting = $cashier->settings;

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function handelCreation(): Model
    {
        throw_if($this->setting->factor <= 0, new LogicException('Factor must be greater than 0', 400));

        if ($this->isValidForFirstModel()) {
            return $this->createFirstModelLoyalty();
        } elseif ($this->isValidForSecondModel()) {
            return $this->createSecondModelLoyalty();
        }

        throw new LogicException('No loyalty model found', 400);
    }

    public function isValidForFirstModel(): bool
    {
        return $this->setting->loyalty_model == LoyaltyModel::FIRST_MODEL && ($this->visit->receipt >= $this->setting->min_points);
    }

    public function isValidForSecondModel(): bool
    {
        return $this->setting->loyalty_model == LoyaltyModel::SECOND_MODEL && $this->visit->receipt < $this->setting->min_points;
    }

    private function createFirstModelLoyalty(): Model
    {
        return $this->visit->loyalty()->create([
            'points' => $this->visit->receipt * $this->setting->factor,
        ]);
    }

    // second model

    private function createSecondModelLoyalty(): Model
    {
        return $this->visit->loyalty()->create([
            'points' => $this->visit->receipt / $this->setting->factor,
        ]);
    }

    /**
     * @return $this
     */
    public function deleteLoyalty(): self
    {
        $this->visit->loyalty()->delete();

        return $this;
    }
}
