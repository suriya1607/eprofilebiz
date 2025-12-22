<?php

namespace App\Console;

use App\Console\Commands\GenerateSiteMap;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\PlanExpirationMailCommand;
use App\Console\Commands\SendCardNotCreatedReminder;
use App\Console\Commands\DailyCardViewNotificationCommand;
use App\Console\Commands\WeeklyCardViewNotificationCommand;
use App\Console\Commands\SendCardNotDownloadedReminder;
use App\Console\Commands\PaymentReminderCommand;
use App\Console\Commands\DailyWhatsappShareNotificationCommand;
use App\Console\Commands\WeeklyWhatsappShareNotificationCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GenerateSiteMap::class,
        PlanExpirationMailCommand::class,
        SendCardNotCreatedReminder::class,
        DailyCardViewNotificationCommand::class,
        WeeklyCardViewNotificationCommand::class,
        SendCardNotDownloadedReminder::class,
        PaymentReminderCommand::class,
        DailyWhatsappShareNotificationCommand::class,
        WeeklyWhatsappShareNotificationCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('sitemap:generate')->daily();
        $schedule->command(PlanExpirationMailCommand::class)->daily();
        $schedule->command(PaymentReminderCommand::class)->daily();
        $schedule->command(SendCardNotCreatedReminder::class)->daily();
        $schedule->command(SendCardNotDownloadedReminder::class)->daily();
        $schedule->command(DailyCardViewNotificationCommand::class)->daily();
        $schedule->command(WeeklyCardViewNotificationCommand::class)->weeklyOn(0, '09:00');
        $schedule->command(DailyWhatsappShareNotificationCommand::class)->daily();
        $schedule->command(WeeklyWhatsappShareNotificationCommand::class)->weeklyOn(0, '10:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
