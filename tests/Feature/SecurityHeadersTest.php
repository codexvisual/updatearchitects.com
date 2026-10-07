<?php

namespace Tests\Feature;

use App\Http\Middleware\SecurityHeaders;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ContentSeeder::class);
    }

    public function test_baseline_security_headers_are_sent_on_public_pages(): void
    {
        foreach (['/', '/projects', '/contact'] as $path) {
            $response = $this->get($path);

            $this->assertSame(200, $response->getStatusCode(), "GET {$path}");
            $response->assertHeader('X-Content-Type-Options', 'nosniff');
            $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
            $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->assertHeader('X-Permitted-Cross-Domain-Policies', 'none');
            $this->assertStringContainsString(
                'camera=()',
                (string) $response->headers->get('Permissions-Policy'),
                "Permissions-Policy missing on {$path}."
            );
        }
    }

    public function test_hsts_is_only_sent_over_https(): void
    {
        $this->assertNull($this->get('/')->headers->get('Strict-Transport-Security'));

        config(['app.env' => 'production', 'app.debug' => false]);
        $request = Request::create('https://example.test/', 'GET');

        $headers = app(SecurityHeaders::class)
            ->handle($request, fn ($req) => response('ok'));

        $this->assertStringContainsString('max-age=31536000', (string) $headers->headers->get('Strict-Transport-Security'));
    }

    public function test_content_security_policy_is_sent_once_debug_is_disabled(): void
    {
        config(['app.env' => 'production', 'app.debug' => false]);

        $csp = $this->get('/')->headers->get('Content-Security-Policy');

        $this->assertNotNull($csp, 'A production build must send a Content-Security-Policy.');
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("frame-ancestors 'self'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
        $this->assertStringContainsString("form-action 'self'", $csp);
        $this->assertStringContainsString("base-uri 'self'", $csp);
    }

    public function test_content_security_policy_is_withheld_from_the_vite_dev_server(): void
    {
        $this->assertNull(
            $this->get('/')->headers->get('Content-Security-Policy'),
            'The CSP must stay off in local development so npm run dev still works.'
        );
    }

    public function test_private_storage_files_cannot_be_fetched_without_a_signature(): void
    {
        $status = $this->get('/storage/consultations/2026/10/secret.pdf')->getStatusCode();

        $this->assertTrue(
            in_array($status, [403, 404], true),
            "Private files must not be readable directly; got {$status}."
        );
    }

    public function test_icon_and_hero_assets_exist_on_disk(): void
    {
        foreach ([
            'favicon.ico',
            'favicon.svg',
            'icon.svg',
            'apple-touch-icon.png',
            'images/optimized/hero-800.jpg',
            'images/optimized/hero-1440.jpg',
            'images/optimized/hero-800.webp',
            'images/optimized/hero-1440.webp',
        ] as $path) {
            $this->assertFileExists(public_path($path), "The layout references a missing asset: {$path}.");
        }
    }

    public function test_the_layout_links_only_assets_that_exist(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('favicon.ico', $html);
        $this->assertStringContainsString('icon.svg', $html);
        $this->assertStringContainsString('apple-touch-icon.png', $html);
        $this->assertStringNotContainsString('manifest.json', $html, 'A manifest is referenced but never shipped.');
        $this->assertStringNotContainsString('logo.png', $html, 'A logo file is referenced but never shipped.');
    }
}
