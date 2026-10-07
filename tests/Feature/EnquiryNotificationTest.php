<?php

namespace Tests\Feature;

use App\Mail\NewConsultationLead;
use App\Mail\NewContactMessage;
use App\Models\ConsultationLead;
use App\Models\ContactMessage;
use App\Models\Office;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class EnquiryNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ContentSeeder::class);
        Mail::fake();
    }

    public function test_a_contact_message_notifies_the_office(): void
    {
        $this->post('/contact', [
            'name' => 'QA Visitor',
            'email' => 'qa@example.com',
            'message' => 'Please share the services you offer.',
        ])->assertSessionHas('success');

        Mail::assertSent(NewContactMessage::class, fn (NewContactMessage $mail) => $mail->hasTo('updatearchitects120@gmail.com')
            && $mail->hasReplyTo('qa@example.com')
            && $mail->contactMessage->email === 'qa@example.com');
    }

    public function test_the_contact_notification_template_renders(): void
    {
        $this->post('/contact', [
            'name' => 'QA Visitor',
            'email' => 'qa@example.com',
            'subject' => 'General enquiry',
            'message' => 'Please share the services you offer.',
        ]);

        $html = (new NewContactMessage(ContactMessage::firstOrFail()))->render();

        $this->assertStringContainsString('QA Visitor', $html);
        $this->assertStringContainsString('General enquiry', $html);
        $this->assertStringContainsString('Open in the CMS', $html);
    }

    public function test_a_consultation_lead_notifies_the_office(): void
    {
        $this->post('/start-your-project', [
            'name' => 'QA Visitor',
            'phone' => '01700000000',
            'email' => 'qa@example.com',
            'project_type' => 'residential',
            'message' => 'We need architectural and structural consultancy.',
            'consent' => '1',
        ])->assertSessionHas('success');

        Mail::assertSent(NewConsultationLead::class, fn (NewConsultationLead $mail) => $mail->hasTo('updatearchitects120@gmail.com')
            && $mail->hasReplyTo('qa@example.com')
            && $mail->lead->project_type === 'residential');
    }

    public function test_the_consultation_notification_template_renders(): void
    {
        $this->post('/start-your-project', [
            'name' => 'QA Visitor',
            'phone' => '01700000000',
            'email' => 'qa@example.com',
            'message' => 'We need architectural and structural consultancy.',
            'consent' => '1',
        ]);

        $html = (new NewConsultationLead(ConsultationLead::firstOrFail()))->render();

        $this->assertStringContainsString('QA Visitor', $html);
        $this->assertStringContainsString('01700000000', $html);
        $this->assertStringContainsString('Open in the CMS', $html);
    }

    public function test_rejected_submissions_send_no_mail(): void
    {
        $this->post('/contact', []);

        Mail::assertNothingSent();
    }

    public function test_no_notification_is_attempted_without_a_configured_office_email(): void
    {
        Office::query()->update(['email' => null]);

        $this->post('/contact', [
            'name' => 'QA Visitor',
            'email' => 'qa@example.com',
            'message' => 'Please share the services you offer.',
        ])->assertSessionHas('success');

        Mail::assertNothingSent();
    }

    public function test_a_mail_failure_never_loses_the_enquiry(): void
    {
        Mail::shouldReceive('to')->andThrow(new RuntimeException('SMTP unavailable'));

        $this->post('/contact', [
            'name' => 'QA Visitor',
            'email' => 'qa@example.com',
            'message' => 'Please share the services you offer.',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', ['email' => 'qa@example.com']);
    }
}
