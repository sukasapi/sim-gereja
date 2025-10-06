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
        Schema::create('region_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Contoh: "Blok", "Pepanthan", "Sektor", "Lingkungan"
            $table->string('slug'); // Untuk URL dan referensi
            $table->text('description')->nullable();
            $table->integer('level')->default(1); // Level hierarki (1 = teratas)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['church_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('region_types');
    }
};
