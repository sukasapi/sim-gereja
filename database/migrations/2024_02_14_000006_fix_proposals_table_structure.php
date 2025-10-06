<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            // Hapus kolom yang tidak diperlukan karena sudah menggunakan repeater
            $table->dropColumn(['recipient_name', 'recipient_address']);
        });
    }

    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            // Tambahkan kembali kolom jika perlu rollback
            $table->string('recipient_name')->after('requester');
            $table->text('recipient_address')->after('recipient_name');
        });
    }
}; 