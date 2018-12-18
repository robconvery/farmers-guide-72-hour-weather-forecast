<?php
/**
 * Class ForecastAdapterTest
 *
 * @package Robconvery\FarmersGuideForecast\Tests\Unit
 * @author robconvery <robconvery@me.com>
 */

namespace Robconvery\FarmersGuideForecast\Tests\Unit;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Orchestra\Testbench\TestCase;
use Robconvery\FarmersGuideForecast\FakeWeatherGateway;
use Robconvery\FarmersGuideForecast\ForecastAdapter;
use Robconvery\FarmersGuideForecast\Interfaces\GatewayInterface;
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

    /**
     * @test
     * @group creates_new_instance_of_forecast_adapter
     */
    public function creates_new_instance_of_forecast_adapter()
    {
        // Arrange

        // Act
        $Adapter = $this->app->make(ForecastAdapter::class);

        // Assert
        $this->assertInstanceOf(ForecastAdapter::class, $Adapter);
    }

    /**
     * @test
     * @group forecasts_match_those_from_fake_gateway
     */
    public function forecasts_match_those_from_fake_gateway()
    {
        // Arrange
        $this->app->instance(GatewayInterface::class, new FakeWeatherGateway());
        $postcode = 'BB18 5QT';

        // Act
        $Adapter = $this->app->make(ForecastAdapter::class, [$postcode]);

        // Assert
        $this->assertInstanceOf(ForecastAdapter::class, $Adapter);
        $this->assertInstanceOf(Collection::class, $Adapter->forecasts());
        $this->assertCount(count(FakeWeatherGateway::testData()), $Adapter->forecasts());

        $lastDate = null;

        $Adapter->forecasts()->map(function ($Forecast, $index) use(&$lastDate, $postcode) {

            $this->assertInstanceOf(ForecastAdapter::class, $Forecast);
            $data = FakeWeatherGateway::testData()[$index];

            // should be no forecasts
            $this->assertCount(0, $Forecast->forecasts());

            // test location is correct
            $this->assertEquals($postcode, $Forecast->location());

            if ($data[0] === null) {
                $data[0] = $lastDate;
            } else {
                $lastDate = $data[0];
            }

            list($day, $month) = explode('/', $data[0]);
            $day = (int)$day;
            $month = (int)$month;

            list($hour, $minute) = explode(':', $data[1]);
            $hour = (int)$hour;
            $minute = (int)$minute;

            $datetime = Carbon::create(date('Y'), $month, $day);
            $datetime->hour = $hour;
            $datetime->minute = $minute;

            // test datetime is same
            $this->assertEquals($datetime, $Forecast->datetime());

            $temp = round(preg_replace('/[^0-9\.\-]/', '', $data[3]), 0);
            // test temperature
            $this->assertEquals($temp, $Forecast->temperature());
            // test minimum temperature
            $this->assertEquals($temp, $Forecast->temperatureMin());
            // test maximum temperature
            $this->assertEquals($temp, $Forecast->temperatureMax());

            $rain = (int)preg_replace('/[^0-9\.]/', '', $data[4]);
            $cloud = (int)preg_replace('/[^0-9\.]/', '', $data[6]);
            $main = '';
            if ($rain > 0) {
                $main = $rain <= 2 ? 'Light rain' : 'Rain ' . $rain . 'mm';
            } else {
                $main = $cloud == 0 ? 'Clear Skies' : 'Cloudy';
            }


            $this->assertEquals($main, $Forecast->main());
            $this->assertEquals($main, $Forecast->description());

        });
    }
}
