<?php

namespace Robconvery\FarmersGuideForecast\Tests;

use Orchestra\Testbench\TestCase;
use Robconvery\FarmersGuideForecast\FakeGateway;
use Robconvery\FarmersGuideForecast\ForecastAdapter;
use Robconvery\FarmersGuideForecast\Interfaces\GatewayInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForecastGatewayTest extends TestCase
{
    /**
     * @test
     * @group get_72_hours_forecast
     */
    public function get_72_hours_forecast()
    {
        // Arrange
        $this->app->instance(
            'Robconvery\FarmersGuideForecast\Interfaces\GatewayInterface',
            new FakeGateway()
        );
        $gateway = $this->app->make(GatewayInterface::class);
        $this->assertInstanceOf(GatewayInterface::class, $gateway);
        $postcode = 'BB185QT';

        // Act
        $response = $gateway->getForecast($postcode);

        // Assert
        $this->assertInstanceOf(ForecastAdapter::class, $response);
    }
}
