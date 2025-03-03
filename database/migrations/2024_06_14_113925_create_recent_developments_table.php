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
        Schema::create('recent_developments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recent_development_category_id');
            $table->string('title');
            $table->longText('description');
            $table->timestamps();

            $table->foreign('recent_development_category_id')->references('id')->on('recent_development_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recent_developments');
    }
};
