<?php

namespace App\Provider;

use App\Exception\RatesProviderException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeratesapiRatesProvider implements RatesProviderInterface
{
    private const URL = 'https://api.exchangeratesapi.io/latest';

    /**
     * @var HttpClientInterface
     */
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $currency
     *
     * @return string
     * @throws RatesProviderException
     */
    public function getRateByCurrency(string $currency): string
    {
        $jsonData = $this->getResultFromServer();

        $decodedData = json_decode($jsonData, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RatesProviderException('Wrong JSON data from server');
        }

        return $decodedData['rates'][$currency] ?? '0';
    }

    /**
     * @return string
     * @throws RatesProviderException
     */
    private function getResultFromServer(): string
    {
        try {
            $response = $this->client->request('GET', self::URL);

            return $response->getContent();
        } catch (TransportExceptionInterface|RedirectionExceptionInterface|ClientExceptionInterface|ServerExceptionInterface $e) {
            throw new RatesProviderException('Error while getting data from server', $e->getCode(), $e);
        }
    }
}
