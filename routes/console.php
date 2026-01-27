<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automatic database backups
Schedule::command('backup:create')
    ->daily()
    ->at('02:00')
    ->timezone('Africa/Dar_es_Salaam')
    ->emailOutputOnFailure('lauparadiseadventure@gmail.com')
    ->description('Automatic daily database backup');
