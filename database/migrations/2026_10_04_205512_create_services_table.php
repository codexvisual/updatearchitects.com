<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained('service_categories')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('services')->nullOnDelete();
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->foreignId('featured_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('icon')->nullable(); // icon class or SVG path
            $table->json('faq')->nullable(); // array of Q&A
            $table->json('seo')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('visibility')->default(true);
            $table->string('locale')->default('en');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['category_id', 'visibility']);
            $table->index(['parent_id', 'visibility']);
            $table->index('locale');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
