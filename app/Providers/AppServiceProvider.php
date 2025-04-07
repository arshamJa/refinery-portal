<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\BlogPolicy;
use App\Policies\DepartmentOrganizationPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\UserPolicy;
use App\Policies\PhoneListPolicy;
use App\UserPermission;
use App\UserRole;
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
            return $user->hasRole('super-admin') ? true : null;
        });




        // Gate For Side Bar
        Gate::define('side-bar-notifications',function (User $user){
            return $user->hasRole(UserRole::SUPER_ADMIN->value) || $user->hasRole(UserRole::ADMIN->value);
        });






        //gate definition for profile page
        Gate::define('view-profile-page',[ProfilePolicy::class,'view']);
        Gate::define('update-profile-page',[ProfilePolicy::class,'update']);


        // Gate for creating meeting
        Gate::define('create-meeting',function (User $user){
            return $user->userHasPermission(UserPermission::CREATE_MEETING->value);
        });






        //gate definition for phone list via phoneListPolicy...
        Gate::define('view-phone-list', [PhoneListPolicy::class, 'view']);
        Gate::define('create-phone-list', [PhoneListPolicy::class, 'create']);
        Gate::define('update-phone-list', [PhoneListPolicy::class, 'update']);

        //gate definition for Blog
        Gate::define('create-blog',[BlogPolicy::class,'create']);
        Gate::define('update-blog',[BlogPolicy::class,'update']);
        Gate::define('delete-blog',[BlogPolicy::class,'delete']);

        //gate definition for users table - employee access table
        Gate::define('view-any',[UserPolicy::class,'viewAny']);
        Gate::define('view-user',[UserPolicy::class,'view']);
        Gate::define('create-user',[UserPolicy::class,'create']);
        Gate::define('update-user',[UserPolicy::class,'update']);
        Gate::define('delete-user',[UserPolicy::class,'delete']);

        //gate definition for department and organization
//        Gate::define('view-department-organization',[DepartmentOrganizationPolicy::class,'view']);
//        Gate::define('create-department-organization',[DepartmentOrganizationPolicy::class,'create']);
//        Gate::define('update-department-organization',[DepartmentOrganizationPolicy::class,'update']);
//        Gate::define('delete-department-organization',[DepartmentOrganizationPolicy::class,'delete']);


        Gate::define('admin-dashboard',function (User $user){
           return $user->role === 'admin';
        });
        Gate::define('operator-dashboard',function (User $user){
            return $user->role === 'operator';
        });
        Gate::define('employee-dashboard',function (User $user){
            return $user->role === 'employee';
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
