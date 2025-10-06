<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('donor_name');
            $table->text('donor_address');
            $table->enum('donation_type', ['internal', 'external']);
            $table->enum('donation_category', ['barang', 'dana']);
            $table->enum('donation_size', ['besar', 'kecil']);
            $table->decimal('amount', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->foreignId('proposal_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
}; 