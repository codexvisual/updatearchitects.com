<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('photo_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('designation')->nullable(); // e.g., "Geotechnical & Structural Consultant"
            $table->text('qualification')->nullable(); // degrees, certifications
            $table->text('expertise')->nullable();
            $table->text('registration')->nullable(); // professional registrations
            $table->longText('biography')->nullable();
            $table->foreignId('office_id')->nullable()->constrained('offices')->nullOnDelete();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->json('social_links')->nullable(); // linkedin, twitter, etc.
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('visibility')->default(true);
            $table->json('seo')->nullable();
            $table->string('locale')->default('en');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['office_id', 'visibility']);
            $table->index('locale');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
