<?php

namespace Robconvery\FarmersGuideForecast;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Robconvery\FarmersGuideForecast\Interfaces\GatewayInterface;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/../config/farmers_guide.php' => config_path('farmers_guide.php'),
        ], 'config');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // associate `FarmersGuideGateway` with `GatewayInterface`
        $this->app->bind(GatewayInterface::class, function () {
            //
        });
    }
}
