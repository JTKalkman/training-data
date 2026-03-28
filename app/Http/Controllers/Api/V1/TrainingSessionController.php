<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\V1\TrainingSessionFilter;
use App\Http\Requests\Api\V1\TrainingSessionUpdateRequest;
use App\Http\Resources\Api\V1\TrainingSessionResource;
use App\Models\TrainingSession;
use App\Support\DTO\Api\V1\PaginationMeta;
use App\Traits\Api\V1\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

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
            $collection->resolve(),
            PaginationMeta::fromPaginator($trainingSessions),
            200
        );
    }

    public function show(TrainingSession $trainingSession): JsonResponse
    {
        $this->authorize('view', $trainingSession);

        $trainingSession->load(['dataSource', 'device', 'trainingSummary', 'heartRateZones']);
        $data = new TrainingSessionResource($trainingSession);

        return $this->success(
            'Training session retrieved',
            $data->resolve(),
            200
        );
    }

    public function sampleData(TrainingSession $trainingSession): JsonResponse|Response
    {
        $this->authorize('view', $trainingSession);

        if (Storage::exists($trainingSession->sampleDataPath())) {
            return response(Storage::get($trainingSession->sampleDataPath()), 200)
                ->header('Content-Type', 'application/json');
        }

        return $this->error('Not found', [], 404);
    }

    public function routeData(TrainingSession $trainingSession): JsonResponse|Response
    {
        $this->authorize('view', $trainingSession);

        if (Storage::exists($trainingSession->routeDataPath())) {
            return response(Storage::get($trainingSession->routeDataPath()), 200)
                ->header('Content-Type', 'application/json');
        }

        return $this->error('Not found', [], 404);
    }

    public function update(TrainingSession $trainingSession, TrainingSessionUpdateRequest $request) //: JsonResponse|Response
    {
        $this->authorize('update', $trainingSession);

        $data = [];

        if ($request->has('rating')) {
            $data['rating'] = (int) $request->rating;
        }

        if ($request->has('ratingLabel')) {
            $map = array_flip(TrainingSession::RATING_MAP);
            $data['rating'] = $map[$request->ratingLabel] ?? null;
        }

        if ($request->has('notes')) {
            $data['notes'] = $request->notes;
        }

        $trainingSession->update($data);

        $resource = new TrainingSessionResource($trainingSession);

        return $this->success(
            'Training session updated',
            $resource->resolve(),
            200
        );
    }
}
