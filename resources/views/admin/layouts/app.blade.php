<!doctype html>
<html
  lang="en"
  class="light-style layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets/assets/') }}"
  data-template="vertical-menu-template-free"
  data-style="light">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Tour Admin') }}</title>
    <meta name="description" content="@yield('description', 'Tour Management System')" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/assets/img/favicon/favicon.ico') }}" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/fonts/remixicon/remixicon.css') }}" />
    
    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/node-waves/node-waves.css') }}" />
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/assets/css/demo.css') }}" />
    
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    
    <!-- Page CSS -->
    @stack('styles')
    
    <!-- Helpers -->
    <script src="{{ asset('assets/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/assets/js/config.js') }}"></script>
    
    <style>
        /* System Font - Mazzard (Manrope as fallback) */
        * {
            font-family: 'Manrope', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }
        
        body {
            font-family: 'Manrope', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }
        
        /* CSS Variables from home.blade.php - Exact Match */
        :root {
            --primary-green: #3ea572; /* A vibrant green for primary elements */
            --secondary-green: #2d7a5f; /* A darker green for accents and hover states */
            --accent-green: #6cbe8f; /* A lighter, brighter green for highlights */
            --light-green: #e6f4ed; /* Very light green for backgrounds and subtle highlights */
            --dark-green: #1a4d3a; /* Very dark green for text on light backgrounds */
            --gray-dark: #343a40;
            --gray: #6c757d;
            --gray-light: #f8f9fa;
            --white: #ffffff;
            --text-color: #343a40;
            --border-color: #dee2e6;
        }
        
        /* Override Materio colors with our green theme */
        .bg-primary { background-color: var(--primary-green) !important; }
        .bg-success { background-color: var(--secondary-green) !important; }
        .bg-info { background-color: var(--accent-green) !important; }
        .text-primary { color: var(--primary-green) !important; }
        .text-success { color: var(--secondary-green) !important; }
        .btn-primary { background-color: var(--primary-green) !important; border-color: var(--primary-green) !important; }
        .btn-primary:hover { background-color: var(--secondary-green) !important; border-color: var(--secondary-green) !important; }
        .btn-success { background-color: var(--accent-green) !important; border-color: var(--accent-green) !important; }
        .btn-success:hover { background-color: var(--secondary-green) !important; border-color: var(--secondary-green) !important; }
        .btn-info { background-color: var(--accent-green) !important; border-color: var(--accent-green) !important; }
        .btn-info:hover { background-color: var(--primary-green) !important; border-color: var(--primary-green) !important; }
        .badge.bg-primary { background-color: var(--primary-green) !important; }
        .badge.bg-success { background-color: var(--secondary-green) !important; }
        .badge.bg-info { background-color: var(--accent-green) !important; }
        .card-header { background-color: var(--light-green) !important; border-bottom: 2px solid var(--accent-green) !important; }
        .menu-item.active > .menu-link { background-color: var(--light-green) !important; color: var(--primary-green) !important; }
        .menu-link:hover { background-color: var(--light-green) !important; color: var(--primary-green) !important; }
        
        /* Avatar Initials */
        .avatar-initials {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--accent-green) 0%, var(--primary-green) 100%);
            color: var(--white);
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 50%;
        }
        .avatar-initials-nav { width: 40px; height: 40px; font-size: 0.875rem; }
        .avatar-initials-dropdown { width: 60px; height: 60px; font-size: 1.25rem; }
        
        /* Card Border Shadow Styles */
        .card-border-shadow-primary {
            border-left: 4px solid var(--primary-green) !important;
            box-shadow: 0 2px 6px 0 rgba(62, 165, 114, 0.1);
        }
        .card-border-shadow-warning {
            border-left: 4px solid #ffc107 !important;
            box-shadow: 0 2px 6px 0 rgba(255, 193, 7, 0.1);
        }
        .card-border-shadow-danger {
            border-left: 4px solid #dc3545 !important;
            box-shadow: 0 2px 6px 0 rgba(220, 53, 69, 0.1);
        }
        .card-border-shadow-info {
            border-left: 4px solid var(--accent-green) !important;
            box-shadow: 0 2px 6px 0 rgba(108, 190, 143, 0.1);
        }
        
        /* Progress Bar Labels */
        .bookings-progress-labels {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .bookings-progress-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-color);
        }
        
        /* Timeline Styles */
        .timeline-item {
            position: relative;
            padding-left: 2rem;
        }
        .timeline-indicator-advanced {
            position: absolute;
            left: 0;
            top: 0;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--light-green);
        }
        .timeline-event {
            padding-left: 0.5rem;
        }
    </style>
  </head>
  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
              <span class="app-brand-logo demo me-1">
  



                </span>
              </span>
              <span class="app-brand-text demo menu-text fw-semibold ms-2">{{ config('app.name', 'Tour Admin') }}</span>
            </a>
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="menu-toggle-icon d-xl-block align-middle"></i>
            </a>
          </div>
          <div class="menu-inner-shadow"></div>
          <ul class="menu-inner py-1">
            @include('admin.partials.sidebar.menu')
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                <i class="ri-menu-fill ri-24px"></i>
              </a>
            </div>
            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <i class="ri-search-line ri-22px me-2"></i>
                  <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." aria-label="Search..." />
                </div>
              </div>
              <!-- /Search -->
              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt class="w-px-40 h-auto rounded-circle" />
                      @else
                        <div class="avatar-initials avatar-initials-nav rounded-circle" title="{{ auth()->user()->name }}">
                          {{ auth()->user()->initials }}
                        </div>
                      @endif
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
                    <li>
                      <a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <div class="d-flex align-items-center">
                          <div class="flex-shrink-0 me-2">
                            @if(auth()->user()->avatar)
                              <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt class="w-px-40 h-auto rounded-circle" />
                            @else
                              <div class="avatar-initials avatar-initials-dropdown rounded-circle">
                                {{ auth()->user()->initials }}
                              </div>
                            @endif
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-0 small">{{ auth()->user()->name }}</h6>
                            <small class="text-muted">{{ auth()->user()->roles->first() ? auth()->user()->roles->first()->name : 'User' }}</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li><div class="dropdown-divider"></div></li>
                    <li>
                      <a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <i class="ri-user-3-line ri-22px me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="{{ route('admin.settings') }}">
                        <i class="ri-settings-4-line ri-22px me-2"></i>
                        <span class="align-middle">Settings</span>
                      </a>
                    </li>
                    <li><div class="dropdown-divider"></div></li>
                    <li>
                      <div class="d-grid px-4 pt-2 pb-1">
                        <form method="POST" action="{{ route('logout') }}">
                          @csrf
                          <button type="submit" class="btn btn-danger d-flex w-100">
                            <small class="align-middle">Logout</small>
                            <i class="ri-logout-box-r-line ms-2 ri-16px"></i>
                          </button>
                        </form>
                      </div>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <!-- Session Messages (Hidden - will trigger toasts) -->
              @if(session('success'))
                <div data-session-success="{{ session('success') }}" style="display: none;"></div>
              @endif
              @if(session('error'))
                <div data-session-error="{{ session('error') }}" style="display: none;"></div>
              @endif
              @if(session('warning'))
                <div data-session-warning="{{ session('warning') }}" style="display: none;"></div>
              @endif
              @if(session('info'))
                <div data-session-info="{{ session('info') }}" style="display: none;"></div>
              @endif

              @yield('content')
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <hr class="m-0">
            <footer class="content-footer footer bg-footer-theme bg-body-secondary">
              <div class="container-xxl">
                <div class="footer-container py-3 text-center">
                  <div class="text-body small">
                    TOUR_MIS <span class="mx-1">|</span> Version v1.0.0
                    <span class="mx-1">•</span>
                    © {{ now()->year }} • All rights reserved. • Designed &amp; maintained by TOUR_MIS Team
                    <span class="mx-1">•</span>
                    Secured Environment
                  </div>
                </div>
              </div>
            </footer>
            <!-- / Footer -->
            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>
      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="{{ asset('assets/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/assets/vendor/js/menu.js') }}"></script>
    
    <!-- Vendors JS -->
    @if(file_exists(public_path('assets/assets/vendor/libs/apex-charts/apexcharts.js')))
        <script src="{{ asset('assets/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    @elseif(file_exists(public_path('assets/vendor/libs/apex-charts/apexcharts.js')))
        <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    @else
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.min.js"></script>
    @endif
    
    <!-- Main JS -->
    <script src="{{ asset('assets/assets/js/main.js') }}"></script>
    
    <!-- Advanced Toast Notification System -->
    <script src="{{ asset('assets/assets/js/admin-notifications.js') }}"></script>
    
    <!-- Page JS -->
    @stack('scripts')
  </body>
</html>
