<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResourceAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $resourceType;
    public $recommendation;

    /**
     * Create a new message instance.
     */
    public function __construct($resourceType, $recommendation)
    {
        $this->resourceType = $resourceType;
        $this->recommendation = $recommendation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Resource Alert',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        return $this->subject('High Demand Alert for ' . ucfirst($this->resourceType))
                    ->view('emails.resource_alert');
    }
}
