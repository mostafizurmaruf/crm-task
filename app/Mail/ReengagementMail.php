<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReengagementMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Customer $customer,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "We Miss You, {$this->customer->name}!",
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtml(),
        );
    }

    private function buildHtml(): string
    {
        return <<<HTML
        <h2>We Miss You, {$this->customer->name}!</h2>
        <p>It's been a while since your last visit. We'd love to have you back!</p>
        <p>Check out our latest products and exclusive offers.</p>
        <p>Thank you for being a valued customer.</p>
        HTML;
    }

    public function attachments(): array
    {
        return [];
    }
}
