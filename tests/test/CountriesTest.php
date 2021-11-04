<?php

namespace Juanparati\ISOCodes\Tests\test;

use Illuminate\Support\Collection;
use Juanparati\ISOCodes\ISOCodes;
use Juanparati\ISOCodes\Models\ByCountryModel;
use PHPUnit\Framework\TestCase;

class CountriesTest extends TestCase
{

    protected array $testCountry = [
        'name'       => 'Spain',
        'alpha2'     => 'ES',
        'alpha3'     => 'ESP',
        'numeric'    => '724',
        'tld'        => '.es',
    ];

    protected array $testNodeCode = [
        'currencies' => ['EUR'],
        'languages'  => ['ES'],
        'continents' => ['EU'],
    ];

    protected array $testNodeAll = [
        'currencies' => [
            'EUR' => 'Euro'
        ],
        'languages' => [
            'ES'  => 'Spanish'
        ],
        'continents' => [
            'EU' => 'Europe'
        ]
    ];

    protected array $testNodeName = [
        'currencies' => [
            'Euro'
        ],
        'languages' => [
            'Spanish'
        ],
        'continents' => [
            'Europe'
        ]
    ];


    /**
     * Test retrieving all countries.
     */
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

        $country = $iso->byCountry()->byAlpha2('foo');
        $this->assertNull($country);

        $country = $iso->byCountry()->byAlpha2('es');
        $this->assertCountry($this->testNodeCode + $this->testCountry, $country);

        $country = $iso->byCountry()
            ->setResolution('currencies', ByCountryModel::NODE_AS_ALL)
            ->setResolution('continents', ByCountryModel::NODE_AS_ALL)
            ->setResolution('languages' , ByCountryModel::NODE_AS_ALL)
            ->byAlpha2('es');

        $this->assertCountry($this->testNodeAll + $this->testCountry, $country);

        $country = $iso->byCountry()
            ->setResolution('currencies', ByCountryModel::NODE_AS_NAME)
            ->setResolution('continents', ByCountryModel::NODE_AS_NAME)
            ->setResolution('languages' , ByCountryModel::NODE_AS_NAME)
            ->byAlpha2('es');

        $this->assertCountry($this->testNodeName + $this->testCountry, $country);

        $country = $iso->byCountry()
            ->setResolution('currencies', ByCountryModel::NODE_AS_NONE)
            ->setResolution('continents', ByCountryModel::NODE_AS_NONE)
            ->setResolution('languages' , ByCountryModel::NODE_AS_NONE)
            ->byAlpha2('es');

        $this->assertArrayNotHasKey('countries', $country);
        $this->assertArrayNotHasKey('currencies', $country);
        $this->assertArrayNotHasKey('continents', $country);
        $this->assertCountry($this->testCountry, $country);
    }


    /**
     * Test by Alpha3.
     */
    public function testByAlpha3() {
        $iso = new ISOCodes();

        $country = $iso->byCountry()->byAlpha3('esp');

        $this->assertCountry($this->testNodeCode + $this->testCountry, $country);
    }


    /**
     * Test search by Numeric.
     */
    public function testByNumberic() {
        $iso = new ISOCodes();

        $country = $iso->byCountry()->byNumberic('724');

        $this->assertCountry($this->testNodeCode + $this->testCountry, $country);
    }


    /**
     * Test search by TLD.
     */
    public function testByTld() {
        $iso = new ISOCodes();

        $country = $iso->byCountry()->byTld('ES');
        $this->assertCountry($this->testNodeCode + $this->testCountry, $country);

        $country = $iso->byCountry()->byTld('.es');
        $this->assertCountry($this->testNodeCode + $this->testCountry, $country);
    }


    /**
     * Test currency as number.
     */
    public function testCurrencyAsNumber() {
        $iso = new ISOCodes();

        $country = $iso->byCountry()
            ->setCurrencyAsNumber(true)
            ->byAlpha2('es');

        $this->assertEquals($country['currencies'], ['978']);

        $country = $iso->byCountry()
            ->setCurrencyAsNumber(true)
            ->setResolution('currencies', ByCountryModel::NODE_AS_ALL)
            ->byAlpha2('es');

        $this->assertEquals($country['currencies'], ['978' => 'Euro']);
    }


    /**
     * Assert expected country.
     *
     * @param array $expected
     * @param array $country
     */
    protected function assertCountry(array $expected, array $country) {
        foreach ($expected as $key => $value)
            $this->assertEquals($country[$key], $value);
    }

}