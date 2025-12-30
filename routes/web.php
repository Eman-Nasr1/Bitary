<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\Animal\AnimalController;
use App\Modules\Seller\SellerController;
use App\Modules\Medicine\MedicineController;
use App\Modules\Category\CategoryController;
use App\Modules\AnimalType\AnimalTypeController;
use App\Modules\City\CityController;


Route::get('/', function () {
    return view('dashboard.index');
})->middleware('auth:admin');




Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::resource('animals', AnimalController::class)->middleware('auth:admin');
    Route::resource('animal_types', AnimalTypeController::class)->middleware('auth:admin');
    Route::resource('categories', CategoryController::class)->middleware('auth:admin');
    Route::resource('sellers', SellerController::class)->middleware('auth:admin');
    Route::resource('medicines', MedicineController::class)->middleware('auth:admin');
    Route::resource('cities', CityController::class)->middleware('auth:admin');
    Route::resource('users', \App\Modules\User\AdminUserController::class)->middleware('auth:admin');
    Route::post('users/{user}/toggle-provider', [\App\Modules\User\AdminUserController::class, 'toggleProviderStatus'])->name('users.toggle-provider')->middleware('auth:admin');
    Route::post('users/{user}/toggle-status', [\App\Modules\User\AdminUserController::class, 'toggleUserStatus'])->name('users.toggle-status')->middleware('auth:admin');
    
    // Provider Requests
    Route::get('provider-requests', [\App\Modules\ProviderRequest\AdminProviderRequestController::class, 'index'])->name('provider-requests.index')->middleware('auth:admin');
    Route::get('provider-requests/{id}', [\App\Modules\ProviderRequest\AdminProviderRequestController::class, 'show'])->name('provider-requests.show')->middleware('auth:admin');
    Route::post('provider-requests/{id}/approve', [\App\Modules\ProviderRequest\AdminProviderRequestController::class, 'approve'])->name('provider-requests.approve')->middleware('auth:admin');
    Route::post('provider-requests/{id}/reject', [\App\Modules\ProviderRequest\AdminProviderRequestController::class, 'reject'])->name('provider-requests.reject')->middleware('auth:admin');
});
Auth::routes();

