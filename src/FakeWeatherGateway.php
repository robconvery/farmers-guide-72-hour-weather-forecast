<?php
/**
 * Class FakeWeatherGateway
 *
 * @package Robconvery\FarmersGuideForecast\Interfaces
 * @author robconvery <robconvery@me.com>
 */

namespace Robconvery\FarmersGuideForecast;

use Illuminate\Support\Collection;
use Robconvery\FarmersGuideForecast\Interfaces\GatewayInterface;

class FakeWeatherGateway implements GatewayInterface
{
    /**
     * @param string $postcode
     * @return GatewayInterface
     */
    public function getForecast(string $postcode): GatewayInterface
    {
        return $this;
    }

    /**
     * @return Collection
     */
    public function extract(): Collection
    {
        return collect(static::testData())->map(function ($row) {
            return collect($row);
        });
    }

    /**
     * @return array
     */
    public static function testData(): array
    {
        return include dirname(__DIR__) . '/config/farmers_guide_test_data.php';
    }
}
