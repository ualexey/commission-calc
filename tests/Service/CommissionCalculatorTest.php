<?php

namespace App\Tests\Service;

use App\Service\BinProviderInterface;
use App\Service\CommissionCalculator;
use App\Service\CountriesProviderInterface;
use App\Service\ExchangeRateProviderInterface;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    private BinProviderInterface $binProvider;
    private ExchangeRateProviderInterface $exchangeRateProvider;
    private CountriesProviderInterface $eurCountriesProvider;
    private CommissionCalculator $calculator;

    protected function setUp(): void
    {
        $this->binProvider = $this->createMock(BinProviderInterface::class);
        $this->exchangeRateProvider = $this->createMock(ExchangeRateProviderInterface::class);
        $this->eurCountriesProvider = $this->createMock(CountriesProviderInterface::class);

        $this->calculator = new CommissionCalculator(
            $this->binProvider,
            $this->exchangeRateProvider,
            $this->eurCountriesProvider
        );
    }

    public function testCalculateCommissionEur(): void
    {
        $bin = '45717360';
        $amount = 100.00;
        $currency = 'EUR';
        $this->binProvider
            ->method('getBinData')
            ->willReturn(['country' => ['alpha2' => 'DE']]);
        $this->eurCountriesProvider
            ->method('getCountries')
            ->willReturn(['DE', 'FR', 'IT']);
        $this->exchangeRateProvider
            ->method('getExchangeRate')
            ->willReturn(1.0);

        $commission = $this->calculator->calculateCommission($bin, $amount, $currency);

        $this->assertEquals(1.00, $commission);
    }

    public function testCalculateCommissionNonEur(): void
    {
        $bin = '45717360';
        $amount = 100.00;
        $currency = 'USD';
        $this->binProvider
            ->method('getBinData')
            ->willReturn(['country' => ['alpha2' => 'US']]);
        $this->eurCountriesProvider
            ->method('getCountries')
            ->willReturn(['DE', 'FR', 'IT']);
        $this->exchangeRateProvider
            ->method('getExchangeRate')
            ->willReturn(1.2);

        $commission = $this->calculator->calculateCommission($bin, $amount, $currency);

        $this->assertEquals(1.67, $commission);
    }
}
