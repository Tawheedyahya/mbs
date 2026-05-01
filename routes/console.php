<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Daily appointment summary — 8:00 AM every day ────────────
Schedule::command('mbs:daily-summary')
    ->dailyAt('08:00')
    ->timezone('Asia/Kuala_Lumpur')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/daily-summary.log'));

// ── Weekly financial report — Every Monday 8:00 AM ───────────
Schedule::command('mbs:weekly-financial')
    ->weeklyOn(1, '08:00') // 1 = Monday
    ->timezone('Asia/Kuala_Lumpur')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/weekly-report.log'));