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
        // Use View Composer to modify menu at render time (after authentication)
        // This ensures filters run on the menu items
        view()->composer('adminlte::page', function ($view) {
            // Modify menu config at render time when user is authenticated
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
                'text' => 'search',
                'topnav_right' => true,
            ],
            [
                'type' => 'fullscreen-widget',
                'topnav_right' => true,
            ],
            // Sidebar search
            [
                'type' => 'sidebar-menu-search',
                'text' => 'search',
            ],
        ];

        if ($isAdmin) {
            // Admin Menu - Add admin_only flag so RoleMenuFilter can filter
            $menu = array_merge($menu, [
                ['header' => 'ADMIN_MENU'],
                [
                    'text' => 'Provider Requests',
                    'url' => 'dashboard/provider-requests',
                    'icon' => 'fas fa-user-md',
                    'label_color' => 'warning',
                    'admin_only' => true, // Flag for RoleMenuFilter
                ],
                [
                    'text' => 'Jobs',
                    'url' => 'dashboard/admin/jobs',
                    'icon' => 'fas fa-briefcase',
                    'label_color' => 'info',
                    'admin_only' => true,
                ],
                [
                    'text' => 'Job Applications',
                    'url' => 'dashboard/admin/job-applications',
                    'icon' => 'fas fa-file-alt',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => 'Job Specializations',
                    'url' => 'dashboard/admin/job-specializations',
                    'icon' => 'fas fa-tags',
                    'label_color' => 'primary',
                    'admin_only' => true,
                ],
                ['header' => 'CONTENT_MANAGEMENT'],
                [
                    'text' => 'Animals',
                    'url' => 'dashboard/animals',
                    'icon' => 'fas fa-paw',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => 'Animal Types',
                    'url' => 'dashboard/animal_types',
                    'icon' => 'fas fa-tags',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => 'Categories',
                    'url' => 'dashboard/categories',
                    'icon' => 'fas fa-folder',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => 'Sellers',
                    'url' => 'dashboard/sellers',
                    'icon' => 'fas fa-user-tie',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => 'Products',
                    'url' => 'dashboard/medicines',
                    'icon' => 'fas fa-pills',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => 'Cities',
                    'url' => 'dashboard/cities',
                    'icon' => 'fas fa-city',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => 'Users',
                    'url' => 'dashboard/users',
                    'icon' => 'fas fa-users',
                    'label_color' => 'info',
                    'admin_only' => true,
                ],
                [
                    'text' => 'Courses',
                    'url' => 'dashboard/courses',
                    'icon' => 'fas fa-graduation-cap',
                    'label_color' => 'success',
                    'admin_only' => true,
                ],
                [
                    'text' => 'Instructors',
                    'url' => 'dashboard/instructors',
                    'icon' => 'fas fa-chalkboard-teacher',
                    'label_color' => 'info',
                    'admin_only' => true,
                ],
                [
                    'text' => 'Specializations',
                    'url' => 'dashboard/specializations',
                    'icon' => 'fas fa-tags',
                    'label_color' => 'primary',
                    'admin_only' => true,
                ],
            ]);
        } elseif ($isProvider) {
            // Provider Menu - Add provider_only flag so RoleMenuFilter can filter
            $menu = array_merge($menu, [
                ['header' => 'PROVIDER_MENU'],
                [
                    'text' => 'My Jobs',
                    'url' => 'dashboard/provider/jobs',
                    'icon' => 'fas fa-briefcase',
                    'label_color' => 'info',
                    'provider_only' => true, // Flag for RoleMenuFilter
                ],
                [
                    'text' => 'Job Applications',
                    'url' => 'dashboard/provider/job-applications',
                    'icon' => 'fas fa-file-alt',
                    'label_color' => 'success',
                    'provider_only' => true,
                ],
                [
                    'text' => 'My Products',
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
