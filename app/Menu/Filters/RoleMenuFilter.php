<?php

namespace App\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Illuminate\Support\Facades\Auth;

class RoleMenuFilter implements FilterInterface
{
    public function transform($item)
    {
        // Skip if item is not an array
        if (!is_array($item)) {
            return $item;
        }

        // Get current user - check admin guard first
        $isAdmin = Auth::guard('admin')->check();
        $isProvider = false;
        
        // Check if web user is provider
        $webUser = Auth::guard('web')->user();
        if ($webUser && method_exists($webUser, 'isProvider')) {
            $isProvider = $webUser->isProvider();
        }
        
        // If no user is logged in, only show search items
        if (!$isAdmin && !$isProvider) {
            // Only show search items if no user logged in
            if (isset($item['type']) && in_array($item['type'], ['sidebar-menu-search', 'navbar-search', 'fullscreen-widget'])) {
                return $item;
            }
            // Hide all other items if they require admin or provider
            if (isset($item['admin_only']) || isset($item['provider_only']) || isset($item['role'])) {
                return false;
            }
            // Allow items without restrictions
            return $item;
        }

        // If item has 'role' key, check if user has that role
        if (isset($item['role'])) {
            $requiredRole = $item['role'];
            
            if ($requiredRole === 'admin' && !$isAdmin) {
                return false; // Hide item
            }
            
            if ($requiredRole === 'provider' && !$isProvider) {
                return false; // Hide item
            }
        }

        // If item has 'provider_only' key, only show for providers
        if (isset($item['provider_only']) && $item['provider_only']) {
            if (!$isProvider) {
                return false; // Hide item
            }
        }

        // If item has 'admin_only' key, only show for admins
        if (isset($item['admin_only']) && $item['admin_only']) {
            if (!$isAdmin) {
                return false; // Hide item
            }
        }

        return $item;
    }
}
