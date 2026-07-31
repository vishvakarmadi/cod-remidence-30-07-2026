<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        
        // Self-healing database check to add 'coupon_code' column to 'wallet_recharge' if not exists
        try {
            if (!\Schema::hasColumn('wallet_recharge', 'coupon_code')) {
                \Schema::table('wallet_recharge', function ($table) {
                    $table->string('coupon_code')->nullable();
                });
            }
        } catch (\Exception $e) {
            // Silence
        }

        try {
            \File::put(base_path('debug_bd.json'), json_encode([
                'db_integrations' => \DB::table('integrations')->where('courier_id', 3)->get(),
                'env_bluedart_customer_code' => env('BLUEDART_CUSTOMER_CODE'),
                'env_bluedart_origin_area' => env('BLUEDART_ORIGIN_AREA'),
                'api_logs' => \DB::table('api_logs')->where('courier_id', 3)->orderBy('id', 'desc')->limit(10)->get()
            ], JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            \File::put(base_path('debug_bd.json'), json_encode(['error' => $e->getMessage()]));
        }
    }
}
