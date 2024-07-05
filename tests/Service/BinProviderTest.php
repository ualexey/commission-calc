<?php

namespace App\Tests\Service;

use App\Service\BinProvider;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class BinProviderTest extends TestCase
{
    private ClientInterface $httpClient;
    private BinProvider $binProvider;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(ClientInterface::class);
        $this->binProvider = new BinProvider($this->httpClient);
    }

    public function testGetBinData(): void
    {
        $bin = '45717360';
        $response = new Response(200, [], json_encode(['country' => ['alpha2' => 'DE']]));
        $this->httpClient
            ->method('request')
            ->willReturn($response);

        $result = $this->binProvider->getBinData($bin);

        $this->assertEquals(['country' => ['alpha2' => 'DE']], $result);
    }

    public function testGetBinDataThrowsExceptionOnError(): void
    {
        $bin = '45717360';
        $this->httpClient
            ->method('request')
            ->willThrowException(new \Exception('Error fetching BIN data'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error fetching BIN data');

        $this->binProvider->getBinData($bin);
    }
}
