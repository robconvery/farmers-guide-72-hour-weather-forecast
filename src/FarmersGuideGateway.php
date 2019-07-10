<?php declare(strict_types=1);

/**
 * Class FarmersGuideGateway
 *
 * @package Robconvery\FarmersGuideForecast
 * @author robconvery <robconvery@me.com>
 */

namespace Robconvery\FarmersGuideForecast;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Robconvery\FarmersGuideForecast\Interfaces\GatewayInterface;
use GuzzleHttp\Client;

class FarmersGuideGateway implements GatewayInterface
{
    /**
     * @var \GuzzleHttp\Psr7\Response|null
     */
    protected $response;

    /**
     * @var Illuminate\Support\Collection|null
     */
    protected $data;

    /**
     * @param string $postcode
     * @return GatewayInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getForecast(string $postcode): GatewayInterface
    {
        try {
            $postcode = trim($postcode);
            $this->response = (new Client([
                'verify' => false
            ]))
                ->request('GET', 'https://www.farmersguide.co.uk/weather/?postcode=' . urlencode($postcode));
            if ($this->validateResponse() === false) {
                throw new \HttpException('Unable to retrieve Farmers Guide data.');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return $this;
    }

    /**
     * @return \GuzzleHttp\Psr7\Response
     */
    public function getResponse(): \GuzzleHttp\Psr7\Response
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->getResponse()
            ->getBody()
            ->getContents();
    }

    /**
     * @return Collection
     */
    public function extract(): Collection
    {
        if (!$this->getResponse() instanceof \GuzzleHttp\Psr7\Response) {
            throw new \RuntimeException('The `Farmers Guide` has not responded.');
        }

        $data = collect($this->extractRows())
            ->filter(function ($row) {
                return $row->getElementsByTagName('td')->length;
            })
            ->map(function ($row) {
                return $this->extractCells($row)->map(function ($cell) {
                    if ($cell->hasChildNodes()) {
                        return $this->extractCellValue($cell);
                    }
                });
            });
        return $data;
    }

    /**
     * @return array
     */
    private function extractRows(): array
    {
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->validateOnParse = true;
        $dom->loadHTML($this->getContent());

        $xpath = new \DOMXPath($dom);
        $table = $xpath
            ->query("//*[@class='table forecast-table hidden-xs']")
            ->item(0);

        if ($table instanceof \DOMElement) {
            return $this->convertToArray(
                $table->getElementsByTagName("tr")
            );
        } else {
            return [];
        }
    }

    /**
     * @param \DOMElement $row
     * @return Collection
     */
    private function extractCells(\DOMElement $row): Collection
    {
        return collect($this->convertToArray(
            $row->getElementsByTagName('td')
        ));
    }

    /**
     * @param \DOMElement $cell
     * @return string
     */
    private function extractCellValue(\DOMElement $cell): string
    {
        if ($cell->hasAttribute('class')) {
            if ($cell->getAttribute('class') == 'weather-icon') {
                if ($cell->hasChildNodes()) {
                    // return the weather description
                    return $cell->getElementsByTagName('img')
                            ->item(0)
                            ->getAttribute('title');
                }
            }
        }
        // return the other value
        return collect($this->convertToArray($cell->childNodes))
        ->map(function ($node) {
            return $node->nodeValue;
        })->implode(', ');
    }

    /**
     * @param \DOMNodeList $list
     * @return array
     */
    private function convertToArray(\DOMNodeList $list): array
    {
        $rows = [];
        foreach ($list as $node) {
            $rows[] = $node;
        }
        return $rows;
    }

    /**
     * @return bool
     */
    private function validateResponse(): bool
    {
        return $this->response->getStatusCode() == 200 &&
            $this->response->getHeaderLine('content-type') == 'text/html; charset=UTF-8';
    }
}
