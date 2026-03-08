<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\V1\TrainingSessionFilter;
use App\Http\Resources\Api\V1\TrainingSessionResource;
use App\Models\TrainingSession;
use App\Traits\Api\V1\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TrainingSessionController extends Controller
{
    use ApiResponses;

    public function index(TrainingSessionFilter $filters): JsonResponse
    {
        $user = Auth::user();

        $trainingSessions = TrainingSession::filter($filters)
            ->where('user_id', $user->id)
            ->with('dataSource')
            ->with('device')
            ->with('trainingSummary')
            ->with('heartRateZones')
            ->paginate(10);
        $collection = TrainingSessionResource::collection($trainingSessions);

        return $this->paginated(
            'Training sessions retrieved',
            $collection,
            $trainingSessions,
            200
        );
    }

    public function show(TrainingSession $trainingSession)
    {
        // abort_if($trainingSession->user_id !== Auth::id(), 404);
        $this->authorize('view', $trainingSession);

        $trainingSession->load(['dataSource', 'device', 'trainingSummary', 'heartRateZones']);
        $data = new TrainingSessionResource($trainingSession);

        return $this->success(
            'Training session retrieved',
            $data->resolve(),
            200
        );
    }
}
