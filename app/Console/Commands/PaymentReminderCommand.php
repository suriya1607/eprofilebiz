<?php

namespace App\Console\Commands;

use Exception;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\PaymentReminder;
use Illuminate\Support\Facades\Mail;

class PaymentReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:payment-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Mail Before payment Due Date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $users =  User::role('admin')
            ->with(['subscriptions' => function ($q) {
                $q->where('status', 1)
                ->latest('ends_at');
            }])->get();
            foreach ($users as $user) {
                $expirationDate = Carbon::parse($user->subscription->ends_at);
                $data = [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name
                ];
                if (now() > $expirationDate) {
                    Mail::to($user->email)->send(new PaymentReminder($data));
                }
            }
            Log::info('Payment reminder sent successfully');
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
