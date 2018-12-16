<?php declare(strict_types=1);

/**
 * Class ForecastAdapter
 *
 * @package Robconvery\FarmersGuideForecast
 * @author robconvery <robconvery@me.com>
 */

namespace Robconvery\FarmersGuideForecast;

use Robconvery\FarmersGuideForecast\Interfaces\AdapterInterface;

class ForecastAdapter implements AdapterInterface
{
    /**
     * @var array
     */
    protected $data=[];

    /**
     * ForecastAdapter constructor.
     * @param array $data
     */
    public function __construct(array $data = null)
    {
        $this->data = $data;
    }

    /**
     * Append a forecast to the list of forecasts
     * @param AdapterInterface $adapter
     * @return mixed
     */
    public function attach(AdapterInterface $adapter)
    {
        $this->data[] = $adapter;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        //
    }

    /**
     * Number of forecasts found
     * @return int
     */
    public function forecasts(): int
    {
        return count($this->data);
    }
}
