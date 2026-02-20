<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class AccountController extends Controller
{
    public function settings()
    {
        return Inertia::render('Account/Settings');
    }
}
