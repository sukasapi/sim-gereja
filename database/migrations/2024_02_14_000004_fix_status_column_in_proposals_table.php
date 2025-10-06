<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            // Ubah kolom status menjadi enum dengan nilai yang sesuai
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            // Kembalikan ke string jika perlu rollback
            $table->string('status')->change();
        });
    }
}; 