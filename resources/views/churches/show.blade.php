<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $church->name }} - {{ $church->city }}, {{ $church->province }}</title>
    <meta name="description" content="Gereja {{ $church->name }} di {{ $church->city }}, {{ $church->province }}. {{ $church->address }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    @if($church->logo)
                        <img src="{{ Storage::url($church->logo) }}" alt="{{ $church->name }}" class="w-10 h-10 rounded-full mr-3">
                    @else
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-church text-blue-600"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $church->name }}</h1>
                        <p class="text-sm text-gray-600">{{ $church->city }}, {{ $church->province }}</p>
                    </div>
                </div>
                <nav class="hidden md:flex items-center space-x-6">
                    <a href="#tentang" class="text-gray-600 hover:text-blue-600 transition">Tentang</a>
                    <a href="#kontak" class="text-gray-600 hover:text-blue-600 transition">Kontak</a>
                    <a href="#layanan" class="text-gray-600 hover:text-blue-600 transition">Layanan</a>
                    <a href="/churches" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-list mr-2"></i>Daftar Gereja
                    </a>
                    <a href="/admin" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Admin
                    </a>
                </nav>
                <!-- Mobile menu button -->
                <button class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-bars text-gray-600"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-600 via-blue-700 to-purple-800 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                @if($church->logo)
                    <img src="{{ Storage::url($church->logo) }}" alt="{{ $church->name }}" class="w-32 h-32 rounded-full mx-auto mb-8 border-4 border-white shadow-lg">
                @else
                    <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-8 border-4 border-white shadow-lg">
                        <i class="fas fa-church text-5xl"></i>
                    </div>
                @endif
                <h1 class="text-5xl font-bold mb-4">{{ $church->name }}</h1>
                <p class="text-xl mb-6 text-blue-100">{{ $church->city }}, {{ $church->province }}</p>
                <p class="text-lg mb-8 text-blue-200 max-w-3xl mx-auto">
                    Selamat datang di {{ $church->name }}. Kami adalah komunitas yang berkomitmen untuk melayani Tuhan dan sesama dengan kasih dan iman yang teguh.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    @if($church->phone)
                        <a href="tel:{{ $church->phone }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-6 py-3 rounded-lg transition flex items-center">
                            <i class="fas fa-phone mr-2"></i>{{ $church->phone }}
                        </a>
                    @endif
                    @if($church->email)
                        <a href="mailto:{{ $church->email }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-6 py-3 rounded-lg transition flex items-center">
                            <i class="fas fa-envelope mr-2"></i>{{ $church->email }}
                        </a>
                    @endif
                    @if($church->website)
                        <a href="{{ $church->website }}" target="_blank" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-6 py-3 rounded-lg transition flex items-center">
                            <i class="fas fa-external-link-alt mr-2"></i>Website Resmi
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Tentang {{ $church->name }}</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Kami adalah komunitas yang berkomitmen untuk melayani Tuhan dan sesama dengan kasih dan iman yang teguh. 
                    Bergabunglah dengan kami dalam perjalanan iman yang penuh berkat.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Komunitas</h3>
                    <p class="text-gray-600">Bergabunglah dengan komunitas yang penuh kasih dan saling mendukung</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Pelayanan</h3>
                    <p class="text-gray-600">Melayani Tuhan dan sesama dengan hati yang tulus dan penuh kasih</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-pray text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Iman</h3>
                    <p class="text-gray-600">Membangun iman yang kuat melalui firman Tuhan dan doa bersama</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Layanan Kami</h2>
                <p class="text-lg text-gray-600">Berbagai kegiatan dan layanan yang kami sediakan untuk jemaat</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <i class="fas fa-church text-3xl text-blue-600 mb-4"></i>
                    <h3 class="text-lg font-semibold mb-2">Ibadah Minggu</h3>
                    <p class="text-gray-600 text-sm">Setiap hari Minggu pukul 08.00 & 10.00</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <i class="fas fa-book text-3xl text-green-600 mb-4"></i>
                    <h3 class="text-lg font-semibold mb-2">Sekolah Minggu</h3>
                    <p class="text-gray-600 text-sm">Pembelajaran Alkitab untuk anak-anak</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <i class="fas fa-music text-3xl text-purple-600 mb-4"></i>
                    <h3 class="text-lg font-semibold mb-2">Paduan Suara</h3>
                    <p class="text-gray-600 text-sm">Latihan setiap hari Rabu pukul 19.00</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <i class="fas fa-hands-helping text-3xl text-orange-600 mb-4"></i>
                    <h3 class="text-lg font-semibold mb-2">Pelayanan Sosial</h3>
                    <p class="text-gray-600 text-sm">Membantu sesama yang membutuhkan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Hubungi Kami</h2>
                <p class="text-lg text-gray-600">Kami siap melayani dan menjawab pertanyaan Anda</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Information -->
                <div class="space-y-6">
                    @if($church->address)
                        <div class="flex items-start">
                            <div class="bg-blue-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Alamat</h3>
                                <p class="text-gray-600">{{ $church->address }}</p>
                                @if($church->postal_code)
                                    <p class="text-gray-600">{{ $church->postal_code }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($church->phone)
                        <div class="flex items-start">
                            <div class="bg-green-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Telepon</h3>
                                <a href="tel:{{ $church->phone }}" class="text-blue-600 hover:underline">{{ $church->phone }}</a>
                            </div>
                        </div>
                    @endif

                    @if($church->email)
                        <div class="flex items-start">
                            <div class="bg-purple-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-envelope text-purple-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Email</h3>
                                <a href="mailto:{{ $church->email }}" class="text-blue-600 hover:underline">{{ $church->email }}</a>
                            </div>
                        </div>
                    @endif

                    @if($church->website)
                        <div class="flex items-start">
                            <div class="bg-orange-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-globe text-orange-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Website</h3>
                                <a href="{{ $church->website }}" target="_blank" class="text-blue-600 hover:underline">{{ $church->website }}</a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="bg-gray-50 rounded-lg p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Akses Cepat</h3>
                    <div class="space-y-4">
                        <a href="/admin" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login Admin
                        </a>
                        @if($church->website)
                            <a href="{{ $church->website }}" target="_blank" class="w-full bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition flex items-center justify-center">
                                <i class="fas fa-external-link-alt mr-2"></i>Kunjungi Website Resmi
                            </a>
                        @endif
                        <a href="/churches" class="w-full border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition flex items-center justify-center">
                            <i class="fas fa-list mr-2"></i>Lihat Daftar Gereja Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        @if($church->logo)
                            <img src="{{ Storage::url($church->logo) }}" alt="{{ $church->name }}" class="w-10 h-10 rounded-full mr-3">
                        @else
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-church text-blue-600"></i>
                            </div>
                        @endif
                        <h3 class="text-xl font-bold">{{ $church->name }}</h3>
                    </div>
                    <p class="text-gray-400 mb-4">
                        {{ $church->city }}, {{ $church->province }}
                    </p>
                    <p class="text-gray-400 text-sm">
                        Komunitas yang berkomitmen untuk melayani Tuhan dan sesama dengan kasih dan iman yang teguh.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                    <div class="space-y-2 text-gray-400">
                        @if($church->address)
                            <p><i class="fas fa-map-marker-alt mr-2"></i>{{ $church->address }}</p>
                        @endif
                        @if($church->phone)
                            <p><i class="fas fa-phone mr-2"></i>{{ $church->phone }}</p>
                        @endif
                        @if($church->email)
                            <p><i class="fas fa-envelope mr-2"></i>{{ $church->email }}</p>
                        @endif
                    </div>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Akses Cepat</h4>
                    <div class="space-y-2">
                        <a href="/admin" class="block text-gray-400 hover:text-white transition">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login Admin
                        </a>
                        <a href="/churches" class="block text-gray-400 hover:text-white transition">
                            <i class="fas fa-list mr-2"></i>Daftar Gereja
                        </a>
                        @if($church->website)
                            <a href="{{ $church->website }}" target="_blank" class="block text-gray-400 hover:text-white transition">
                                <i class="fas fa-external-link-alt mr-2"></i>Website Resmi
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-400">
                    &copy; {{ date('Y') }} {{ $church->name }}. All rights reserved.
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    Powered by Multi-Church Platform
                </p>
            </div>
        </div>
    </footer>
</body>
</html>

