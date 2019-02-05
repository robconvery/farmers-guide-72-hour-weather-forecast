<?php declare(strict_types=1);

/**
 * Class ForecastAdapter
 *
 * @package Robconvery\FarmersGuideForecast
 * @author robconvery <robconvery@me.com>
 */

namespace Robconvery\FarmersGuideForecast;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Robconvery\FarmersGuideForecast\Interfaces\AdapterInterface;
use Robconvery\WeatherAdapter\WeatherAdapterInterface;

class ForecastAdapter implements AdapterInterface, WeatherAdapterInterface
{
    /**
     * @var array
     */
    protected $data=[];
    protected $forecasts=[];

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
     * @param WeatherAdapterInterface $adapter
     * @return mixed
     */
    public function attach(WeatherAdapterInterface $adapter)
    {
        $this->forecasts[] = $adapter;
    }

    /**
     * Number of forecasts found
     * @return Collection
     */
    public function forecasts(): Collection
    {
        return is_array($this->forecasts) ? collect($this->forecasts) : collect();
    }

    /**
     * @return string
     */
    public function location(): string
    {
        return isset($this->data['location']) ? $this->data['location'] : '';
    }

    /**
     * @return Carbon
     */
    public function datetime(): Carbon
    {
        if (!isset($this->data[0])) {
            throw new \RuntimeException('Missing day and month data');
        }

        if (!isset($this->data[1])) {
            throw new \RuntimeException('Missing hour and minute data');
        }

        list($day, $month) = explode('/', $this->data[0]);
        $day = (int)$day;
        $month = (int)$month;

        list($hour, $minute) = explode(':', $this->data[1]);
        $hour = (int)$hour;
        $minute = (int)$minute;

        $datetime = Carbon::create(date('Y'), $month, $day);
        $datetime->hour = $hour;
        $datetime->minute = $minute;

        return $datetime;
    }

    /**
     * @return string
     */
    public function main(): string
    {
        return $this->getTextual();
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->getTextual();
    }

    /**
     * @return int
     */
    public function temperature(): int
    {
        if (!isset($this->data[3])) {
            throw new \RuntimeException('Missing temperature data');
        }
        return (int)round(preg_replace('/[^0-9\.\-]/', '', $this->data[3]), 0);
    }

    /**
     * @return int
     */
    public function temperatureMin(): int
    {
        if (!isset($this->data[3])) {
            throw new \RuntimeException('Missing temperature data');
        }
        return (int)round(preg_replace('/[^0-9\.\-]/', '', $this->data[3]), 0);
    }

    /**
     * @return int
     */
    public function temperatureMax(): int
    {
        if (!isset($this->data[3])) {
            throw new \RuntimeException('Missing temperature data');
        }
        return (int)round(preg_replace('/[^0-9\.\-]/', '', $this->data[3]), 0);
    }

    /**
     * @return string
     */
    private function getTextual(): string
    {
        // Rain amount in mm
        if (!isset($this->data[4])) {
            throw new \RuntimeException('Missing rain data');
        }
        // Cloud percentage
        if (!isset($this->data[6])) {
            throw new \RuntimeException('Missing cloud data');
        }

        $rain = (float)preg_replace('/[^0-9\.]/', '', $this->data[4]);
        if ($rain > 0) {
            return $rain <= 2 ? 'Light rain' : 'Rain ' . $rain . 'mm';
        } else {
            $cloud = (float)preg_replace('/[^0-9\.]/', '', $this->data[6]);
            return $cloud == 0 ? 'Clear Skies' : 'Cloudy';
        }
    }
}
