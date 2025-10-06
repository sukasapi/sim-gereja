<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $church->name }} - Multi-Church Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <i class="fas fa-church text-2xl text-blue-600 mr-3"></i>
                        <h1 class="text-2xl font-bold text-gray-900">Multi-Church Platform</h1>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <a href="/admin" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Admin Login
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Church Header -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center">
                @if($church->logo)
                    <img src="{{ Storage::url($church->logo) }}" alt="{{ $church->name }}" class="w-24 h-24 rounded-full mr-6 border-4 border-white">
                @else
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-6 border-4 border-white">
                        <i class="fas fa-church text-4xl"></i>
                    </div>
                @endif
                <div>
                    <h2 class="text-4xl font-bold mb-2">{{ $church->name }}</h2>
                    <p class="text-xl mb-2">{{ $church->city }}, {{ $church->province }}</p>
                    <div class="flex items-center space-x-4">
                        @if($church->phone)
                            <span class="flex items-center">
                                <i class="fas fa-phone mr-2"></i>{{ $church->phone }}
                            </span>
                        @endif
                        @if($church->email)
                            <span class="flex items-center">
                                <i class="fas fa-envelope mr-2"></i>{{ $church->email }}
                            </span>
                        @endif
                        @if($church->website)
                            <a href="{{ $church->website }}" target="_blank" class="flex items-center hover:underline">
                                <i class="fas fa-external-link-alt mr-2"></i>Website
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Church Details -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Church Information -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Informasi Gereja</h3>
                    
                    <div class="space-y-4">
                        @if($church->address)
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Alamat</h4>
                                    <p class="text-gray-600">{{ $church->address }}</p>
                                </div>
                            </div>
                        @endif

                        @if($church->postal_code)
                            <div class="flex items-start">
                                <i class="fas fa-mail-bulk text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Kode Pos</h4>
                                    <p class="text-gray-600">{{ $church->postal_code }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-semibold text-gray-900">Status</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $church->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $church->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Kontak</h3>
                    
                    <div class="space-y-4">
                        @if($church->phone)
                            <div class="flex items-center">
                                <i class="fas fa-phone text-green-600 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Telepon</h4>
                                    <a href="tel:{{ $church->phone }}" class="text-blue-600 hover:underline">{{ $church->phone }}</a>
                                </div>
                            </div>
                        @endif

                        @if($church->email)
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-green-600 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Email</h4>
                                    <a href="mailto:{{ $church->email }}" class="text-blue-600 hover:underline">{{ $church->email }}</a>
                                </div>
                            </div>
                        @endif

                        @if($church->website)
                            <div class="flex items-center">
                                <i class="fas fa-globe text-green-600 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Website</h4>
                                    <a href="{{ $church->website }}" target="_blank" class="text-blue-600 hover:underline">{{ $church->website }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-12 text-center">
                <div class="flex justify-center space-x-4">
                    <a href="/admin" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login Admin
                    </a>
                    @if($church->website)
                        <a href="{{ $church->website }}" target="_blank" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition font-semibold">
                            <i class="fas fa-external-link-alt mr-2"></i>Kunjungi Website
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Multi-Church Platform. All rights reserved.</p>
                <p class="mt-2 text-gray-400">Platform manajemen gereja terintegrasi</p>
            </div>
        </div>
    </footer>
</body>
</html>

