<?php

use App\Http\Controllers\Admin\AddOrganizationController;
use App\Http\Controllers\Admin\DepOrgConnectionController;
use App\Http\Controllers\Admin\OrgDepManagementController;
use App\Livewire\admin\DepartmentTable;
use App\Livewire\admin\OrganizationTable;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'adminMiddleware'])->group(function () {

    Route::get('department/organization/manage',[OrgDepManagementController::class, 'index'])
        ->name('organization.department.manage');
//        ->can('view-department-organization');

    Route::get('department/organization/connection', [OrgDepManagementController::class, 'departmentOrganizationConnection'])
        ->name('department.organization.connection');
//        ->can('create-department-organization');

    Route::post('/department/organization/connection', [OrgDepManagementController::class, 'store'])
        ->name('departments.organizations.store');

//    Route::get('/departments/organizations', organizationManage::class)
//        ->name('organization.department.manage')
//        ->can('view-department-organization');

//    Route::get('/department/organization/connection', DepOrgConnection::class)
//        ->name('department-organization-connection');

//    Route::get('/dep/org', [DepOrgConnectionController::class, 'create'])
//        ->name('departments.organizations')
//        ->can('create-department-organization');
//    Route::post('/departments/users', [DepOrgConnectionController::class, 'storeDepUser'])
//        ->name('departments.users');

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
//        ->can('view-department-organization');

    // Organization Route
    Route::get('organizations', OrganizationTable::class)
        ->name('organizations');
//        ->can('view-department-organization');




});
