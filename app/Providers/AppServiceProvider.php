<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        RateLimiter::for('api', function (Request $request) {
            $key = $request->user()
                ? $request->user()->id
                : $request->ip();

            if (Cache::has('blocked_' . $key)) {
                return response()->json(['message' => 'Too many requests'], 429);
            }

            return [
                Limit::perMinute(6)->by($key),
                Limit::perMinutes(3, 180) // Too many requests for 3 minutes.
                    ->by($key)
                    ->response(function () use ($key) {
                        Cache::put('blocked_' . $key, true, now()->addMinutes(15)); // Block for 15 minutes.
                        return response()->json(['message' => 'Too many requests'], 429);
                    })
            ];
        });

        // Max 3 accounts per IP address.
        RateLimiter::for('register', function (Request $request) {
            $key = $request->ip();

            if (Cache::has('blocked_register_' . $key)) {
                return response()->json(['message' => 'Too many requests'], 429);
            }

            return Limit::perDay(3)
                ->by($key)
                ->response(function () use ($key) {
                    Cache::put('blocked_register_' . $key, true, now()->addDay());
                    return response()->json(['message' => 'Too many requests'], 429);
                });
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
