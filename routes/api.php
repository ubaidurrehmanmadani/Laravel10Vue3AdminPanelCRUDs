<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('companies', \App\Http\Controllers\CompaniesController::class);
Route::get('get_all_companies', [App\Http\Controllers\CompaniesController::class, 'getAll'])->name('getAllCompanies');

Route::resource('employees', \App\Http\Controllers\EmployeesController::class);
Route::get('get_all_employees', [App\Http\Controllers\EmployeesController::class, 'getAll'])->name('getAllEmployees');

