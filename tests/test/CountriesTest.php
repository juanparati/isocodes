<?php

namespace Juanparati\ISOCodes\Tests\test;

use Illuminate\Support\Collection;
use Juanparati\ISOCodes\ISOCodes;
use PHPUnit\Framework\TestCase;

class CountriesTest extends TestCase
{

    protected $testCountry = [
        'name'       => 'Spain',
        'alpha2'     => 'ES',
        'alpha3'     => 'ESP',
        'numeric'    => '724',
        'tld'        => '.es',
        'currencies' => ['EUR'],
        'languages'  => ['ES'],
        'continents' => ['EU'],
    ];

    public function testAllCountries() {

        $iso = new ISOCodes();

        /**
         * @var Collection $countries
         */
        $countries = $iso->byCountry()->all();

        $this->assertGreaterThan(100, $countries->count());

        foreach ($countries as $country) {
            $this->assertArrayHasKey('alpha2', $country);
            $this->assertArrayHasKey('alpha3', $country);
            $this->assertArrayHasKey('tld', $country);
            $this->assertArrayHasKey('currencies', $country);
            $this->assertArrayHasKey('continents', $country);
            $this->assertArrayHasKey('name', $country);
        }
    }


    /**
     * Test by Alpha2.
     */
    public function testByAlpha2() {

        $iso = new ISOCodes();

        $country = $iso->byCountry()->byAlpha2('es');

        foreach ($this->testCountry as $key => $value)
            $this->assertEquals($country[$key], $value);
    }

}