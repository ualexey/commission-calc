<?php

namespace App\Service;

interface ExchangeRateProviderInterface
{
    public function getExchangeRate(string $currency): float;
}
