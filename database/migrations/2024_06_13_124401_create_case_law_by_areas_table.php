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
        Schema::create('case_law_by_areas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_law_by_area_category_id');
            $table->string('title');
            $table->longText('description');
            $table->timestamps();

            $table->foreign('case_law_by_area_category_id')->references('id')->on('case_law_by_area_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_law_by_areas');
    }
};
