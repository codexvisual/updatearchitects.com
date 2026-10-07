<?php

namespace App\Mail;

use App\Models\ConsultationLead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewConsultationLead extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly ConsultationLead $lead) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New consultation request from '.$this->lead->name,
            replyTo: [$this->lead->email],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(view: 'emails.consultation-lead');
    }
}
