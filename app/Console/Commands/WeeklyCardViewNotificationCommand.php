<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Vcard;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\CardViewMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Analytic;
class WeeklyCardViewNotificationCommand extends Command
{
    protected $signature = 'notify:card-views-weekly';
    protected $description = 'Send weekly vCard view notification';

    public function handle()
    {
        $vcards = Vcard::with('user')->get();

        foreach ($vcards as $vcard) {
            $user = $vcard->user;
            if (!$user) {
                continue;
            }

            $count = Analytic::where('vcard_id', $vcard->id)
                ->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ])
                ->count();
            if ($count > 0) {
                Mail::to($user->email)->send(
                    new CardViewMail($user, $vcard, $count, 'weekly')
                );
            }
        }
    }
}
