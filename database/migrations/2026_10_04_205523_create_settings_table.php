<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable(); // flexible JSON value
            $table->string('group')->default('general'); // general, seo, contact, social, etc.
            $table->text('description')->nullable();
            $table->boolean('public')->default(false); // whether exposed to frontend
            $table->string('locale')->default('en');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['group', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
