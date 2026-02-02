<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Vcard;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\WeeklyVcardReportMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Analytic;
use Illuminate\Support\Facades\Storage;

class WeeklyVcardReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:weekly-vcard-report-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly vCard report';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = now()->startOfWeek();
        $end   = now()->endOfWeek();

        $vcards = Vcard::with('user')
            ->get();

        foreach ($vcards as $vcard) {

            $user = $vcard->user;
            if (!$user) continue;

            // 🔹 Analytics (Day-wise views for this vCard)
            $views = DB::table('analytics')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as views')
                ->where('vcard_id', $vcard->id)
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('date')
                ->pluck('views', 'date');

            // 🔹 WhatsApp Shares (Day-wise for this vCard)
            $whatsapp = DB::table('vcardsenders_list')
                ->selectRaw('
                    DATE(created_at) as date,
                    senders_name,
                    senders_number,
                    CASE 
                        WHEN visited = 1 THEN "Opened"
                        ELSE "Unopened"
                    END as status
                ')
                ->where('vcard_id', $vcard->id)
                ->whereBetween('created_at', [$start, $end])
                ->get()
                ->groupBy('date');

            //No data → skip mail
            if ($views->isEmpty() && $whatsapp->isEmpty()) {
                continue;
            }

            //  Build CSV
            $csv = "Date,vCard Views,Sender Name,Sender Number,WP Status\n";

            $dates = collect()
                ->merge($views->keys())
                ->merge($whatsapp->keys())
                ->unique()
                ->sort();

            foreach ($dates as $date) {

                $viewCount = $views[$date] ?? '-';

                if (isset($whatsapp[$date])) {
                    foreach ($whatsapp[$date] as $row) {
                        $csv .= "{$date},{$viewCount},{$row->senders_name},{$row->senders_number},{$row->status}\n";
                    }
                } else {
                    $csv .= "{$date},{$viewCount},-,-,-\n";
                }
            }

            // Save file per vCard
            $file = 'weekly_vcard_report_vcard_' . $vcard->id . '_' . now()->format('Y_m_d') . '.csv';
            Storage::disk('public')->put($file, $csv);

            //Send mail to vCard owner
            Mail::to($user->email)
                ->send(new WeeklyVcardReportMail($user,$file));

            if (Storage::disk('public')->exists($file)) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
