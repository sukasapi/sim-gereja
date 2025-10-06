<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubProjectIdToProjectWorkItemsTable extends Migration
{
    public function up(): void
    {
        Schema::table('project_work_items', function (Blueprint $table) {
            $table->foreignId('sub_project_id')->nullable()->constrained('project_sub_projects')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('project_work_items', function (Blueprint $table) {
            $table->dropForeign(['sub_project_id']);
            $table->dropColumn('sub_project_id');
        });
    }
} 