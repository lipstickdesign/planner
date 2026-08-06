<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Synk kamper fra fotball.no automatisk hver morgen (og tidlig ettermiddag,
// så flyttede kamper fanges opp samme dag).
Schedule::command('kamper:import')->dailyAt('06:00')->withoutOverlapping();
Schedule::command('kamper:import')->dailyAt('13:00')->withoutOverlapping();
