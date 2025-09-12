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

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { background: #f8fafc; }
            .navbar-brand { font-weight: bold; letter-spacing: 1px; }
            .footer { background: #222; color: #fff; padding: 1rem 0; text-align: center; margin-top: 3rem; }
            .card { box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
            .nav-link.active, .nav-link:focus { font-weight: bold; color: #0d6efd !important; }
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
