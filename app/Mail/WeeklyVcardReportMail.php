<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;


class WeeklyVcardReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * CSV file name to attach
     */
    public string $file;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param string $file  CSV file name stored in storage/app/public
     */
    public function __construct($user,string $file)
    {
        $this->file = $file;
        $this->user = $user;
    }

    /**
     * Get the message envelope (subject, from, etc.)
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Weekly vCard Report'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.weekly_vcard_report',
            with: [
                'user' => $this->user,
            ]
        );
    }

    /**
     * Attach weekly CSV report.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath(
                 Storage::disk('public')->path($this->file)
            )->as('weekly_vcard_report.csv')
             ->withMime('text/csv')
        ];
    }
}
