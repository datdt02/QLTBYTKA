<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Liquidation\LiquidationRepository;
use App\Repositories\Liquidation\LiquidationRepositoryInterface;
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\Liquidation\LiquidationRepositoryInterface',
            'App\Repositories\Liquidation\LiquidationRepository');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
