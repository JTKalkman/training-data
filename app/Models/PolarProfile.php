<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolarProfile extends Model
{
    protected $fillable = [
        'user_id',
        'polar_user_id',
        'access_token',
        'token_expires_at',
        'linked_at',
        'unlinked_at',
    ];
}
