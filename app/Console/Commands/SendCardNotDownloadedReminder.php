<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\CardNotDownloadedReminder;
use Illuminate\Support\Facades\Mail;

class SendCardNotDownloadedReminder extends Command
{
    protected $signature = 'app:send-card-not-downloaded-reminder';

    protected $description = 'Send reminder emails to users who created a digital card but have not downloaded it';

    public function handle()
    {
        $users = User::whereHas('vcard', function ($q) {
                        $q->where('is_downloaded', 0);
                    })->get();
        if ($users->isNotEmpty()) {
            foreach ($users as $user) {
                $data = [
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'alias'   => $user->vcard->name,
                ];

                Mail::to($user->email)->send(new CardNotDownloadedReminder($data));
            }
            $this->info("Reminder emails sent successfully!");
        } else {
            $this->info("No users found with undownloaded cards.");
        }
    }
}
