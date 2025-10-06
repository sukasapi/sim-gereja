<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Gereja - Multi-Church Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <i class="fas fa-church text-2xl text-blue-600 mr-3"></i>
                    <h1 class="text-2xl font-bold text-gray-900">Multi-Church Platform</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/admin" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Admin Login
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-4">Platform Manajemen Gereja Terintegrasi</h2>
            <p class="text-xl mb-8">Kelola data jemaat, wilayah, dan administrasi gereja dengan mudah</p>
            <div class="flex justify-center space-x-8">
                <div class="text-center">
                    <i class="fas fa-users text-3xl mb-2"></i>
                    <p>Manajemen Jemaat</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-map text-3xl mb-2"></i>
                    <p>Wilayah Terstruktur</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-chart-bar text-3xl mb-2"></i>
                    <p>Laporan Terintegrasi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Churches List -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-900 mb-4">Gereja yang Terdaftar</h3>
                <p class="text-lg text-gray-600">Pilih gereja Anda untuk mengakses layanan</p>
                <div class="mt-4 p-4 bg-blue-50 rounded-lg max-w-2xl mx-auto">
                    <p class="text-blue-800 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Fitur Baru:</strong> Superadmin dapat mengatur gereja mana yang akan ditampilkan sebagai halaman depan website. 
                        Jika ada gereja yang dipilih sebagai default, halaman ini akan menampilkan company profile gereja tersebut.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__currentLoopData = $churches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $church): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <?php if($church->logo): ?>
                                <img src="<?php echo e(Storage::url($church->logo)); ?>" alt="<?php echo e($church->name); ?>" class="w-12 h-12 rounded-full mr-4">
                            <?php else: ?>
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-church text-blue-600"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h4 class="text-xl font-semibold text-gray-900"><?php echo e($church->name); ?></h4>
                                <p class="text-gray-600"><?php echo e($church->city); ?>, <?php echo e($church->province); ?></p>
                            </div>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <?php if($church->address): ?>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2"></i><?php echo e($church->address); ?>

                                </p>
                            <?php endif; ?>
                            <?php if($church->phone): ?>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-phone mr-2"></i><?php echo e($church->phone); ?>

                                </p>
                            <?php endif; ?>
                            <?php if($church->email): ?>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-envelope mr-2"></i><?php echo e($church->email); ?>

                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex flex-col space-y-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($church->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo e($church->is_active ? 'Aktif' : 'Tidak Aktif'); ?>

                                </span>
                                <?php if($church->is_default): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i>Halaman Depan
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="flex space-x-2">
                                <?php if($church->website): ?>
                                    <a href="<?php echo e($church->website); ?>" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="/admin" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-sign-in-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold text-gray-900 mb-4">Fitur Unggulan</h3>
                <p class="text-lg text-gray-600">Platform yang dirancang khusus untuk kebutuhan gereja</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                    <h4 class="text-lg font-semibold mb-2">Manajemen Jemaat</h4>
                    <p class="text-gray-600">Kelola data jemaat dengan lengkap termasuk status baptis dan sidi</p>
                </div>

                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-map text-2xl text-green-600"></i>
                    </div>
                    <h4 class="text-lg font-semibold mb-2">Wilayah Terstruktur</h4>
                    <p class="text-gray-600">Sistem wilayah yang fleksibel sesuai kebutuhan gereja</p>
                </div>

                <div class="text-center">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-2xl text-purple-600"></i>
                    </div>
                    <h4 class="text-lg font-semibold mb-2">Keamanan Data</h4>
                    <p class="text-gray-600">Data gereja terpisah dan aman dengan sistem role yang ketat</p>
                </div>

                <div class="text-center">
                    <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-bar text-2xl text-orange-600"></i>
                    </div>
                    <h4 class="text-lg font-semibold mb-2">Laporan Terintegrasi</h4>
                    <p class="text-gray-600">Generate laporan dan statistik untuk kebutuhan administrasi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p>&copy; <?php echo e(date('Y')); ?> Multi-Church Platform. All rights reserved.</p>
                <p class="mt-2 text-gray-400">Platform manajemen gereja terintegrasi</p>
            </div>
        </div>
    </footer>
</body>
</html>

<?php /**PATH C:\laragon\www\gkjprambanan\resources\views/churches/index.blade.php ENDPATH**/ ?>