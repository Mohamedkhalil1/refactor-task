<?php

namespace App\Repositories\Visits\Contracts;

interface VisitRepositoryInterface
{
    public function getVisits($filters, $perPage);


    public function createVisit($data);

    public function createVisitLoyalty($visit, $data);

    public function updateVisit($data, $visit);

    public function deleteVisit($visit);

    public function deleteVisitLoyalty($visit);
}
