<?php

namespace App\Tests\Service;

use App\Service\EurCountriesProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class EurCountriesProviderTest extends TestCase
{
    private EurCountriesProvider $eurCountriesProvider;

    protected function setUp(): void
    {
        $config = Yaml::parseFile(__DIR__ . '/../../config/eur_countries.yaml');
        $eurCountries = $config['parameters']['eur_countries'];

        $this->eurCountriesProvider = new EurCountriesProvider($eurCountries);
    }

    public function testGetCountries(): void
    {
        $countries = $this->eurCountriesProvider->getCountries();

        $this->assertCount(27, $countries);

        foreach ($countries as $country) {
            $this->assertEquals(2, strlen($country));
        }
    }
}
