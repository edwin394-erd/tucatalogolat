<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('actualizarsubs', function () {
    $this->call('App\Console\Commands\UpdateSubscriptionStatus');
    $this->comment('Subscription statuses updated successfully.');
})->purpose('Update subscription status based on expiration date');

Schedule::command('actualizarsubs')->everyminute();

// en produccion usar cron job para ejecutar el comando cada minuto