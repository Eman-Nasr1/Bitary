<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\Animal\AnimalController;
use App\Modules\Seller\SellerController;
use App\Modules\Medicine\MedicineController;
use App\Modules\Category\CategoryController;
use App\Modules\AnimalType\AnimalTypeController;
use App\Modules\City\CityController;

// Language Switcher
Route::get('/lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switchLang'])->name('lang.switch');

// Admin Login Routes
Route::get('/admin/login', [\App\Http\Controllers\Auth\AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\Auth\AdminLoginController::class, 'login']);
Route::post('/admin/logout', [\App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('admin.logout');

// Provider Login Routes
Route::get('/provider/login', [\App\Http\Controllers\Auth\ProviderLoginController::class, 'showLoginForm'])->name('provider.login');
Route::post('/provider/login', [\App\Http\Controllers\Auth\ProviderLoginController::class, 'login']);
Route::post('/provider/logout', [\App\Http\Controllers\Auth\ProviderLoginController::class, 'logout'])->name('provider.logout');

// Root redirect
Route::get('/', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('dashboard.index');
    } elseif (Auth::guard('web')->check() && Auth::guard('web')->user()->isProvider()) {
        return redirect()->route('dashboard.provider.jobs.index');
    }
    return redirect()->route('admin.login');
});

// Admin Dashboard Routes - Separate group with auth:admin
Route::prefix('dashboard')->name('dashboard.')->middleware('auth:admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('index');
    
    // Admin Resources
    Route::resource('animals', AnimalController::class);
    Route::resource('animal_types', AnimalTypeController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('sellers', SellerController::class);
    Route::resource('medicines', MedicineController::class);
    Route::resource('cities', CityController::class);
    Route::resource('users', \App\Modules\User\AdminUserController::class);
    Route::post('users/{user}/toggle-provider', [\App\Modules\User\AdminUserController::class, 'toggleProviderStatus'])->name('users.toggle-provider');
    Route::post('users/{user}/toggle-status', [\App\Modules\User\AdminUserController::class, 'toggleUserStatus'])->name('users.toggle-status');
    
    // Provider Requests
    Route::get('provider-requests', [\App\Modules\ProviderRequest\AdminProviderRequestController::class, 'index'])->name('provider-requests.index');
    Route::get('provider-requests/{id}', [\App\Modules\ProviderRequest\AdminProviderRequestController::class, 'show'])->name('provider-requests.show');
    Route::post('provider-requests/{id}/approve', [\App\Modules\ProviderRequest\AdminProviderRequestController::class, 'approve'])->name('provider-requests.approve');
    Route::post('provider-requests/{id}/reject', [\App\Modules\ProviderRequest\AdminProviderRequestController::class, 'reject'])->name('provider-requests.reject');
    
    // Courses
    Route::resource('courses', \App\Http\Controllers\CourseController::class);
    Route::post('courses/instructors', [\App\Http\Controllers\CourseController::class, 'storeInstructor'])->name('courses.instructors.store');
    
    // Instructors
    Route::resource('instructors', \App\Http\Controllers\InstructorController::class);
    
    // Specializations
    Route::resource('specializations', \App\Http\Controllers\SpecializationController::class);
    
    // Podcasts
    Route::resource('podcasts', \App\Http\Controllers\PodcastController::class);
    
    // Episodes
    Route::resource('episodes', \App\Http\Controllers\EpisodeController::class);
    
    // Podcast Categories
    Route::resource('podcast-categories', \App\Http\Controllers\PodcastCategoryController::class);
    
    // News Module
    Route::resource('news', \App\Http\Controllers\NewsController::class);
    
    // Market Prices Module
    Route::resource('market-prices', \App\Http\Controllers\MarketPriceController::class);
    
    // Static Pages Module
    Route::resource('static-pages', \App\Http\Controllers\StaticPageController::class);
    
    // Contact Us Module (Admin Management)
    Route::get('contact-us', [\App\Http\Controllers\ContactUsController::class, 'index'])->name('contact-us.index');
    Route::get('contact-us/{id}', [\App\Http\Controllers\ContactUsController::class, 'show'])->name('contact-us.show');
    Route::post('contact-us/{id}/mark-as-read', [\App\Http\Controllers\ContactUsController::class, 'markAsRead'])->name('contact-us.mark-as-read');
    Route::post('contact-us/{id}/reply', [\App\Http\Controllers\ContactUsController::class, 'reply'])->name('contact-us.reply');
    Route::post('contact-us/{id}/archive', [\App\Http\Controllers\ContactUsController::class, 'archive'])->name('contact-us.archive');
    Route::delete('contact-us/{id}', [\App\Http\Controllers\ContactUsController::class, 'destroy'])->name('contact-us.destroy');
    
    // News Comments Module (Moderation)
    Route::get('news-comments', [\App\Http\Controllers\NewsCommentController::class, 'index'])->name('news-comments.index');
    Route::post('news-comments/{id}/approve', [\App\Http\Controllers\NewsCommentController::class, 'approve'])->name('news-comments.approve');
    Route::post('news-comments/{id}/reject', [\App\Http\Controllers\NewsCommentController::class, 'reject'])->name('news-comments.reject');
    Route::post('news-comments/{id}/hide', [\App\Http\Controllers\NewsCommentController::class, 'hide'])->name('news-comments.hide');
    Route::delete('news-comments/{id}', [\App\Http\Controllers\NewsCommentController::class, 'destroy'])->name('news-comments.destroy');
    
    // Admin Job Module Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Job Specializations
        Route::resource('job-specializations', \App\Http\Controllers\Admin\JobSpecializationController::class);
        
        // Jobs
        Route::resource('jobs', \App\Http\Controllers\Admin\JobController::class);
        Route::post('jobs/{id}/approve', [\App\Http\Controllers\Admin\JobController::class, 'approve'])->name('jobs.approve');
        Route::post('jobs/{id}/reject', [\App\Http\Controllers\Admin\JobController::class, 'reject'])->name('jobs.reject');
        
        // Job Applications
        Route::resource('job-applications', \App\Http\Controllers\Admin\JobApplicationController::class)->except(['create', 'store', 'edit']);
        Route::post('job-applications/{id}/update-status', [\App\Http\Controllers\Admin\JobApplicationController::class, 'updateStatus'])->name('job-applications.update-status');
    });
});

// Provider Dashboard Routes - Separate group with auth:web + CheckProviderRole
Route::prefix('dashboard/provider')->name('dashboard.provider.')->middleware(['auth:web', \App\Http\Middleware\CheckProviderRole::class])->group(function () {
    // Provider Jobs
    Route::resource('jobs', \App\Http\Controllers\Provider\JobController::class);
    
    // Provider Job Applications
    Route::resource('job-applications', \App\Http\Controllers\Provider\JobApplicationController::class)->except(['create', 'store', 'edit', 'destroy']);
    Route::post('job-applications/{id}/update-status', [\App\Http\Controllers\Provider\JobApplicationController::class, 'updateStatus'])->name('job-applications.update-status');
    
    // Provider Medicines (Products)
    Route::resource('medicines', \App\Modules\Medicine\MedicineController::class);
});

Auth::routes();
