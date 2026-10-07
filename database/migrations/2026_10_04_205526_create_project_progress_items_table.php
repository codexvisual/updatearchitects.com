<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_progress_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, completed, on_hold, not_applicable
            $table->boolean('is_completed')->default(false);
            $table->json('images')->nullable(); // array of media IDs
            $table->foreignId('video_id')->nullable()->constrained('media')->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('visibility')->default(true);
            $table->text('internal_notes')->nullable(); // not shown publicly
            $table->string('locale')->default('en');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['project_id', 'sort_order']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_progress_items');
    }
};
