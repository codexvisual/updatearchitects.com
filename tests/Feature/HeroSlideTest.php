<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeroSlideTest extends TestCase
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

    public function test_a_hero_slide_can_be_created_toggled_and_deleted(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/hero-slides', [
                'title' => 'Slide From Test',
                'eyebrow' => 'Test Eyebrow',
                'subtitle' => 'A subtitle from the test.',
                'locale' => 'en',
                'sort_order' => 1,
                'visibility' => '1',
            ])
            ->assertRedirect(route('admin.hero-slides.index'));

        $this->assertDatabaseHas('hero_slides', ['title' => 'Slide From Test']);

        $this->get('/')->assertSee('Slide From Test');

        $slide = HeroSlide::where('title', 'Slide From Test')->firstOrFail();

        $this->actingAs($this->admin)
            ->patch("/admin/hero-slides/{$slide->id}/toggle")
            ->assertSessionHas('success');

        $this->get('/')->assertDontSee('Slide From Test');

        $this->actingAs($this->admin)
            ->delete("/admin/hero-slides/{$slide->id}")
            ->assertSessionHas('success');

        $this->get('/')->assertDontSee('Slide From Test');
    }

    public function test_a_hero_slide_requires_a_title(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/hero-slides', ['locale' => 'en'])
            ->assertSessionHasErrors('title');
    }

    public function test_inactive_slides_are_not_shown_on_the_homepage(): void
    {
        HeroSlide::create([
            'title' => 'Hidden Slide',
            'subtitle' => 'Should not appear.',
            'locale' => 'en',
            'visibility' => false,
        ]);

        $this->get('/')->assertDontSee('Hidden Slide');
    }
}
