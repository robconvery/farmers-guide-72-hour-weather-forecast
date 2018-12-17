<?php declare(strict_types=1);
/**
 * Interface GatewayInterface
 * @package Robconvery\FarmersGuideForecast\Interfaces
 */

namespace Robconvery\FarmersGuideForecast\Interfaces;

use Illuminate\Support\Collection;

interface GatewayInterface
{
    /**
     * @param string $postcode
     * @return GatewayInterface
     */
    public function getForecast(string $postcode): GatewayInterface;

    /**
     * @return Collection
     */
    public function extract(): Collection;
}
