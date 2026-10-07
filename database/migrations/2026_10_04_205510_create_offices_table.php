<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name'); // Kurigram Head Office, Nageshwari Branch, Rangpur Office
            $table->text('address');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('map_embed')->nullable(); // Google Maps embed iframe
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('visibility')->default(true);
            $table->string('locale')->default('en');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['visibility', 'sort_order']);
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
