<?php

namespace App\Providers;

use App\Models\Document;
use App\Models\Employee;
use App\Models\User;
use App\Policies\DocumentPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Employee::class, EmployeePolicy::class);
        Gate::policy(Document::class, DocumentPolicy::class);
    }
}
