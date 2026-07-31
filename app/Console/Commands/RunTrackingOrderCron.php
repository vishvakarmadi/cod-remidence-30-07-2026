<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\CronController;

class RunTrackingOrderCron extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'cron:tracking-order';

    /**
     * The console command description.
     */
    protected $description = 'Run tracking order cron to update shipment statuses from courier APIs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('[' . now() . '] Running tracking order cron...');

        try {
            // Direct controller call — no HTTP request needed
            ob_start();
            app(CronController::class)->trackingorder();
            $output = ob_get_clean();

            $this->info('Output: ' . strip_tags($output));
            $this->info('[' . now() . '] Done.');

        } catch (\Exception $e) {
            $this->error('Exception: ' . $e->getMessage());
            \Log::error('Tracking cron failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
