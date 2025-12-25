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

});
Auth::routes();

