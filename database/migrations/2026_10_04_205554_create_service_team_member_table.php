<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_team_member', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_member_id')->constrained()->cascadeOnDelete();
            $table->string('role')->nullable(); // lead, consultant, specialist
            $table->unique(['service_id', 'team_member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_team_member');
    }
};
