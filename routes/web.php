<?php

use App\Http\Controllers\ReportBuilderController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::post('/get-filtered-fields', [ReportController::class, 'getFilteredFields']);
Route::get('/',[ReportController::class,'index'])->name('report.search');
Route::get('/listFilteredData/{id}',[ReportBuilderController::class,'listFilteredData'])->name('listFilteredData');
Route::get('/export/{id}',[ReportController::class,'export'])->name('exportExcel');
Route::get('field',[ReportController::class,'field'])->name('report.index');
Route::post('/get-filtered-record', [ReportController::class, 'getrecord'])->name('getrecord');
