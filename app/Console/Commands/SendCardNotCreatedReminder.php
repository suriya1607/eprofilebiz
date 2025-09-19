<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\DigitalCardReminder;
use Illuminate\Support\Facades\Mail;




class SendCardNotCreatedReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-card-not-created-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
            $users = User::doesntHave('vcard')->where('id',517)->get();
            if ($users->isNotEmpty()) {
                foreach ($users as $user) {
                    $data = [
                        'first_name' => $user->first_name,
                        'last_name'  => $user->last_name,
                    ];

                    Mail::to($user->email)->send(new DigitalCardReminder($data));
                }
            }
    }
}
