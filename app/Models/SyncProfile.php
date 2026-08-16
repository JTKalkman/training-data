<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class SyncProfile extends Model
{
    protected $fillable = [
        'user_id',
        'linked_at',
        'unlinked_at',
        'last_synced_at',
        'last_sync_attempted_at',
        'last_sync_error',
        'next_sync_at',
        'locked_at',
        'consecutive_sync_failures',
    ];

    protected $casts = [
        'linked_at' => 'datetime',
        'unlinked_at' => 'datetime',
        'last_synced_at' => 'datetime',
        'last_sync_attempted_at' => 'datetime',
        'next_sync_at' => 'datetime',
        'locked_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSyncStatusAttribute(): string
    {
        if ($this->unlinked_at !== null) {
            return 'removed';
        }

        if ($this->consecutive_sync_failures >= 3) {
            return 'error';
        }

        if ($this->consecutive_sync_failures >= 1) {
            return 'warning';
        }

        if ($this->last_synced_at === null) {
            return $this->last_sync_attempted_at === null
                ? 'pending'   // never even attempted yet
                : 'pending';  // attempted, in progress / awaiting first success — same UI treatment for now
        }

        return 'ok';
    }
}
