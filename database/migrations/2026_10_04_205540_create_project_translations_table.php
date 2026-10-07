<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('locale'); // en, bn
            $table->string('title');
            $table->text('summary')->nullable();
            $table->string('location')->nullable();
            $table->string('client')->nullable();
            $table->text('description')->nullable();
            $table->text('design_concept')->nullable();
            $table->text('architecture_info')->nullable();
            $table->text('structural_info')->nullable();
            $table->text('engineering_info')->nullable();
            $table->text('geotechnical_info')->nullable();
            $table->text('construction_info')->nullable();
            $table->text('interior_info')->nullable();
            $table->string('consultant')->nullable();
            $table->text('progress_overview')->nullable();
            $table->json('seo')->nullable();
            $table->unique(['project_id', 'locale']);
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_translations');
    }
};
