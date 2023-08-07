<?php

namespace App\Http\Controllers\Visits;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Visits\VisitRequest;
use App\Http\Resources\VisitResource;
use App\Models\Visit;
use App\Services\Visits\VisitService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VisitController extends Controller
{

    protected VisitService $visitService;

    public function __construct(VisitService $visitService)
    {
        $this->visitService = $visitService;
    }

    public function index(Request $request)
    {
        try {
            $visits = $this->visitService->getVisits($request);
            $data = VisitResource::collection($visits['data']);
            return ApiResponse::success(200, $data, 'Successful', $visits['pagination']);
        } catch (\Exception $exception) {
            return ApiResponse::error(400, ['message' => $exception->getMessage(), 'file' => $exception->getFile(), 'trace' => $exception->getTrace()]);
        }
    }


    public function show(Visit $visit)
    {
        try {
            $visit = $this->visitService->showVisit($visit);
            $data = VisitResource::make($visit);
            return ApiResponse::success(200, $data, 'Successful');
        } catch (\Exception $exception) {
            return ApiResponse::error(400, ['message' => $exception->getMessage(), 'file' => $exception->getFile(), 'trace' => $exception->getTrace()]);
        }
    }


    public function store(VisitRequest $request)
    {
        try {
            $visit = $this->visitService->createVisit($request->validated());
            $data = VisitResource::make($visit['data']);
            return ApiResponse::success(201, $data, 'Successful');
        } catch (\Exception $exception) {
            return ApiResponse::error(400, ['message' => $exception->getMessage(), 'file' => $exception->getFile(), 'trace' => $exception->getTrace()]);
        }
    }

    public function update(VisitRequest $request, Visit $visit)
    {
        try {
            $this->visitService->updateVisit($request->validated(), $visit);
            return ApiResponse::success(200, [], 'Update Successful');
        } catch (\Exception $exception) {
            return ApiResponse::error(400, ['message' => $exception->getMessage(), 'file' => $exception->getFile(), 'trace' => $exception->getTrace()]);
        }
    }

    public function destroy(Visit $visit)
    {
        try {
            $this->visitService->deleteVisit($visit);
            return ApiResponse::success(200, [], 'Delete Successful');
        } catch (\Exception $exception) {
            return ApiResponse::error(400, ['message' => $exception->getMessage(), 'file' => $exception->getFile(), 'trace' => $exception->getTrace()]);
        }
    }

}
