<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vcard;
use App\Models\VcardSendersList;
use Illuminate\Support\Facades\Mail;
use App\Mail\WhatsappShareMail;

class DailyWhatsappShareNotificationCommand extends Command
{
    protected $signature = 'notify:whatsapp-share-daily';
    protected $description = 'Daily WhatsApp share notification for vCards';

    public function handle()
    {
        $vcards = Vcard::with('user')->get();

        foreach ($vcards as $vcard) {
            $user = $vcard->user;
            if (!$user) continue;

            $count = VcardSendersList::where('vcard_id', $vcard->id)
                ->whereDate('created_at', today())
                ->count();

            if ($count > 0) {
                Mail::to($user->email)->send(
                    new WhatsappShareMail($user, $vcard, $count, 'daily')
                );
            }
        }
    }
}
