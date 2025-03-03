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
            $table->text('title')->change();
            $table->text('description')->nullable()->change();
            $table->text('document')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_law_by_areas', function (Blueprint $table) {
            $table->string('title', 255)->change();
            $table->text('description')->nullable(false)->change();
            $table->dropColumn('document');
        });
    }
};
