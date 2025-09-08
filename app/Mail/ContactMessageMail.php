<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    // Estas propiedades públicas estarán disponibles en la vista como $name, $email, etc.
    public string $name;
    public string $email;
    public string $message;
    public string $ip;
    public string $userAgent;
    public string $createdAt;
    public string $subjectLine;

    public function __construct(public ContactMessage $contact)
    {
        $this->name       = (string) $contact->name;
        $this->email      = (string) $contact->email;
        $this->message    = (string) $contact->message;
        $this->ip         = (string) ($contact->ip ?? '');
        $this->userAgent  = (string) ($contact->user_agent ?? '');
        $this->createdAt  = now()->toDateTimeString();
        $this->subjectLine= (string) ($contact->subject ?: 'Nuevo mensaje de contacto');
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->subjectLine);
    }

    public function content(): Content
    {
        // No usamos "with": las props públicas ya están disponibles en la vista
        return new Content(
            markdown: 'emails.contact_message',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
