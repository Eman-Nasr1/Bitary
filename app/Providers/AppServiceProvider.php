<?php

namespace App\Providers;

use App\Models\Rating;
use App\Observers\RatingObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }


    public function boot(): void
    {
        Rating::observe(RatingObserver::class);
        
        // Register policies
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Job::class, \App\Policies\JobPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\JobApplication::class, \App\Policies\JobApplicationPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Medicine::class, \App\Policies\MedicinePolicy::class);

        // Register morphMap for polymorphic relationships (favorites & ratings)
        // This ensures favoritable_type/rateable_type is stored as short names instead of full class name
        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'medicine' => \App\Models\Medicine::class,
            'product' => \App\Models\Medicine::class, // Alias for medicine
            'course' => \App\Models\Course::class,
            'seller' => \App\Models\Seller::class,
        ]);
    }
}
