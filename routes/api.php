<?php


use Illuminate\Http\Request;
use App\Modules\User\UserController;
use Illuminate\Support\Facades\Route;
use App\Modules\Rating\RatingController;
use App\Modules\Animal\ApiAnimalController;
use App\Modules\AnimalType\ApiAnimalTypeController;

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

// Provider Requests
Route::middleware('auth:sanctum')->prefix('provider-request')->group(function () {
    Route::post('/', [\App\Modules\ProviderRequest\ApiProviderRequestController::class, 'store']);
    Route::get('/status', [\App\Modules\ProviderRequest\ApiProviderRequestController::class, 'status']);
});

// Providers (Doctors & Clinics)
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('doctors', [\App\Modules\ProviderRequest\ApiProviderRequestController::class, 'getProviders']);
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('doctors/{id}', [\App\Modules\ProviderRequest\ApiProviderRequestController::class, 'getProvider']);

// Job Applications
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->post('jobs/{jobId}/apply', [\App\Http\Controllers\Api\JobApplicationController::class, 'apply']);
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('my-applications', [\App\Http\Controllers\Api\JobApplicationController::class, 'myApplications']);

Route::middleware('auth:sanctum')->post('logout', [UserController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/token/check', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Token is valid',
        'user' => auth()->user()
    ]);
});


//animals
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('animals/{id}', [ApiAnimalController::class, 'show']);
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('animals', [ApiAnimalController::class, 'listAllAnimals']);

//animal_types
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('animal_types/{id}', [ApiAnimalTypeController::class, 'show']);
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('animal_types', [ApiAnimalTypeController::class, 'listAllAnimalTypes']);

//category
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('categories/{id}', [ApiCategoryController::class, 'show']);
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('categories', [ApiCategoryController::class, 'listAllCategories']);

//medicines
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('products', [ApiMedicineController::class, 'listAllMedicines']);
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('/products/{id}', [ApiMedicineController::class, 'show']);

//courses
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('courses', [\App\Modules\Course\ApiCourseController::class, 'listAllCourses']);
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('courses/{id}', [\App\Modules\Course\ApiCourseController::class, 'show']);

//jobs
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('jobs', [\App\Http\Controllers\Api\JobController::class, 'index']);
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('jobs/{id}', [\App\Http\Controllers\Api\JobController::class, 'show']);

//job-specializations
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('job-specializations', [\App\Http\Controllers\Api\JobSpecializationController::class, 'index']);
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('job-specializations/{id}', [\App\Http\Controllers\Api\JobSpecializationController::class, 'show']);

//course-specializations
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('course-specializations', [\App\Http\Controllers\Api\CourseSpecializationController::class, 'index']);
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('course-specializations/{id}', [\App\Http\Controllers\Api\CourseSpecializationController::class, 'show']);

//podcasts
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('podcasts', [\App\Http\Controllers\Api\PodcastController::class, 'index']);
Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocaleLang::class])->get('podcasts/{id}', [\App\Http\Controllers\Api\PodcastController::class, 'show']);

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

