<?php

use App\Http\Controllers\Admin\AddOrganizationController;
use App\Http\Controllers\Admin\OrgDepManagementController;
use App\Http\Controllers\DepartmentImportExportController;
use App\Http\Controllers\EmployeesOrganizationController;
use App\Http\Controllers\OrganizationImportController;
use App\Livewire\admin\DepartmentTable;
use App\Livewire\admin\OrganizationTable;
use App\Livewire\employee\EmployeesOrganization;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'sanitizeInputs'])->group(function () {

    Route::middleware('adminMiddleware')->group(function () {

        Route::get('department/organization/manage', [OrgDepManagementController::class, 'index'])
            ->name('organization.department.manage');


        Route::post('department/organization/connection', [OrgDepManagementController::class, 'store'])
            ->name('departments.organizations.store');

        Route::get('/addOrganization/{id}', [AddOrganizationController::class, 'index'])
            ->name('addOrganization');

        Route::post('/addOrganization/{id}', [AddOrganizationController::class, 'store'])
            ->name('addOrganization.store');

        Route::delete('/addOrganization/{id}/{organizations}',
            [AddOrganizationController::class, 'destroy'])
            ->name('addOrganization.delete');

        // Department Route
        Route::get('departments', DepartmentTable::class)
            ->name('departments.index');

        Route::delete('department/{departmentId}', [AddOrganizationController::class, 'deleteDepartment'])
            ->name('department.destroy');

        // Organization Route
        Route::get('organizations', OrganizationTable::class)
            ->name('organizations');

        // this is for deleting the organization
        Route::delete('organization/{organizationId}', [AddOrganizationController::class, 'deleteOrganization'])
            ->name('organization.destroy');

        // import & export of department & organization tables
        Route::post('/organization_import', [OrganizationImportController::class, 'import']);
        Route::get('/organization_export', [OrganizationImportController::class, 'export'])
            ->name('organization.export');
        Route::post('/department_import', [DepartmentImportExportController::class, 'import']);
        Route::get('/department_export', [DepartmentImportExportController::class, 'export'])
            ->name('department.export');
    });

    Route::get('employee/organization', [EmployeesOrganizationController::class, 'index'])
        ->name('employee.organization');

});
