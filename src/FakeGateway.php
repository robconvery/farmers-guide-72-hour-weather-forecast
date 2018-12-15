<?php declare(strict_types=1);
/**
 * Class FakeGateway
 *
 * @package Robconvery\FarmersGuideForecast
 * @author robconvery <robconvery@me.com>
 */

namespace Robconvery\FarmersGuideForecast;

use Robconvery\FarmersGuideForecast\Interfaces\AdapterInterface;
use Robconvery\FarmersGuideForecast\Interfaces\GatewayInterface;

class FakeGateway implements GatewayInterface
{
    /**
     * @param string $postcode
     * @return ForecastAdapter
     */
    public function getForecast(string $postcode): AdapterInterface
    {
        return new ForecastAdapter();
    }

}
