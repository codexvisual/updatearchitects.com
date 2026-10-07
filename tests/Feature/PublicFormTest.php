<?php

namespace Tests\Feature;

use App\Models\ConsultationLead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_requires_the_expected_fields(): void
    {
        $this->post('/contact', [])
            ->assertSessionHasErrors(['name', 'email', 'message']);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_rejects_an_invalid_email(): void
    {
        $this->post('/contact', [
            'name' => 'QA Visitor',
            'email' => 'not-an-email',
            'message' => 'Hello there.',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_rejects_the_honeypot(): void
    {
        $this->post('/contact', [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'Automated spam.',
            'website' => 'http://spam.example',
        ])->assertSessionHasErrors('website');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_stores_a_message(): void
    {
        $this->post('/contact', [
            'name' => 'QA Visitor',
            'email' => 'qa@example.com',
            'phone' => '01700000000',
            'subject' => 'General enquiry',
            'message' => 'Please share the services you offer.',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'qa@example.com',
            'subject' => 'General enquiry',
        ]);
    }

    public function test_contact_form_is_rate_limited(): void
    {
        $payload = [
            'name' => 'QA Visitor',
            'email' => 'qa@example.com',
            'message' => 'Please share the services you offer.',
        ];

        for ($attempt = 1; $attempt <= 6; $attempt++) {
            $this->assertSame(302, $this->post('/contact', $payload)->getStatusCode());
        }

        $this->assertSame(429, $this->post('/contact', $payload)->getStatusCode());
    }

    public function test_consultation_form_requires_the_expected_fields(): void
    {
        $this->post('/start-your-project', [])
            ->assertSessionHasErrors(['name', 'phone', 'email', 'consent']);

        $this->assertDatabaseCount('consultation_leads', 0);
    }

    public function test_consultation_form_rejects_unsupported_file_types(): void
    {
        $this->post('/start-your-project', [
            'name' => 'QA Visitor',
            'phone' => '01700000000',
            'email' => 'qa@example.com',
            'consent' => '1',
            'files' => [UploadedFile::fake()->create('shell.php', 4)],
        ])->assertSessionHasErrors('files.0');

        $this->assertDatabaseCount('consultation_leads', 0);
    }

    public function test_consultation_form_stores_a_lead_with_its_attachment(): void
    {
        Storage::fake('local');

        $brief = UploadedFile::fake()->createWithContent(
            'project-brief.jpg',
            file_get_contents(base_path('tests/fixtures/gallery-sample.jpg'))
        );

        $this->post('/start-your-project', [
            'name' => 'QA Visitor',
            'phone' => '01700000000',
            'email' => 'qa@example.com',
            'project_type' => 'residential',
            'project_location' => 'Kurigram',
            'message' => 'We need architectural and structural consultancy.',
            'consent' => '1',
            'contact_method' => 'email',
            'files' => [$brief],
        ])->assertSessionHas('success');

        $lead = ConsultationLead::where('email', 'qa@example.com')->firstOrFail();

        $this->assertSame('new', $lead->status);
        $this->assertNotEmpty($lead->files);
        $this->assertTrue(Storage::disk('local')->exists($lead->files[0]));
    }
}
