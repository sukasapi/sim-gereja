<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $churches = \App\Models\Church::all();
        $categories = \App\Models\Category::all();
        $users = \App\Models\User::where('role', 'admin_gereja')->get();

        if ($churches->isEmpty() || $categories->isEmpty() || $users->isEmpty()) {
            return;
        }

        $posts = [
            [
                'title' => 'Ibadah Minggu Paskah 2024',
                'excerpt' => 'Mari bergabung dalam ibadah Minggu Paskah yang penuh sukacita dan makna.',
                'content' => 'Ibadah Minggu Paskah akan dilaksanakan pada tanggal 31 Maret 2024 pukul 07.00 WIB. Ibadah ini akan dipimpin oleh Pdt. Dr. John Doe dengan tema "Kebangkitan yang Memberikan Harapan". Semua jemaat diundang untuk hadir dan merayakan kebangkitan Tuhan Yesus Kristus.',
                'status' => 'published',
                'is_featured' => true,
                'views' => 150,
            ],
            [
                'title' => 'Pengumuman Libur Natal dan Tahun Baru',
                'excerpt' => 'Informasi penting mengenai jadwal ibadah selama libur Natal dan Tahun Baru.',
                'content' => 'Dalam rangka libur Natal dan Tahun Baru, berikut adalah jadwal ibadah yang telah disesuaikan:\n\n1. Ibadah Natal: 25 Desember 2024 pukul 07.00 WIB\n2. Ibadah Tahun Baru: 1 Januari 2025 pukul 07.00 WIB\n3. Ibadah Minggu biasa akan dilaksanakan sesuai jadwal normal\n\nSelamat Natal dan Tahun Baru untuk semua jemaat.',
                'status' => 'published',
                'is_featured' => true,
                'views' => 200,
            ],
            [
                'title' => 'Renungan Harian: Kasih yang Tidak Bersyarat',
                'excerpt' => 'Mari merenungkan kasih Tuhan yang tidak bersyarat dalam kehidupan kita sehari-hari.',
                'content' => 'Kasih Tuhan adalah kasih yang tidak bersyarat. Dia mengasihi kita bukan karena apa yang kita lakukan atau tidak lakukan, tetapi karena siapa kita di mata-Nya. Dalam Yohanes 3:16, kita membaca bahwa Tuhan begitu mengasihi dunia ini, sehingga Ia memberikan Anak-Nya yang tunggal.\n\nKasih ini mengajarkan kita untuk mengasihi sesama tanpa syarat, menerima mereka apa adanya, dan memberikan dukungan dalam suka dan duka.',
                'status' => 'published',
                'is_featured' => false,
                'views' => 75,
            ],
            [
                'title' => 'Kegiatan Komisi Remaja: Retreat Spiritual',
                'excerpt' => 'Komisi Remaja akan mengadakan retreat spiritual untuk memperdalam iman dan persekutuan.',
                'content' => 'Komisi Remaja GKJ Prambanan akan mengadakan retreat spiritual pada tanggal 15-17 Maret 2024 di Wisma Bethel, Kaliurang. Kegiatan ini bertujuan untuk:\n\n1. Memperdalam iman dan spiritualitas remaja\n2. Membangun persekutuan yang lebih erat\n3. Melatih kepemimpinan dan pelayanan\n4. Menikmati alam ciptaan Tuhan\n\nBiaya pendaftaran: Rp 150.000 per orang\nPendaftaran dibuka hingga 10 Maret 2024.',
                'status' => 'published',
                'is_featured' => false,
                'views' => 120,
            ],
            [
                'title' => 'Artikel: Pentingnya Komunitas dalam Iman Kristen',
                'excerpt' => 'Mengapa komunitas berperan penting dalam perjalanan iman seorang Kristen?',
                'content' => 'Iman Kristen bukanlah perjalanan yang dilakukan sendirian. Sejak awal, Tuhan menciptakan manusia untuk hidup dalam komunitas. Dalam Kejadian 2:18, Tuhan berkata, "Tidak baik kalau manusia itu seorang diri saja."\n\nKomunitas gereja memberikan:\n1. Dukungan spiritual dan emosional\n2. Akuntabilitas dalam perjalanan iman\n3. Kesempatan untuk melayani dan dilayani\n4. Pertumbuhan melalui persekutuan\n5. Kesaksian bersama tentang kasih Tuhan\n\nMari kita aktif terlibat dalam komunitas gereja kita.',
                'status' => 'published',
                'is_featured' => false,
                'views' => 90,
            ],
        ];

        foreach ($posts as $index => $postData) {
            $church = $churches->random();
            $category = $categories->random();
            $user = $users->where('church_id', $church->id)->first() ?? $users->first();

            \App\Models\Post::create([
                'title' => $postData['title'],
                'slug' => \Illuminate\Support\Str::slug($postData['title']),
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'status' => $postData['status'],
                'is_featured' => $postData['is_featured'],
                'views' => $postData['views'],
                'published_at' => now()->subDays(rand(1, 30)),
                'category_id' => $category->id,
                'user_id' => $user->id,
                'church_id' => $church->id,
            ]);
        }
    }
}
