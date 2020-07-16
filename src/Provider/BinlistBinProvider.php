<?php

namespace App\Provider;

use App\Exception\BinProviderException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BinlistBinProvider implements BinProviderInterface
{
    private const URL_PATH_PATTERN = 'https://lookup.binlist.net/%s';

    /**
     * @var HttpClientInterface
     */
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $bin
     *
     * @return string
     * @throws BinProviderException
     */
    public function getCountryCodeByBIN(string $bin): string
    {
        $jsonData = $this->getResultFromServer($bin);

        $decodedData = json_decode($jsonData, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BinProviderException('Wrong JSON data from server');
        }

        if (!isset($decodedData['country']['alpha2'])) {
            throw new BinProviderException('Wrong data structure from server');
        }

        return mb_strtoupper($decodedData['country']['alpha2']);
    }

    /**
     * @param string $bin
     *
     * @return string
     * @throws BinProviderException
     */
    private function getResultFromServer(string $bin): string
    {
        try {
            $response = $this->client->request('GET', sprintf(self::URL_PATH_PATTERN, $bin));

            return $response->getContent();
        } catch (TransportExceptionInterface|RedirectionExceptionInterface|ClientExceptionInterface|ServerExceptionInterface $e) {
            throw new BinProviderException('Error while getting data from server', $e->getCode(), $e);
        }
    }
}
