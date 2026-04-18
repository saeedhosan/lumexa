<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\Access;
use App\Enums\UserType;
use App\Listeners\AuthenticationActivitySubscriber;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
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
        $this->withGateAccess();
        Event::subscribe(AuthenticationActivitySubscriber::class);
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

    private function withGateAccess(): void
    {
        Gate::define(Access::super, fn (Authenticatable $user): bool => $user->isSuper());
        Gate::define(Access::admin, fn (Authenticatable $user): bool => $user->isAdmin());
        Gate::define(Access::user, fn (Authenticatable $user): bool => $user->type === UserType::user);
    }
}
