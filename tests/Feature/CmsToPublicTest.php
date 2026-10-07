<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CmsToPublicTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AdminSeeder::class);
        $this->seed(ContentSeeder::class);

        $this->admin = User::where('email', 'admin@updatearchitects.com')->firstOrFail();
    }

    public function test_a_project_can_be_created_updated_and_deleted_from_the_cms(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/projects', $this->payload())
            ->assertRedirect('/admin/projects');

        $project = Project::where('slug', 'qa-test-project')->firstOrFail();

        $this->assertNotNull($project->published_at, 'Publishing the project should stamp published_at.');

        $this->assertPageContains('/projects/qa-test-project', 'Quality Assurance Test Project');

        $this->actingAs($this->admin)
            ->put('/admin/projects/'.$project->id, array_merge($this->payload(), [
                'title' => 'Quality Assurance Test Project (Edited)',
            ]))
            ->assertRedirect('/admin/projects');

        $this->assertPageContains('/projects/qa-test-project', 'Quality Assurance Test Project (Edited)');

        $this->actingAs($this->admin)
            ->delete('/admin/projects/'.$project->id)
            ->assertRedirect();

        $this->assertSame(
            404,
            $this->get('/projects/qa-test-project')->getStatusCode(),
            'A deleted project must disappear from the public site.'
        );
    }

    public function test_gallery_images_uploaded_in_the_cms_reach_the_public_site(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post('/admin/projects', $this->payload())
            ->assertRedirect('/admin/projects');

        $project = Project::where('slug', 'qa-test-project')->firstOrFail();

        $this->actingAs($this->admin)
            ->put('/admin/projects/'.$project->id, array_merge($this->payload(), [
                'gallery' => [
                    UploadedFile::fake()->createWithContent(
                        'gallery-sample.jpg',
                        file_get_contents(base_path('tests/fixtures/gallery-sample.jpg'))
                    ),
                ],
            ]))
            ->assertRedirect('/admin/projects');

        $project->refresh();

        $this->assertSame(1, $project->images()->count());
        $this->assertNotNull($project->featured_image_id, 'The first gallery image should become the featured image.');

        $this->assertPageContains('/projects/qa-test-project', 'gallery-sample.jpg');

        $image = $project->images()->firstOrFail();

        $this->actingAs($this->admin)
            ->delete('/admin/projects/'.$project->id.'/images/'.$image->id);

        $this->assertSame(0, $project->images()->count());
        $this->assertNull($project->fresh()->featured_image_id, 'Removing the featured image should clear the reference.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(): array
    {
        return [
            'title' => 'Quality Assurance Test Project',
            'slug' => 'qa-test-project',
            'summary' => 'A temporary project used to verify the publishing pipeline.',
            'location' => 'Kurigram, Bangladesh',
            'status' => 'ongoing',
            'locale' => 'en',
            'publish_now' => '1',
            'sort_order' => '99',
        ];
    }

    private function assertPageContains(string $path, string $needle): void
    {
        $response = $this->get($path);

        $this->assertSame(200, $response->getStatusCode(), "GET {$path} returned {$response->getStatusCode()}.");
        $this->assertStringContainsString($needle, $response->getContent(), "GET {$path} did not contain \"{$needle}\".");
    }
}
