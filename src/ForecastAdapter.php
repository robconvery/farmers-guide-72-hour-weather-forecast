<?php declare(strict_types=1);

/**
 * Class ForecastAdapter
 *
 * @package Robconvery\FarmersGuideForecast
 * @author robconvery <robconvery@me.com>
 */

namespace Robconvery\FarmersGuideForecast;

use Carbon\Carbon;
use Robconvery\FarmersGuideForecast\Interfaces\AdapterInterface;
/*use Robertconvery\WeatherAdapterInterface\WeatherAdapterInterface;*/

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

    /**
     * @return string
     */
    public function location(): string
    {
        //
    }

    /**
     * @return Carbon
     */
    public function datetime(): Carbon
    {

    }

    /**
     * @return string
     */
    public function main(): string
    {
        //
    }

    /**
     * @return string
     */
    public function description(): string
    {
        //
    }

    /**
     * @return int
     */
    public function temperature(): int
    {
        //
    }

    /**
     * @return int
     */
    public function temperatureMin(): int
    {
        //
    }

    /**
     * @return int
     */
    public function temperatureMax(): int
    {
        //
    }

}
