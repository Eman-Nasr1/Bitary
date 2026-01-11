<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Update menu config before views are rendered
        if (app()->runningInConsole()) {
            return;
        }

        // Use View Creator to modify menu BEFORE view is rendered (before authentication check in view)
        // This ensures menu is available when AdminLTE builds it
        view()->creator('adminlte::page', function ($view) {
            // Modify menu config before view is rendered
            $roleBasedMenu = $this->getMenuItems();
            config(['adminlte.menu' => $roleBasedMenu]);
        });
    }

    /**
     * Get menu items based on user role
     */
    protected function getMenuItems(): array
    {
        $user = Auth::guard('admin')->user() ?? Auth::guard('web')->user();
        $isAdmin = $user && (Auth::guard('admin')->check() || (method_exists($user, 'isAdmin') && $user->isAdmin()));
        $isProvider = $user && method_exists($user, 'isProvider') && $user->isProvider();

        $menu = [
            // Navbar items
            [
                'type' => 'navbar-search',
                'text' => __('Search'),
                'topnav_right' => true,
            ],
            [
                'type' => 'fullscreen-widget',
                'topnav_right' => true,
            ],
            [
                'type' => 'navbar-dropdown',
                'text' => __('Language'),
                'icon' => 'fas fa-language',
                'topnav_right' => true,
                'submenu' => [
                    [
                        'text' => __('Arabic'),
                        'icon' => 'fas fa-circle',
                        'icon_color' => app()->getLocale() == 'ar' ? 'success' : 'secondary',
                        'url' => url('/lang/ar'),
                        'active' => app()->getLocale() == 'ar',
                    ],
                    [
                        'text' => __('English'),
                        'icon' => 'fas fa-circle',
                        'icon_color' => app()->getLocale() == 'en' ? 'success' : 'secondary',
                        'url' => url('/lang/en'),
                        'active' => app()->getLocale() == 'en',
                    ],
                ],
            ],
            // Sidebar search
            [
                'type' => 'sidebar-menu-search',
                'text' => __('Search'),
            ],
        ];

        if ($isAdmin) {
            // Admin Menu - Add admin_only flag so RoleMenuFilter can filter
            $menu = array_merge($menu, [
                ['header' => __('ADMIN_MENU')],
                [
                    'text' => __('Provider Requests'),
                    'url' => 'dashboard/provider-requests',
                    'icon' => 'fas fa-user-md',
                    'label_color' => 'warning',
                    'admin_only' => true, // Flag for RoleMenuFilter
                ],
                [
                    'text' => __('Jobs'),
                    'url' => 'dashboard/admin/jobs',
                    'icon' => 'fas fa-briefcase',
                    'label_color' => 'info',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Job Applications'),
                    'url' => 'dashboard/admin/job-applications',
                    'icon' => 'fas fa-file-alt',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Job Specializations'),
                    'url' => 'dashboard/admin/job-specializations',
                    'icon' => 'fas fa-tags',
                    'label_color' => 'primary',
                    'admin_only' => true,
                ],
                ['header' => __('CONTENT_MANAGEMENT')],
                [
                    'text' => __('Animals'),
                    'url' => 'dashboard/animals',
                    'icon' => 'fas fa-paw',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Animal Types'),
                    'url' => 'dashboard/animal_types',
                    'icon' => 'fas fa-tags',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Categories'),
                    'url' => 'dashboard/categories',
                    'icon' => 'fas fa-folder',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Sellers'),
                    'url' => 'dashboard/sellers',
                    'icon' => 'fas fa-user-tie',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Products'),
                    'url' => 'dashboard/medicines',
                    'icon' => 'fas fa-pills',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Cities'),
                    'url' => 'dashboard/cities',
                    'icon' => 'fas fa-city',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Users'),
                    'url' => 'dashboard/users',
                    'icon' => 'fas fa-users',
                    'label_color' => 'info',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Courses'),
                    'url' => 'dashboard/courses',
                    'icon' => 'fas fa-graduation-cap',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Podcasts'),
                    'url' => 'dashboard/podcasts',
                    'icon' => 'fas fa-microphone-alt',
                    'label_color' => 'info',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Episodes'),
                    'url' => 'dashboard/episodes',
                    'icon' => 'fas fa-video',
                    'label_color' => 'info',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Podcast Categories'),
                    'url' => 'dashboard/podcast-categories',
                    'icon' => 'fas fa-tags',
                    'label_color' => 'primary',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Instructors'),
                    'url' => 'dashboard/instructors',
                    'icon' => 'fas fa-chalkboard-teacher',
                    'label_color' => 'info',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Specializations'),
                    'url' => 'dashboard/specializations',
                    'icon' => 'fas fa-tags',
                    'label_color' => 'primary',
                    'admin_only' => true,
                ],
                ['header' => __('NEWS_&_MARKET')],
                [
                    'text' => __('News'),
                    'url' => 'dashboard/news',
                    'icon' => 'fas fa-newspaper',
                    'label_color' => 'info',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Market Prices'),
                    'url' => 'dashboard/market-prices',
                    'icon' => 'fas fa-chart-line',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => __('News Comments'),
                    'url' => 'dashboard/news-comments',
                    'icon' => 'fas fa-comments',
                    'label_color' => 'warning',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Static Pages'),
                    'url' => 'dashboard/static-pages',
                    'icon' => 'fas fa-file-alt',
                    'label_color' => 'info',
                    'admin_only' => true,
                ],
                [
                    'text' => __('Contact Us'),
                    'url' => 'dashboard/contact-us',
                    'icon' => 'fas fa-envelope',
                    'label_color' => 'primary',
                    'admin_only' => true,
                ],
            ]);
        } elseif ($isProvider) {
            // Provider Menu - Add provider_only flag so RoleMenuFilter can filter
            $menu = array_merge($menu, [
                ['header' => __('PROVIDER_MENU')],
                [
                    'text' => __('My Jobs'),
                    'url' => 'dashboard/provider/jobs',
                    'icon' => 'fas fa-briefcase',
                    'label_color' => 'info',
                    'provider_only' => true, // Flag for RoleMenuFilter
                ],
                [
                    'text' => __('Job Applications'),
                    'url' => 'dashboard/provider/job-applications',
                    'icon' => 'fas fa-file-alt',
                    'label_color' => 'success',
                    'provider_only' => true,
                ],
                [
                    'text' => __('My Products'),
                    'url' => 'dashboard/provider/medicines',
                    'icon' => 'fas fa-pills',
                    'label_color' => 'success',
                    'provider_only' => true,
                ],
            ]);
        }

        return $menu;
    }
}
