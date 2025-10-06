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
        Schema::table('project_work_items', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->decimal('realization_amount', 15, 2)->default(0);
        });

        Schema::table('project_work_sub_items', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->decimal('realization_amount', 15, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_work_items', function (Blueprint $table) {
            $table->dropColumn('realization_amount');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
        });

        Schema::table('project_work_sub_items', function (Blueprint $table) {
            $table->dropColumn('realization_amount');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
        });
    }
};
