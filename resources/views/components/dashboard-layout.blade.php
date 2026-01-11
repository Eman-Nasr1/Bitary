@push('topnav_right')
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-language"></i> {{ __('Language') }}
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="{{ url('/lang/ar') }}" class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                <i class="fas fa-circle mr-2 {{ app()->getLocale() == 'ar' ? 'text-success' : 'text-secondary' }}"></i>
                {{ __('Arabic') }}
            </a>
            <div class="dropdown-divider"></div>
            <a href="{{ url('/lang/en') }}" class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                <i class="fas fa-circle mr-2 {{ app()->getLocale() == 'en' ? 'text-success' : 'text-secondary' }}"></i>
                {{ __('English') }}
            </a>
        </div>
    </li>
@endpush

@push('css')
    <style>
        /* Sidebar Scrolling */
        .sidebar {
            overflow-y: auto !important;
            height: calc(100vh - 57.6px) !important;
        }

        .sidebar::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.5);
        }

        /* For Firefox */
        .sidebar {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.3) rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            // Ensure language switcher is added if not already present
            if ($('.nav-item:has(.fa-language)').length === 0) {
                var languageSwitcher = `
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-language"></i> {{ __('Language') }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <a href="{{ url('/lang/ar') }}" class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                                <i class="fas fa-circle mr-2 {{ app()->getLocale() == 'ar' ? 'text-success' : 'text-secondary' }}"></i>
                                {{ __('Arabic') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ url('/lang/en') }}" class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                                <i class="fas fa-circle mr-2 {{ app()->getLocale() == 'en' ? 'text-success' : 'text-secondary' }}"></i>
                                {{ __('English') }}
                            </a>
                        </div>
                    </li>
                `;
                
                var topnavRight = $('.navbar-nav.topnav-right, .navbar-nav:has(.fa-expand)').first();
                if (topnavRight.length > 0) {
                    topnavRight.append(languageSwitcher);
                } else {
                    $('.navbar-nav').last().append(languageSwitcher);
                }
            }
        });
    </script>
@endpush

