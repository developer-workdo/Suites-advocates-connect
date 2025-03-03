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
        Schema::create('practice_tools', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('practice_tool_category_id');
            $table->string('title');
            $table->longText('description');
            $table->timestamps();

            $table->foreign('practice_tool_category_id')->references('id')->on('practice_tool_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_tools');
    }
};
