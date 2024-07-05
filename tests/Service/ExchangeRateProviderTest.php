<?php

namespace App\Tests\Service;

use App\Service\ExchangeRateProvider;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ExchangeRateProviderTest extends TestCase
{
    private ClientInterface $httpClient;
    private ExchangeRateProvider $exchangeRateProvider;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(ClientInterface::class);
        $this->exchangeRateProvider = new ExchangeRateProvider($this->httpClient, 'https://api.apilayer.com/exchangerates_data/latest', 'fake-api-key');
    }

    public function testGetExchangeRate(): void
    {
        $currency = 'USD';
        $response = new Response(200, [], json_encode(['rates' => ['USD' => 1.2]]));
        $this->httpClient
            ->method('request')
            ->willReturn($response);

        $rate = $this->exchangeRateProvider->getExchangeRate($currency);

        $this->assertEquals(1.2, $rate);
    }

    public function testGetExchangeRateThrowsExceptionOnError(): void
    {
        $currency = 'USD';
        $this->httpClient
            ->method('request')
            ->willThrowException(new \Exception('Error fetching exchange rate'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error fetching exchange rate');

        $this->exchangeRateProvider->getExchangeRate($currency);
    }

    public function testGetExchangeRateThrowsExceptionOnRequestError(): void
    {
        $currency = 'USD';
        $this->httpClient
            ->method('request')
            ->willThrowException(new \Exception('Error fetching exchange rate'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error fetching exchange rate');

        $this->exchangeRateProvider->getExchangeRate($currency);
    }
}
