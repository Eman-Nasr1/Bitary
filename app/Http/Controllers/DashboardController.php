<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProviderRequest;
use App\Models\Animal;
use App\Models\Medicine;
use App\Models\Course;
use App\Models\Job;
use App\Models\News;
use App\Models\StaticPage;
use App\Models\MarketPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics.
     */
    public function index()
    {
        // Users Statistics
        $totalUsers = User::count();
        // Check if is_active column exists, otherwise count all users as active
        $activeUsers = \Schema::hasColumn('users', 'is_active') 
            ? User::where('is_active', true)->count() 
            : $totalUsers;
        $providers = User::where('is_provider', true)->count();
        $verifiedUsers = User::where('is_verified', true)->count();

        // Provider Requests Statistics
        $totalProviderRequests = ProviderRequest::count();
        $approvedProviders = ProviderRequest::where('status', 'approved')->count();
        $pendingProviderRequests = ProviderRequest::where('status', 'pending')->count();
        $doctors = ProviderRequest::where('status', 'approved')
            ->where('provider_type', 'doctor')
            ->count();
        $clinics = ProviderRequest::where('status', 'approved')
            ->where('provider_type', 'clinic')
            ->count();

        // Content Statistics
        $totalAnimals = Animal::count();
        $totalMedicines = Medicine::count();
        $totalCourses = Course::count();
        $totalJobs = Job::count();
        $publishedNews = News::where('status', 'published')->count();
        $totalNews = News::count();
        $activeStaticPages = StaticPage::where('status', 'active')->count();
        $totalStaticPages = StaticPage::count();
        $totalMarketPrices = MarketPrice::count();

        // Recent Activity (last 7 days)
        $recentUsers = User::where('created_at', '>=', now()->subDays(7))->count();
        $recentProviderRequests = ProviderRequest::where('created_at', '>=', now()->subDays(7))->count();
        $recentNews = News::where('created_at', '>=', now()->subDays(7))->count();

        return view('dashboard.index', [
            // Users Stats
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'providers' => $providers,
            'verifiedUsers' => $verifiedUsers,
            
            // Provider Requests Stats
            'totalProviderRequests' => $totalProviderRequests,
            'approvedProviders' => $approvedProviders,
            'pendingProviderRequests' => $pendingProviderRequests,
            'doctors' => $doctors,
            'clinics' => $clinics,
            
            // Content Stats
            'totalAnimals' => $totalAnimals,
            'totalMedicines' => $totalMedicines,
            'totalCourses' => $totalCourses,
            'totalJobs' => $totalJobs,
            'publishedNews' => $publishedNews,
            'totalNews' => $totalNews,
            'activeStaticPages' => $activeStaticPages,
            'totalStaticPages' => $totalStaticPages,
            'totalMarketPrices' => $totalMarketPrices,
            
            // Recent Activity
            'recentUsers' => $recentUsers,
            'recentProviderRequests' => $recentProviderRequests,
            'recentNews' => $recentNews,
        ]);
    }
}
