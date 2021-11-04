<?php

namespace Juanparati\ISOCodes\Tests\test;

use Juanparati\ISOCodes\ISOCodes;
use PHPUnit\Framework\TestCase;

class CurrenciesTest extends TestCase
{

    public function testAllCurrencies() {
        $iso = new ISOCodes();
        $cont = $iso->byCurrency()->all();
        $this->assertGreaterThan(100, $cont->count());
    }


    public function testByCurrencyCode() {
        $iso = new ISOCodes();
        $cont = $iso->byCurrency()->byCode('EUR');

        $this->assertArrayHasKey('countries', $cont);
        $this->assertArrayHasKey('languages', $cont);
        $this->assertArrayHasKey('continents', $cont);

        $this->assertGreaterThan(20, $cont['countries']->count());
        $this->assertGreaterThan(20, $cont['languages']->count());
        $this->assertGreaterThan(2, $cont['continents']->count());
    }

}