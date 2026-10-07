<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_member_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_member_id')->constrained()->cascadeOnDelete();
            $table->string('locale');
            $table->string('name');
            $table->string('designation')->nullable();
            $table->text('qualification')->nullable();
            $table->text('expertise')->nullable();
            $table->text('registration')->nullable();
            $table->longText('biography')->nullable();
            $table->json('seo')->nullable();
            $table->unique(['team_member_id', 'locale']);
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_member_translations');
    }
};
