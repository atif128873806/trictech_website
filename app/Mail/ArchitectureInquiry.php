<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ArchitectureInquiry extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $inquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            // Replies from any recipient go back to the visitor, not to the mailbox
            // that happened to receive the notification.
            replyTo: [$this->inquiry['email']],
            subject: 'Website inquiry: '.(($this->inquiry['interest'] ?? null) ?: 'General inquiry'),
        );
    }

    public function content(): Content
    {
        return new Content(text: 'mail.architecture-inquiry');
    }
}
