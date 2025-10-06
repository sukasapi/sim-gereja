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
        Schema::table('members', function (Blueprint $table) {
            $table->foreignId('father_id')->nullable()->constrained('members')->onDelete('set null');
            $table->foreignId('mother_id')->nullable()->constrained('members')->onDelete('set null');
            $table->foreignId('region_id')->nullable()->constrained('regions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['father_id']);
            $table->dropForeign(['mother_id']);
            $table->dropForeign(['region_id']);
            $table->dropColumn(['father_id', 'mother_id', 'region_id']);
        });
    }
};
