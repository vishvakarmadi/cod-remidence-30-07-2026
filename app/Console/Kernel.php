<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // ✅ Tracking Order — har 30 second mein run hoga (Laravel 8 compatible)
        // First run: immediately at minute start
        $schedule->command('cron:tracking-order')
            ->everyMinute()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/tracking_cron.log'));

        // Second run: 30 seconds after minute start (sleep workaround for Laravel 8)
        $schedule->exec('sleep 30 && php ' . base_path('artisan') . ' cron:tracking-order')
            ->everyMinute()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/tracking_cron.log'));



        // ✅ MIS Report Mail — roz raat 11:30 baje
        $schedule->call(function () {
            app(\App\Http\Controllers\Admin\CronController::class)->mailmisreport();
        })->dailyAt('23:30')->name('mail-mis-report')->withoutOverlapping();

        // ✅ NDR mark for Delhivery — roz subah 6:00 baje
        $schedule->call(function () {
            app(\App\Http\Controllers\Admin\CronController::class)->markndrfordelhvery();
        })->dailyAt('06:00')->name('mark-ndr-delhivery')->withoutOverlapping();

        // ✅ Shopify Order Sync — har 10 minute mein
        $schedule->call(function () {
            app(\App\Http\Controllers\Admin\CronController::class)->getordershopify();
        })->everyTenMinutes()->name('shopify-order-sync')->withoutOverlapping();

        // ✅ Update Shopify Fulfillment — har 15 minute mein
        $schedule->call(function () {
            app(\App\Http\Controllers\Admin\CronController::class)->updatedetailsonshopify();
        })->everyFifteenMinutes()->name('shopify-fulfillment')->withoutOverlapping();
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
