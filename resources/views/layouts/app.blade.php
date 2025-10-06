<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [x-cloak] { display: none !important; }
        </style>
        @stack('styles')
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex flex-col">
            <div class="flex flex-1">
                <!-- Sidebar -->
                @include('layouts.sidebar')

                <!-- Main Content -->
                <div class="flex-1 flex flex-col w-full">
                    <!-- Header -->
                    @include('layouts.header')

                    <!-- Main Content -->
                    <main class="flex-1 p-6 bg-gray-50">
                        <div class="max-w-full mx-auto">
                            @yield('content')
                        </div>
                    </main>

                    <!-- Footer -->
                    @include('layouts.footer')
                </div>
            </div>
        </div>

        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @stack('scripts')
    </body>
</html>
