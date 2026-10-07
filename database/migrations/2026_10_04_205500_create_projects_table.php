<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('category')->nullable(); // residential, commercial, industrial, institutional, infrastructure, interior, construction, structural, geotechnical
            $table->string('location')->nullable();
            $table->string('client')->nullable();
            $table->year('year')->nullable();
            $table->string('status')->default('upcoming'); // upcoming, ongoing, completed
            $table->boolean('featured')->default(false);
            $table->string('area')->nullable(); // e.g., "1,550 sq.ft"
            $table->unsignedSmallInteger('floors')->nullable();
            $table->text('description')->nullable();
            $table->text('design_concept')->nullable();
            $table->text('architecture_info')->nullable();
            $table->text('structural_info')->nullable();
            $table->text('engineering_info')->nullable();
            $table->text('geotechnical_info')->nullable();
            $table->text('construction_info')->nullable();
            $table->text('interior_info')->nullable();
            $table->string('consultant')->nullable();
            $table->foreignId('featured_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->text('progress_overview')->nullable();
            $table->json('seo')->nullable(); // title, description, canonical, og_image, schema
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('locale')->default('en');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index(['category', 'status']);
            $table->index('locale');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
