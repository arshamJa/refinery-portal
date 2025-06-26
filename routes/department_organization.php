<?php

use App\Http\Controllers\Admin\AddOrganizationController;
use App\Http\Controllers\Admin\OrgDepManagementController;
use App\Livewire\admin\DepartmentTable;
use App\Livewire\admin\OrganizationTable;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'adminMiddleware'])->group(function () {

    Route::get('department/organization/manage',[OrgDepManagementController::class, 'index'])
        ->name('organization.department.manage');

    Route::get('department/organization/connection', [OrgDepManagementController::class, 'departmentOrganizationConnection'])
        ->name('department.organization.connection');

    Route::post('/department/organization/connection', [OrgDepManagementController::class, 'store'])
        ->name('departments.organizations.store');


    Route::get('/addOrganization/{id}', [AddOrganizationController::class, 'index'])
        ->name('addOrganization');

    Route::post('/addOrganization/{id}', [AddOrganizationController::class, 'store'])
        ->name('addOrganization.store');

    Route::delete('/addOrganization/{id}/{organizations}',
        [AddOrganizationController::class, 'destroy'])
        ->name('addOrganization.delete');


    // Department Route
    Route::get('/departments', DepartmentTable::class)
        ->name('departments.index');

    // Organization Route
    Route::get('organizations', OrganizationTable::class)
        ->name('organizations');

    // this is for deleting the organization
    Route::delete('organization/{organizationId}',[AddOrganizationController::class,'deleteOrganization'])
        ->name('organization.destroy');




});
