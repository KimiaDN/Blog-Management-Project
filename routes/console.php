<?php

use App\Jobs\ExportPostsJob;
use App\Jobs\ExportPostsJob1;
use App\Jobs\PublishPostJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule::job(new ExportPostsJob())->weeklyOn('6', '0:00');
Schedule::command('app:long-command')->everyMinute();
Schedule::command('app:short-command')->everyFifteenSeconds()->runInBackground();
