<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_team_member', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_member_id')->constrained()->cascadeOnDelete();
            $table->string('role')->nullable(); // lead architect, structural engineer, etc.
            $table->unique(['project_id', 'team_member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_team_member');
    }
};
