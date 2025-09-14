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
            
            /* Hide any loading indicators or arrows that might appear */
            .loading-arrow, .loading-spinner, .vite-loading, [class*="arrow"], [class*="loading"] {
                display: none !important;
            }
            
            /* Ensure proper layout */
            .container { position: relative; z-index: 1; }
            main { position: relative; z-index: 1; }
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
    </body>
</html>
