<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vcard;
use App\Models\VcardSendersList;
use Illuminate\Support\Facades\Mail;
use App\Mail\WhatsappShareMail;

class WeeklyWhatsappShareNotificationCommand extends Command
{
    protected $signature = 'notify:whatsapp-share-weekly';
    protected $description = 'Weekly WhatsApp share notification for vCards';

    public function handle()
    {
        $vcards = Vcard::with('user')->get();

        foreach ($vcards as $vcard) {
            $user = $vcard->user;
            if (!$user) continue;

            $count = VcardSendersList::where('vcard_id', $vcard->id)
                ->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])
                ->count();

            if ($count > 0) {
                Mail::to($user->email)->send(
                    new WhatsappShareMail($user, $vcard, $count, 'weekly')
                );
            }
        }
    }
}
