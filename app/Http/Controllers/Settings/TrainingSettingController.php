<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\TrainingSettingsUpdateRequest;
use App\Models\TrainingSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TrainingSettingController extends Controller
{
    /**
     * Show the user's training settings page.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        $trainingSettings = TrainingSettings::where('user_id', $user->id)
            ->first();

        return Inertia::render('settings/TrainingSettings', [
            'trainingSettings' => $trainingSettings
        ]);
    }

    /**
     * Update the user's training settings.
     */
    public function update(TrainingSettingsUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        TrainingSettings::upsert([[
                'user_id'                 => $user->id,
                'date_of_birth'           => $data['date_of_birth'],
                'weight_kg'               => $data['weight_kg'],
                'height_cm'               => $data['height_cm'],
                'resting_heart_rate'      => $data['resting_heart_rate'],
                'max_heart_rate'          => $data['max_heart_rate'],
                'aerobic_threshold_bpm'   => $data['aerobic_threshold_bpm'],
                'anaerobic_threshold_bpm' => $data['anaerobic_threshold_bpm'],
                'mas_seconds_per_km'      => $data['mas_seconds_per_km'],
                'map_watts'               => $data['map_watts'],
                'ftp_watts'               => $data['ftp_watts']
            ]], 
            uniqueBy: ['user_id'], 
            update: [
                'user_id', 'date_of_birth', 'weight_kg',
                'height_cm', 'resting_heart_rate', 'max_heart_rate',          
                'aerobic_threshold_bpm', 'anaerobic_threshold_bpm', 'mas_seconds_per_km',     
                'map_watts', 'ftp_watts'
            ]
        );

        return to_route('training-settings.edit');
    }
}
