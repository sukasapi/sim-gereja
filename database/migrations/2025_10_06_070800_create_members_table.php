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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->date('join_date')->nullable(); // Tanggal gabung gereja
            $table->boolean('is_baptized')->default(false); // Status baptis
            $table->boolean('is_sidi')->default(false); // Status sidi
            $table->date('baptism_date')->nullable();
            $table->date('sidi_date')->nullable();
            $table->text('ministry_notes')->nullable(); // Catatan pelayanan
            $table->text('notes')->nullable(); // Catatan umum
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
