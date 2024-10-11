<?php

namespace App\Jobs;

use App\Exports\PostExport;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Maatwebsite\Excel\Facades\Excel;

class ExportPostsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $last_date = now()->previous(Carbon::SATURDAY)->format('Y-m-d');
        $today_date = now()->format('Y-m-d');
        $path = $last_date. '.xlsx';
        Excel::store(new PostExport($last_date, $today_date), $path);
    }
}
