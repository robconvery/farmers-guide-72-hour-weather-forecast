<?php

namespace Robconvery\FarmersGuideForecast;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Robconvery\FarmersGuideForecast\Interfaces\AdapterInterface;
use Robconvery\FarmersGuideForecast\Interfaces\GatewayInterface;
use Robertconvery\WeatherAdapter\AbstractWeatherAdapter;

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
            return new FarmersGuideGateway();
        });

        // return a `ForecastAdapter`, populate if `postcode` is passed.
        $this->app->bind(ForecastAdapter::class, function ($app, $params) {

            $validDate =null;
            $postcode = current($params);
            $ForecastAdapter = app()->make(AdapterInterface::class);

            if (is_string($postcode) && strlen(trim($postcode))) {
                app()->make(GatewayInterface::class)
                    ->getForecast($postcode)
                    ->extract()
                    ->map(function ($forecast) use (&$ForecastAdapter, $postcode, &$validDate) {

                        $data = $forecast->toArray();
                        if (isset($data[0])) {
                            $validDate = $data[0];
                        } else {
                            $data[0] = $validDate;
                        }

                        $ForecastAdapter->attach(
                            app()->make(AdapterInterface::class, [
                                array_merge([
                                    'location' => $postcode
                                ], $data)
                            ])
                        );
                    });
            }
            return $ForecastAdapter;
        });

        // associate `ForecastAdapter` with `AdapterInterface`
        $this->app->bind(AdapterInterface::class, function ($app, $params) {
            return new ForecastAdapter(is_array(current($params)) ? current($params) : null);
        });
    }
}
