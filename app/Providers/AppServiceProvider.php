<?php

namespace App\Providers;

use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Models\Meeting;
use App\Models\User;
use App\Policies\BlogPolicy;
use App\Policies\PhoneListPolicy;
use App\Policies\UserPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

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

//        Model::preventLazyLoading(! $this->app->isProduction());
//        Model::shouldBeStrict();
        Vite::prefetch(3);
//        $this->configureCommands();
//        $this->configureModels();
//        $this->configureUrl();
        Paginator::useTailwind();


        // Gate for SuperAdmin
        Gate::before(function (User $user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });

        Gate::define('super-admin-only', function (User $user) {
            return $user->hasRole(UserRole::SUPER_ADMIN->value);
        });


        Gate::define('users-info', function (User $user){
            return $user->hasRole(UserRole::ADMIN->value);
        });

        // Gate For Side Bar
        Gate::define('side-bar-notifications',function (User $user){
            return $user->hasRole(UserRole::ADMIN->value);
        });


        Gate::define('admin-role',function (User $user){
           return $user->hasRole(UserRole::ADMIN->value);
        });


        Gate::define('lock-task', function (User $user) {
            return $user->permissions->contains('name', UserPermission::SCRIPTORIUM_PERMISSIONS->value);
        });


//        // Gate for only the bosses to see the refinery report
//        Gate::define('refinery-report', function (User $user) {
//            return $user->permissions->contains('name', UserPermission::TASK_REPORT_TABLE->value);
//        });


        // Gate for the scriptorium to handle only his meeting
        Gate::define('handle-own-meeting', function (User $user, Meeting $meeting) {
            $userInfo = $user->user_info;
            return $user->id === $meeting->scriptorium_id && $userInfo;
        });

        Gate::define('has-permission-and-role', function ($user, UserPermission|string $permission, UserRole|string $role = null) {
            $permissionName = $permission instanceof UserPermission ? $permission->value : $permission;
            $hasPermission = $user->permissions->contains('name', $permissionName);
            if ($role === null) {
                // Only permission check
                return $hasPermission;
            }
            $roleName = $role instanceof UserRole ? $role->value : $role;
            $hasRole = $user->roles->contains('name', $roleName);
            // Return true if user has either the permission OR the role
            return $hasPermission || $hasRole;
        });

        //gate definition for profile page
        Gate::define('profile-page', function (User $user) {
            return $user->id === auth()->id();
        });

    }
    /**
     * Configure the application's command.
     */
    public function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->isProduction(),
        );
    }
    /**
     * Configure the application's models.
     */
    public function configureModels(): void
    {
        Model::shouldBeStrict();
    }
    /**
     * Configure the application's URL.
     */
    public function configureUrl(): void
    {
        URL::forceScheme('https');
    }
}
