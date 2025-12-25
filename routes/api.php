<?php


use Illuminate\Http\Request;
use App\Modules\User\UserController;
use Illuminate\Support\Facades\Route;
use App\Modules\Rating\RatingController;
use App\Modules\Animal\ApiAnimalController;



use App\Modules\Favorite\FavoriteController;
use App\Modules\Location\LocationController;
use App\Modules\Developer\ProjectsController;
use App\Modules\Category\ApiCategoryController;
use App\Modules\Medicine\ApiMedicineController;
use App\Http\Controllers\Auth\SocialApiController;
use App\Modules\Developer\ProjectExportController;

Route::get('user/{provider}/redirect', [SocialApiController::class, 'redirect'])
    ->whereIn('provider',['google','facebook']);

Route::get('user/{provider}/callback', [SocialApiController::class, 'callback'])
    ->whereIn('provider',['google','facebook']);


Route::prefix('user')->group(function () {
    Route::post('register', [UserController::class, 'createUser']);
    Route::post('verify', [UserController::class, 'verify']);
    Route::post('resend-otp', [UserController::class, 'resendOtp']);
    Route::post('login', [UserController::class, 'login']);


    Route::post('password/forgot', [UserController::class, 'sendResetOtp']);
    Route::post('password/verify-otp', [UserController::class, 'verifyOtp']);
    Route::post('password/reset', [UserController::class, 'resetPassword']);

});
Route::middleware('auth:sanctum')->post('logout', [UserController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/token/check', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Token is valid',
        'user' => auth()->user()
    ]);
});


//animals
Route::middleware([\App\Http\Middleware\SetLocaleLang::class])->get('animals/{id}', [ApiAnimalController::class, 'show']);
Route::middleware([\App\Http\Middleware\SetLocaleLang::class])->get('animals', [ApiAnimalController::class, 'listAllAnimals']);


//category

Route::middleware([\App\Http\Middleware\SetLocaleLang::class])->get('categories', [ApiCategoryController::class, 'listAllCategories']);

//medicines

Route::middleware([\App\Http\Middleware\SetLocaleLang::class])->get('medicines', [ApiMedicineController::class, 'listAllMedicines']);
Route::middleware([\App\Http\Middleware\SetLocaleLang::class])->get('/medicines/{id}', [ApiMedicineController::class, 'show']);

//locations

Route::middleware([\App\Http\Middleware\SetLocaleLang::class])->get('cities', [LocationController::class, 'listAllCites']);
Route::middleware([\App\Http\Middleware\SetLocaleLang::class])->get('areas', [LocationController::class, 'listAllAreas']);

//rating

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/rate', [RatingController::class, 'rate']);


    Route::post('/favorites', [FavoriteController::class, 'addToFavorite']);
    Route::get('/favorites', [FavoriteController::class, 'listFavorites']);
    Route::delete('/favorites', [FavoriteController::class, 'removeFromFavorite']);


});

