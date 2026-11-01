<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\EstimateHeartRateRequest;
use App\Http\Requests\Settings\PhysicalDataUpdateRequest;
use App\Http\Resources\PhysicalDataResource;
use App\Models\PhysicalData;
use App\Support\Services\HeartRateEstimationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PhysicalDataController extends Controller
{
    /**
     * Show the user's training settings page.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('settings/PhysicalData', [
            'physicalData' => new PhysicalDataResource(
                PhysicalData::firstOrNew(['user_id' => $user->id])
            ),
        ]);
    }

    /**
     * Update the user's training settings.
     */
    public function update(PhysicalDataUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Triggers PhysicalDataObserver.
        $physicalData = PhysicalData::firstOrNew(['user_id' => $user->id]);
        $physicalData->fill($data)->save();

        return to_route('physical-data.edit');
    }

    public function estimateHeartRate(EstimateHeartRateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $age = Carbon::parse($data['date_of_birth'])->age;
        $estimates = app(HeartRateEstimationService::class)->estimate($age);

        return response()->json($estimates);
    }
}
