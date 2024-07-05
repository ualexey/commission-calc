<?php

namespace App\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class BinProvider implements BinProviderInterface
{
    private ClientInterface $httpClient;
    private string $apiUrl = 'https://lookup.binlist.net/';

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getBinData(string $bin): array
    {

        try {
            $response = $this->httpClient->request(
                'GET',
                $this->apiUrl . $bin,
                ['timeout' => 30, 'connect_timeout' => 5]
            );

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new \Exception('Error fetching BIN data: ' . $e->getMessage());
        }
    }
}
