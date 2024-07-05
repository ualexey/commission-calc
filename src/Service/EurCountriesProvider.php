<?php

namespace App\Service;

class EurCountriesProvider implements CountriesProviderInterface
{
    private array $eurCountries;

    public function __construct(array $eurCountries)
    {
        $this->eurCountries = $eurCountries;
    }

    public function getCountries(): array
    {
        return $this->eurCountries;
    }
}
