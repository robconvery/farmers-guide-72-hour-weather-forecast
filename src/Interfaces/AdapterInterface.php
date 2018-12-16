<?php declare(strict_types=1);
/**
 * Interface AdapterInterface
 * @package Robconvery\FarmersGuideForecast\Interfaces
 */

namespace Robconvery\FarmersGuideForecast\Interfaces;

interface AdapterInterface
{
    /**
     * Append a forecast to the list of forecasts
     * @param AdapterInterface $adapter
     * @return mixed
     */
    public function attach(AdapterInterface $adapter);

    /**
     * @return mixed
     */
    public function get();

    /**
     * Counts the number of forecasts attached
     * @return int
     */
    public function forecasts(): int;
}
