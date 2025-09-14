<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LibraFlow') }}</title>

        <!-- PWA Meta Tags -->
        <meta name="description" content="Dagupan City National High School Library Management System">
        <meta name="theme-color" content="#4e73df">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="LibraFlow">
        
        <!-- PWA Manifest -->
        <link rel="manifest" href="/manifest.json">
        
        <!-- Apple Touch Icons -->
        <link rel="apple-touch-icon" href="/favicon.ico">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Fallback CSS if Vite fails -->
        <style>
            /* Fallback styles if Vite assets don't load */
            .btn { display: inline-block; padding: 0.375rem 0.75rem; margin-bottom: 0; font-size: 1rem; font-weight: 400; line-height: 1.5; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; border: 1px solid transparent; border-radius: 0.375rem; }
            .btn-primary { color: #fff; background-color: #0d6efd; border-color: #0d6efd; }
            .btn-success { color: #fff; background-color: #198754; border-color: #198754; }
            .btn-warning { color: #000; background-color: #ffc107; border-color: #ffc107; }
            .btn-danger { color: #fff; background-color: #dc3545; border-color: #dc3545; }
            .btn-info { color: #000; background-color: #0dcaf0; border-color: #0dcaf0; }
            .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.25rem; }
            .table { width: 100%; margin-bottom: 1rem; color: #212529; border-collapse: collapse; }
            .table th, .table td { padding: 0.75rem; vertical-align: top; border-top: 1px solid #dee2e6; }
            .table thead th { vertical-align: bottom; border-bottom: 2px solid #dee2e6; }
            .table-bordered { border: 1px solid #dee2e6; }
            .table-bordered th, .table-bordered td { border: 1px solid #dee2e6; }
            .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(0,0,0,.05); }
        </style>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { background: #f8fafc; }
            .navbar-brand { font-weight: bold; letter-spacing: 1px; }
            .footer { background: #222; color: #fff; padding: 1rem 0; text-align: center; margin-top: 3rem; }
            .card { box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
            .nav-link.active, .nav-link:focus { font-weight: bold; color: #0d6efd !important; }
            
            /* Fix pagination styling */
            .pagination { display: flex; justify-content: center; align-items: center; margin: 1rem 0; }
            .pagination .page-link { display: inline-block; padding: 0.5rem 1rem; margin: 0 0.25rem; background-color: #fff; border: 1px solid #dee2e6; color: #0d6efd; text-decoration: none; border-radius: 0.375rem; }
            .pagination .page-link:hover { background-color: #e9ecef; border-color: #dee2e6; color: #0a58ca; }
            .pagination .page-item.active .page-link { background-color: #0d6efd; border-color: #0d6efd; color: #fff; }
            .pagination .page-item.disabled .page-link { color: #6c757d; background-color: #fff; border-color: #dee2e6; cursor: not-allowed; }
            
            /* Hide any problematic arrows or loading indicators */
            .loading-arrow, .loading-spinner, .vite-loading {
                display: none !important;
            }
            
            /* Ensure proper layout */
            .container { position: relative; z-index: 1; }
            main { position: relative; z-index: 1; }
            
            /* Custom dropdown menu styling */
            .dropdown { position: relative; }
            .dropdown-menu { 
                position: absolute; 
                top: 100%; 
                right: 0; 
                z-index: 9999; 
                min-width: 180px; 
                padding: 0.5rem 0; 
                margin: 0.125rem 0 0; 
                background-color: #fff; 
                border: 1px solid rgba(0,0,0,.15); 
                border-radius: 0.375rem; 
                box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
                display: none;
                pointer-events: auto;
            }
            .dropdown-menu.show { display: block; }
            .dropdown-item { 
                display: block; 
                width: 100%; 
                padding: 0.5rem 1rem; 
                clear: both; 
                font-weight: 400; 
                color: #212529; 
                text-align: inherit; 
                text-decoration: none; 
                white-space: nowrap; 
                background-color: transparent; 
                border: 0; 
                cursor: pointer;
            }
            .dropdown-item:hover, .dropdown-item:focus { 
                color: #1e2125; 
                background-color: #e9ecef; 
                text-decoration: none;
            }
            .dropdown-item.active, .dropdown-item:active { 
                color: #fff; 
                background-color: #0d6efd; 
            }
            .dropdown-item.disabled, .dropdown-item:disabled { 
                color: #adb5bd; 
                pointer-events: none; 
                background-color: transparent; 
            }
            .dropdown-divider { 
                height: 0; 
                margin: 0.5rem 0; 
                overflow: hidden; 
                border-top: 1px solid rgba(0,0,0,.15); 
            }
            .dropdown-arrow { 
                font-size: 0.8em; 
                margin-left: 0.5rem; 
                transition: transform 0.2s ease;
            }
            .dropdown-arrow.rotated { 
                transform: rotate(180deg); 
            }
            
            /* Ensure navbar doesn't interfere with dropdown */
            .navbar { z-index: 1000; }
            .navbar-nav .dropdown { z-index: 10000; }
            
            /* Make sure dropdown items are clickable */
            .dropdown-item {
                position: relative;
                z-index: 10001;
                pointer-events: auto;
            }
        </style>
    </head>
    <body>
        @include('layouts.navigation')
        <div class="container py-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <main>
                @yield('content')
            </main>
        </div>
        <footer class="footer">
            &copy; {{ date('Y') }} Dagupan City National Highschool Library &mdash; Powered by LibraFlow
        </footer>
        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- PWA Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/sw.js')
                        .then(function(registration) {
                            console.log('ServiceWorker registration successful');
                        })
                        .catch(function(err) {
                            console.log('ServiceWorker registration failed');
                        });
                });
            }
        </script>
        
        <!-- Custom Dropdown JavaScript -->
        <script>
            function toggleDropdown(event) {
                event.preventDefault();
                event.stopPropagation();
                
                const dropdownMenu = document.getElementById('userDropdownMenu');
                const dropdownArrow = document.querySelector('.dropdown-arrow');
                
                if (dropdownMenu && dropdownArrow) {
                    if (dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '') {
                        dropdownMenu.style.display = 'block';
                        dropdownMenu.style.position = 'absolute';
                        dropdownMenu.style.top = '100%';
                        dropdownMenu.style.right = '0';
                        dropdownMenu.style.zIndex = '9999';
                        dropdownArrow.classList.add('rotated');
                    } else {
                        dropdownMenu.style.display = 'none';
                        dropdownArrow.classList.remove('rotated');
                    }
                }
            }
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const dropdownMenu = document.getElementById('userDropdownMenu');
                const dropdownToggle = document.getElementById('userDropdown');
                const dropdownArrow = document.querySelector('.dropdown-arrow');
                
                if (dropdownMenu && dropdownToggle && dropdownArrow) {
                    if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                        dropdownMenu.style.display = 'none';
                        dropdownArrow.classList.remove('rotated');
                    }
                }
            });
            
            // Ensure dropdown items are clickable
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('dropdown-item')) {
                    // Allow the click to proceed normally
                    return true;
                }
            });
        </script>
    </body>
</html>
