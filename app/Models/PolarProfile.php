<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolarProfile extends Model
{
    protected $fillable = [
        'user_id',
        'polar_user_id',
        'access_token',
        'token_expires_at',
        'first_name',
        'last_name',
        'linked_at',
        'unlinked_at',
    ];

    protected $casts = [
        'linked_at' => 'datetime',
        'unlinked_at' => 'datetime',
        'token_expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
