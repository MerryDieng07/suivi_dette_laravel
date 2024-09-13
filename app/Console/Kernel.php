<?php

namespace App\Console;

use App\Jobs\EnvoyerSmsAuxClients;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Planifier le job tous les vendredis à 14h
        $schedule->job(new EnvoyerSmsAuxClients)->fridays()->at('14:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
