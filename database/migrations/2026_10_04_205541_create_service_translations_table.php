<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('locale');
            $table->string('name');
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->json('faq')->nullable();
            $table->json('seo')->nullable();
            $table->unique(['service_id', 'locale']);
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_translations');
    }
};
