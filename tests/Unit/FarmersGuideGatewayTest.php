<?php
/**
 * Class FarmersGuideGatewayTest
 *
 * @package Robconvery\FarmersGuideForecast\Tests\Unit
 * @author robconvery <robconvery@me.com>
 */

namespace Robconvery\FarmersGuideForecast\Tests\Unit;

use Orchestra\Testbench\TestCase;
use Robconvery\FarmersGuideForecast\FarmersGuideGateway;
use Robconvery\FarmersGuideForecast\Interfaces\GatewayInterface;
use Robconvery\FarmersGuideForecast\PackageServiceProvider;

class FarmersGuideGatewayTest extends TestCase
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
     * @group get_forecast_returns_response
     * @group live
     */
    public function get_forecast_returns_response()
    {
        // Arrange
        $gateway = new FarmersGuideGateway();
        $postcode = 'BB185QT';

        // Act
        $response = $gateway->getForecast($postcode);

        // Assert
        $this->assertInstanceOf(\GuzzleHttp\Psr7\Response::class, $response->getResponse());
    }

    /**
     * @test
     * @group get_live_data_from_farmers_guide
     * @group live
     */
    public function get_live_data_from_farmers_guide()
    {
        // Arrange
        $gateway = new FarmersGuideGateway();
        $this->assertInstanceOf(GatewayInterface::class, $gateway);
        $postcode = 'BB185QT';

        // Act
        $forecast = $gateway->getForecast($postcode);

        // Assert
        $this->assertInstanceOf(GatewayInterface::class, $forecast);
    }
}
