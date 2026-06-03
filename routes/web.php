<?php

use App\Http\Controllers\ContractController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeSearchController;
use App\Http\Controllers\EmployeeSummaryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('employees.index');
});

Route::resource('employees', EmployeeController::class);

Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
Route::post('/employees/{employee}/contracts/renew', [ContractController::class, 'renew'])
    ->name('contracts.renew');

Route::get('/employees-search', EmployeeSearchController::class)->name('employees.search');
Route::get('/employee-summary', EmployeeSummaryController::class)->name('employees.summary');
