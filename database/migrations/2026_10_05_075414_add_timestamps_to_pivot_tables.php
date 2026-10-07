<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var list<string>
     */
    private array $pivotTables = [
        'project_service',
        'project_team_member',
        'blog_post_tag',
        'service_team_member',
    ];

    public function up(): void
    {
        foreach ($this->pivotTables as $table) {
            if (! Schema::hasTable($table) || Schema::hasColumn($table, 'created_at')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->timestamps();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->pivotTables as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'created_at')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropTimestamps();
            });
        }
    }
};
