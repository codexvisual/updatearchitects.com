<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QueryBudgetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ContentSeeder::class);
    }

    public function test_public_pages_stay_within_a_sane_query_budget(): void
    {
        foreach (['/', '/about', '/services', '/projects', '/team', '/insights', '/contact'] as $path) {
            $this->assertQueryBudget($path, 25);
        }
    }

    public function test_public_detail_pages_stay_within_a_sane_query_budget(): void
    {
        $this->assertQueryBudget('/projects/2-storey-residential-building', 30);
        $this->assertQueryBudget('/services/architecture', 30);
        $this->assertQueryBudget('/team/engr-dr-sharifullah-ahmed-peng', 30);
    }

    public function test_admin_list_pages_stay_within_a_sane_query_budget(): void
    {
        $this->seed(AdminSeeder::class);

        $admin = User::where('email', 'admin@updatearchitects.com')->firstOrFail();

        foreach (['/admin', '/admin/projects', '/admin/services', '/admin/team', '/admin/blog'] as $path) {
            $count = $this->countQueries(fn () => $this->actingAs($admin)->get($path)->assertOk());

            fwrite(STDERR, "\n  {$path}: {$count} queries");

            $this->assertLessThanOrEqual(
                30,
                $count,
                "{$path} ran {$count} queries - possible N+1."
            );
        }

        fwrite(STDERR, "\n");
    }

    private function assertQueryBudget(string $path, int $max): void
    {
        $count = $this->countQueries(fn () => $this->get($path)->assertOk());

        fwrite(STDERR, "\n  {$path}: {$count} queries");

        $this->assertLessThanOrEqual($max, $count, "{$path} ran {$count} queries - possible N+1.");
    }

    private function countQueries(callable $callback): int
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        try {
            $callback();
        } finally {
            $count = count(DB::getQueryLog());
            DB::disableQueryLog();
            DB::flushQueryLog();
        }

        return $count;
    }
}
