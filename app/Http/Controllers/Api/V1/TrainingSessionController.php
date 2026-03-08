<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TrainingSessionResource;
use App\Models\TrainingSession;
use App\Traits\Api\V1\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TrainingSessionController extends Controller
{
    use ApiResponses;

    public function index(): JsonResponse
    {
        $user = Auth::user();

        $trainingSessions = TrainingSession::where('user_id', $user->id)
            ->with('dataSource')
            ->with('device')
            ->with('trainingSummary')
            ->with('heartRateZones')
            ->paginate();
        $collection = TrainingSessionResource::collection($trainingSessions);

        return $this->success(
            'Training sessions retrieved',
            $collection->resolve(),
            200
        );
    }
}
