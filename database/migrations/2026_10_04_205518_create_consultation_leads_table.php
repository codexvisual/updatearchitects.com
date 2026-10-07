<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('project_type')->nullable(); // residential, commercial, industrial, interior, etc.
            $table->string('project_location')->nullable();
            $table->string('approximate_area')->nullable();
            $table->json('required_services')->nullable(); // array of service IDs/slugs
            $table->string('estimated_budget')->nullable();
            $table->date('expected_start_date')->nullable();
            $table->text('message')->nullable();
            $table->json('files')->nullable(); // array of uploaded file paths
            $table->boolean('consent')->default(false);
            $table->string('contact_method')->nullable(); // email, phone, whatsapp
            $table->string('language')->nullable(); // en, bn
            $table->string('source')->nullable(); // website, referral, social, etc.
            $table->json('utm')->nullable(); // utm_source, utm_medium, utm_campaign
            $table->string('status')->default('new'); // new, contacted, qualified, proposal_sent, won, lost
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('follow_up_at')->nullable();
            $table->longText('notes')->nullable(); // internal notes
            $table->string('locale')->default('en');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('email');
            $table->index('assigned_to');
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_leads');
    }
};
