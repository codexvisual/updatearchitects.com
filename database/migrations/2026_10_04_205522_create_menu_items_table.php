<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
            $table->string('title');
            $table->string('url')->nullable(); // for custom links
            $table->string('target')->default('_self'); // _self, _blank
            $table->string('type')->default('custom'); // route, page, custom
            $table->json('route_params')->nullable(); // for route type
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('visibility')->default(true);
            $table->string('locale')->default('en');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['menu_id', 'parent_id', 'sort_order']);
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
