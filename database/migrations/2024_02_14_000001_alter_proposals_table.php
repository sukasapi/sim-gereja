<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            // Hapus kolom yang tidak diperlukan
            $table->dropColumn('purpose');
            
            // Tambah kolom baru
            $table->string('recipient_name')->after('requester');
            $table->text('recipient_address')->after('recipient_name');
            $table->string('file')->nullable()->after('quantity');
            
            // Ubah kolom quantity menjadi nullable karena akan diisi per recipient
            $table->integer('quantity')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            // Kembalikan kolom yang dihapus
            $table->text('purpose')->after('requester');
            
            // Hapus kolom baru
            $table->dropColumn('recipient_name');
            $table->dropColumn('recipient_address');
            $table->dropColumn('file');
            
            // Kembalikan kolom quantity menjadi required
            $table->integer('quantity')->nullable(false)->change();
        });
    }
}; 