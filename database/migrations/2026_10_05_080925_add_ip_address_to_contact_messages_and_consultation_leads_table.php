<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var list<string>
     */
    private array $tables = [
        'contact_messages',
        'consultation_leads',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'ip_address')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->string('ip_address', 45)->nullable()->after('locale');
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'ip_address')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropColumn('ip_address');
                });
            }
        }
    }
};
