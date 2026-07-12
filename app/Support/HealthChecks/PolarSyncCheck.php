<?php

namespace App\Support\HealthChecks;

use App\Models\PolarProfile;

use JTKalkman\LaravelHealth\HealthChecks\HealthCheck;
use JTKalkman\LaravelHealth\HealthCheckResult;
use JTKalkman\LaravelHealth\HealthCheckStatus;

class PolarSyncCheck extends HealthCheck
{
    protected string $name = 'Polar Sync';

    protected int $syncInterval = 86400; // 24 hours in seconds

    protected function performHealthCheck(): HealthCheckResult
    {
        $gracePeriod = now()->subMinutes(15);
        $errorThreshold = now()->subSeconds($this->syncInterval * 6);
        $warningThreshold = now()->subSeconds($this->syncInterval * 3);

        // Base query to fetch Polar profiles that have not been synced recently 
        // with a grace period of 15 minutes for new profiles.
        $baseQuery = fn () => PolarProfile::where('created_at', '<', $gracePeriod);

        // Errors: Older than six times the sync interval or more than five consecutive failed syncs.
        $erroredCount = (clone $baseQuery())
            ->where(function ($q) use ($errorThreshold) {
                $q->whereNull('last_synced_at')->orWhere('last_synced_at', '<', $errorThreshold);
            })
            ->orWhere('consecutive_sync_failures', '>', 5)
            ->count();

        if ($erroredCount > 0) {
            return new HealthCheckResult(
                name: $this->name,
                status: HealthCheckStatus::ERROR,
                value: $erroredCount,
                description: "{$erroredCount} Polar profile" . ($erroredCount === 1 ? '' : 's') . " with sync problems.",
            );
        }

        // Warnings: Older than three times the sync interval or more than two consecutive failed syncs.
        $warnedCount = (clone $baseQuery())
            ->where(function ($q) use ($warningThreshold) {
                $q->whereNull('last_synced_at')->orWhere('last_synced_at', '<', $warningThreshold);
            })
            ->orWhere('consecutive_sync_failures', '>', 2)
            ->count();

        if ($warnedCount > 0) {
            return new HealthCheckResult(
                name: $this->name,
                status: HealthCheckStatus::WARNING,
                value: $warnedCount,
                description: "{$warnedCount} Polar profile" . ($warnedCount === 1 ? '' : 's') . " with sync problems.",
            );
        }
    
        return new HealthCheckResult(
            name: $this->name,
            status: HealthCheckStatus::OK,
            value: 0,
            description: 'No sync problems detected for Polar profiles.',
        );
    }
}
