<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class CardViewMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $vcard;
    public $count;
    public $type;

    public function __construct($user, $vcard, $count, $type)
    {
        $this->user  = $user;
        $this->vcard = $vcard;
        $this->count = $count;
        $this->type  = $type;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->type === 'daily'
                ? __('messages.mail.card_view_daily_title')
                : __('messages.mail.card_view_weekly_title')
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.card_view_notification',
            with: [
                'user'  => $this->user,
                'vcard' => $this->vcard,
                'count' => $this->count,
                'type'  => $this->type,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
