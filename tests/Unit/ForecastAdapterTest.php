<?php
/**
 * Class ForecastAdapterTest
 *
 * @package Robconvery\FarmersGuideForecast\Tests\Unit
 * @author robconvery <robconvery@me.com>
 */

namespace Robconvery\FarmersGuideForecast\Tests\Unit;

use Orchestra\Testbench\TestCase;
use Robconvery\FarmersGuideForecast\ForecastAdapter;
use Robconvery\FarmersGuideForecast\PackageServiceProvider;

class ForecastAdapterTest extends TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [PackageServiceProvider::class];
    }

    /**
     * @test
     * @group instantiate_forecast_adapter
     * @group live
     */
    public function instantiate_forecast_adapter()
    {
        // Arrange
        $postcode = 'BB18 5QT';

        // Act
        $Adapter = $this->app->make(ForecastAdapter::class, [$postcode]);

        // Assert
        $this->assertInstanceOf(ForecastAdapter::class, $Adapter);
    }

}
