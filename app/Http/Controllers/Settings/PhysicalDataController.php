<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\PhysicalDataUpdateRequest;
use App\Models\PhysicalData;
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
        $physicalData = PhysicalData::where('user_id', $user->id)
            ->first();

        return Inertia::render('settings/PhysicalData', [
            'physicalData' => $physicalData
        ]);
    }

    /**
     * Update the user's training settings.
     */
    public function update(PhysicalDataUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        PhysicalData::upsert([[
                'user_id'                 => $user->id,
                'date_of_birth'           => $data['date_of_birth'] ?? null,
                'weight_kg'               => $data['weight_kg'] ?? null,
                'height_cm'               => $data['height_cm'] ?? null,
                'sex'                     => $data['sex'] ?? null,
                'resting_heargt_rate'     => $data['resting_heart_rate'] ?? null,
                'max_heart_rate'          => $data['max_heart_rate'] ?? null,
                'aerobic_threshold_bpm'   => $data['aerobic_threshold_bpm'] ?? null,
                'anaerobic_threshold_bpm' => $data['anaerobic_threshold_bpm'] ?? null,
                'mas_seconds_per_km'      => $data['mas_seconds_per_km'] ?? null,
                'vo2_max'                 => $data['vo2_max'] ?? null,
                'map_watts'               => $data['map_watts'] ?? null,
                'ftp_watts'               => $data['ftp_watts'] ?? null
            ]], 
            uniqueBy: ['user_id'], 
            update: [
                'user_id', 'date_of_birth', 'weight_kg', 'sex', 'height_cm', 
                'resting_heart_rate', 'max_heart_rate', 'aerobic_threshold_bpm', 
                'anaerobic_threshold_bpm', 'mas_seconds_per_km', 'vo2_max', 
                'map_watts', 'ftp_watts'
            ]
        );

        return to_route('physical-data.edit');
    }
}
