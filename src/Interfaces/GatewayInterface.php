<?php declare(strict_types=1);
/**
 * Interface GatewayInterface
 * @package Robconvery\FarmersGuideForecast\Interfaces
 */

namespace Robconvery\FarmersGuideForecast\Interfaces;

interface GatewayInterface
{
    /**
     * @param string $postcode
     * @return ForecastAdapter
     */
    public function getForecast(string $postcode): AdapterInterface;
}
