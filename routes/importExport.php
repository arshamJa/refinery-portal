<?php


use App\Http\Controllers\DepartmentImportExportController;
use App\Http\Controllers\OrganizationImportController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::post('/organization_import', [OrganizationImportController::class, 'import']);
    Route::get('/organization_export', [OrganizationImportController::class, 'export'])->name('organization.export');
    Route::post('/department_import', [DepartmentImportExportController::class, 'import']);
    Route::get('/department_export', [DepartmentImportExportController::class, 'export'])->name('department.export');
});
