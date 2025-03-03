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
        Schema::table('case_law_by_areas', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('description');
        });

        Schema::table('practice_tools', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('description');
        });

        Schema::table('recent_developments', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_law_by_areas', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('practice_tools', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('recent_developments', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });
    }
};
