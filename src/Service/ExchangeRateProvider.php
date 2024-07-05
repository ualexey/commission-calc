<?php

namespace App\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class ExchangeRateProvider implements ExchangeRateProviderInterface
{
    private ClientInterface $httpClient;
    private string $apiUrl = 'https://api.apilayer.com/exchangerates_data/latest';
    private string $apiKey = 'dlM19OTQ37q9dDUyuO0FBYuPOjBN6YIJ';
    private string $baseCurrency = 'EUR';

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getExchangeRate(string $currency): float
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiUrl, [
                'query' => [
                    'base' => $this->baseCurrency,
                ],
                'headers' => [
                    'Content-Type' => 'text/plain',
                    'apikey' => $this->apiKey
                ],
                'timeout' => 30,
                'connect_timeout' => 5
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['rates'][$currency])) {
                return (float)$data['rates'][$currency];
            } else {
                throw new \Exception('Exchange rate for ' . $currency . ' not found in API response.');
            }
        } catch (GuzzleException $e) {
            throw new \Exception('Error fetching exchange rate: ' . $e->getMessage());
        }
    }
}
