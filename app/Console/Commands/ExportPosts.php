<?php

namespace App\Console\Commands;

use App\Exports\PostExport;
use App\Services\StorageService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

use function PHPUnit\Framework\isEmpty;

class ExportPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post-export {startdate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get weekly excel output of posts from a specific date';

    /**
     * Execute the console command.
     */
    public function handle(StorageService $storage_service)
    {
        $start_date = $this->argument('startdate');
        try
        {
            $parsedDate = Carbon::createFromFormat('Y-m-d', $start_date);
        }
        catch(Exception $e)
        {
            $this->error("The provided date is invalid. Please use the format Y-m-d.");
            return 1;
        }
        
        // get the date of first excel file in the system
        $export_files = $storage_service->readStorageFiles();
        $files_without_extension = collect($export_files)->map(function ($file) {
            return pathinfo($file, PATHINFO_FILENAME); 
        });
        $oldest_file_date = $files_without_extension->sortByDesc('date')->first();
        if(isEmpty($oldest_file_date) == true)
        {
            $oldest_file_date = now()->format('Y-m-d');
        }

        // start extraction files from the given date till the first exists date
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($oldest_file_date);

        $number_of_days = $start_date->diffInDays($end_date);
        $number_of_weeks = ceil($number_of_days / 7);
        for($i = 0; $i < $number_of_weeks; $i++)
        {
            $week_start_date = $start_date->copy()->addDays($i * 7);
            $week_end_date = $week_start_date->copy()->addDays(6);
            if($week_end_date > $end_date)
            {
                $week_end_date = $end_date;
            }
            
            $path = $week_start_date->format('Y-m-d'). '.xlsx';
            Excel::store(new PostExport($week_start_date, $week_end_date), $path);
            $this->info('file : '. $path . ' added');
            
        }
        
    }
}
