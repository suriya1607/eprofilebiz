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
class DailyCardViewNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-card-view-notification-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily vCard view notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $vcards = Vcard::with('user')->get();

        foreach ($vcards as $vcard) {
            $user = $vcard->user;
            if (!$user) {
                continue;
            }

            $count = Analytic::where('vcard_id', $vcard->id)
                ->whereDate('created_at', today())
                ->count();
            if ($count > 0) {
                Mail::to($user->email)->send(
                    new CardViewMail($user, $vcard, $count, 'daily')
                );
            }
        }
    }
}
