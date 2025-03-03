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
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->string('legal_document')->nullable();
            $table->string('template')->nullable();
            $table->string('case_law')->nullable();
            $table->string('statute_regulation')->nullable();
            $table->string('practice_guide')->nullable();
            $table->string('form_checklist')->nullable();
            $table->string('article_publication')->nullable();
            $table->string('firm_policy_procedure')->nullable();
            $table->string('research_tool')->nullable();
            $table->string('training_material')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libraries');
    }
};
