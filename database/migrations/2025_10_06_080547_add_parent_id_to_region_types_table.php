<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('region_types', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('region_types')->onDelete('cascade');
            $table->integer('sort_order')->default(0); // Untuk mengurutkan tipe wilayah
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('region_types', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'sort_order']);
        });
    }
};
