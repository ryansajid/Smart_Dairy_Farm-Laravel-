<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Health Management Routes
Route::prefix('health')->name('health.')->group(function () {
    // Disease Routes
    Route::resource('diseases', \App\Http\Controllers\Health\DiseaseController::class);
    Route::get('diseases/statistics', [\App\Http\Controllers\Health\DiseaseController::class, 'statistics'])->name('diseases.statistics');
    
    // Disease Case Routes
    Route::resource('disease-cases', \App\Http\Controllers\Health\DiseaseCaseController::class);
    Route::post('disease-cases/{id}/outcome', [\App\Http\Controllers\Health\DiseaseCaseController::class, 'recordOutcome'])->name('disease-cases.outcome');
    Route::get('disease-cases/statistics', [\App\Http\Controllers\Health\DiseaseCaseController::class, 'statistics'])->name('disease-cases.statistics');
    
    // Medicine Routes
    Route::resource('medicines', \App\Http\Controllers\Health\MedicineController::class);
    
    // Treatment Routes
    Route::resource('treatments', \App\Http\Controllers\Health\TreatmentController::class);
    
    // Vaccine Routes
    Route::resource('vaccines', \App\Http\Controllers\Health\VaccineController::class);
    
    // Withdrawal Routes
    Route::resource('withdrawals', \App\Http\Controllers\Health\WithdrawalController::class);
});
