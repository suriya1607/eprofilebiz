<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class WhatsappShareMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $vcard;
    public $count;
    public $type;
    public $messageKey;
    public $noteKey;

    public function __construct($user, $vcard, $count, $type)
    {
        $this->user  = $user;
        $this->vcard = $vcard;
        $this->count = $count;
        $this->type  = $type;

        // Language keys
        $this->messageKey = $type === 'daily'
            ? 'messages.mail.whatsapp_share_daily_msg'
            : 'messages.mail.whatsapp_share_weekly_msg';

        $this->noteKey = 'messages.mail.whatsapp_share_note';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->type === 'daily'
                ? 'Daily WhatsApp Share Update'
                : 'Weekly WhatsApp Share Summary'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.whatsapp_share_notification',
            with: [
                'user'       => $this->user,
                'vcard'      => $this->vcard,
                'count'      => $this->count,
                'type'       => $this->type,
                'messageKey' => $this->messageKey,
                'noteKey'    => $this->noteKey,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
