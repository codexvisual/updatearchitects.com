<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ContentSeeder::class);
    }

    public function test_core_public_pages_render(): void
    {
        foreach ([
            '/',
            '/about',
            '/services',
            '/projects',
            '/team',
            '/insights',
            '/contact',
            '/start-your-project',
            '/privacy',
            '/terms',
            '/international-sop',
            '/search?q=architecture',
            '/login',
            '/forgot-password',
        ] as $path) {
            $this->assertPageRenders($path);
        }
    }

    public function test_every_service_category_page_renders(): void
    {
        foreach (['architecture', 'structural', 'geotechnical', 'construction', 'interior', 'mep', 'approval', 'documentation'] as $slug) {
            $this->assertPageRenders(route("services.{$slug}"));
        }
    }

    public function test_detail_pages_for_seeded_content_render(): void
    {
        $this->assertPageRenders(route('projects.show', Project::firstOrFail()->slug));
        $this->assertPageRenders(route('services.show', Service::firstOrFail()->slug));
        $this->assertPageRenders(route('team.show', TeamMember::firstOrFail()->slug));
    }

    public function test_unknown_urls_return_404(): void
    {
        foreach ([
            '/no-such-page',
            '/projects/missing-project',
            '/team/missing-member',
            '/services/missing-service',
            '/insights/missing-post',
        ] as $path) {
            $this->assertSame(
                404,
                $this->get($path)->getStatusCode(),
                "GET {$path} should return 404."
            );
        }
    }

    public function test_sitemap_and_robots_are_served(): void
    {
        $sitemap = $this->get('/sitemap.xml');

        $this->assertSame(200, $sitemap->getStatusCode());
        $this->assertStringContainsString('<urlset', $sitemap->getContent());
        $this->assertStringContainsString('<loc>', $sitemap->getContent());

        $robots = $this->get('/robots.txt');

        $this->assertSame(200, $robots->getStatusCode());
        $this->assertStringContainsString('User-agent:', $robots->getContent());
        $this->assertStringContainsString('sitemap.xml', $robots->getContent());
    }

    public function test_switching_locale_does_not_break_pages(): void
    {
        $switch = $this->get(route('locale.switch', 'bn'));

        $this->assertTrue(
            in_array($switch->getStatusCode(), [200, 302], true),
            'GET /locale/bn returned '.$switch->getStatusCode().'.'
        );

        $this->assertPageRenders('/');
        $this->assertPageRenders('/projects');
        $this->assertPageRenders('/services');
    }

    private function assertPageRenders(string $path): void
    {
        $response = $this->get($path);

        $this->assertSame(
            200,
            $response->getStatusCode(),
            "GET {$path} returned {$response->getStatusCode()}.\n"
                .substr(strip_tags($response->getContent()), 0, 400)
        );
    }
}
