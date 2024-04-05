<?php

namespace Juanparati\ISOCodes\Tests\test;

use Illuminate\Support\Collection;
use Juanparati\ISOCodes\Enums\NodeResolution;
use Juanparati\ISOCodes\ISOCodes;
use Juanparati\ISOCodes\Models\CountryModel;
use Juanparati\ISOCodes\Models\ModelRecord;
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
        'languages'  => ['ES', 'CA', 'GL', 'EU'],
        'continents' => ['EU'],
    ];

    protected array $testNodeAll = [
        'currencies' => [
            'EUR' => 'Euro'
        ],
        'languages' => [
            'ES' => 'Spanish',
            'CA' => 'Catalan',
            'GL' => 'Galician',
            'EU' => 'Basque'
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
            'Spanish',
            'Catalan',
            'Galician',
            'Basque'
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
        $countries = $iso->countries()->all();

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

        $country = $iso->countries()->findByAlpha2('foo');
        $this->assertNull($country);

        $country = $iso->countries()->findByAlpha2('es');

        $this->assertCountry($this->testNodeCode + $this->testCountry, $country);

        $country = $iso->countries()
            ->setResolution('currencies', NodeResolution::NODE_AS_ALL)
            ->setResolution('continents', NodeResolution::NODE_AS_ALL)
            ->setResolution('languages' , NodeResolution::NODE_AS_ALL)
            ->findByAlpha2('es');

        $this->assertCountry($this->testNodeAll + $this->testCountry, $country);

        $country = $iso->countries()
            ->setResolution('currencies', NodeResolution::NODE_AS_NAME)
            ->setResolution('continents', NodeResolution::NODE_AS_NAME)
            ->setResolution('languages' , NodeResolution::NODE_AS_NAME)
            ->findByAlpha2('es');

        $this->assertCountry($this->testNodeName + $this->testCountry, $country);

        $country = $iso->countries()
            ->setResolution('currencies', NodeResolution::NODE_AS_NONE)
            ->setResolution('continents', NodeResolution::NODE_AS_NONE)
            ->setResolution('languages' , NodeResolution::NODE_AS_NONE)
            ->findByAlpha2('es');

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

        $country = $iso->countries()->findByAlpha3('esp');

        $this->assertCountry($this->testNodeCode + $this->testCountry, $country);
    }


    /**
     * Test search by Numeric.
     */
    public function testByNumberic() {
        $iso = new ISOCodes();

        $country = $iso->countries()->findByNumeric('724');

        $this->assertCountry($this->testNodeCode + $this->testCountry, $country);
    }


    /**
     * Test search by TLD.
     */
    public function testByTld() {
        $iso = new ISOCodes();

        $country = $iso->countries()->findByTld('ES');
        $this->assertCountry($this->testNodeCode + $this->testCountry, $country);

        $country = $iso->countries()->findByTld('.es');
        $this->assertCountry($this->testNodeCode + $this->testCountry, $country);
    }


    /**
     * Test search by Name.
     *
     * @return void
     */
    public function testByName() {
        $iso = new ISOCodes();

        $country = $iso->countries()->findByName('spain');
        $this->assertCountry($this->testNodeCode + $this->testCountry, $country);

        $country = $iso->countries()->findByName('spAIn');
        $this->assertCountry($this->testNodeCode + $this->testCountry, $country);

        $country = $iso->countries()->findByName('Lalandia');
        $this->assertNull($country);
    }


    /**
     * Test currency as number.
     */
    public function testCurrencyAsNumber() {
        $iso = new ISOCodes();

        $country = $iso->countries()
            ->setCurrencyAsNumber(true)
            ->findByAlpha2('es');

        $this->assertEquals($country['currencies'], ['978']);

        $country = $iso->countries()
            ->setCurrencyAsNumber(true)
            ->setResolution('currencies', NodeResolution::NODE_AS_ALL)
            ->findByAlpha2('es');

        $this->assertEquals($country['currencies'], ['978' => 'Euro']);
    }


    public function testContinentSearch() {
        $this->assertCount(2, (new ISOCodes())->countries()->whereContinent(['AS', 'EU'], true));
    }


    /**
     * Assert expected country.
     *
     * @param array $expected
     * @param \ArrayAccess|ModelRecord $country
     */
    protected function assertCountry(array $expected, \ArrayAccess|ModelRecord $country) {
        foreach ($expected as $key => $value)
            $this->assertEquals($country[$key], $value);
    }

}