<?php

namespace App\Service;

use Exception;

class CommissionCalculator
{

    private BinProviderInterface $binProvider;
    private ExchangeRateProviderInterface $exchangeRateProvider;
    private CountriesProviderInterface $eurCountriesProvider;

    public function __construct(
        BinProviderInterface          $binProvider,
        ExchangeRateProviderInterface $exchangeRateProvider,
        CountriesProviderInterface    $eurCountriesProvider
    )
    {
        $this->binProvider = $binProvider;
        $this->exchangeRateProvider = $exchangeRateProvider;
        $this->eurCountriesProvider = $eurCountriesProvider;
    }

    public function calculateCommission(string $bin, float $amount, string $currency): float
    {

        $binData = $this->binProvider->getBinData($bin);

        $isEur = in_array($binData['country']['alpha2'], $this->eurCountriesProvider->getCountries());

        $rate = (strtoupper($currency) === 'EUR') ? 1 : $this->exchangeRateProvider->getExchangeRate($currency);

        if ($rate == 0) {
            throw new Exception('Invalid exchange rate!');
        }

        $amountFixed = $amount / $rate;
        $commission = $amountFixed * ($isEur ? 0.01 : 0.02);
        return ceil($commission * 100) / 100;
    }
}
