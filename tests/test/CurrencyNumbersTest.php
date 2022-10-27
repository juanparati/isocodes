<?php

namespace Juanparati\ISOCodes\Tests\test;

use Juanparati\ISOCodes\ISOCodes;
use PHPUnit\Framework\TestCase;

class CurrencyNumbersTest extends TestCase
{

    public function testAllCurrencies() {
        $iso = new ISOCodes();
        $cont = $iso->currencyNumbers()->all();

        $this->assertGreaterThan(100, $cont->count());

        foreach ($cont as $group) {
            $this->assertTrue(ctype_digit($group['code']));
            $this->assertFalse(ctype_digit($group['name']));
        }
    }


    public function testByCurrencyCode() {
        $iso = new ISOCodes();
        $cont = $iso->currencyNumbers()->findByCode('978');

        $this->assertArrayHasKey('countries', $cont);
        $this->assertArrayHasKey('languages', $cont);
        $this->assertArrayHasKey('continents', $cont);

        $this->assertGreaterThan(20, $cont['countries']->count());
        $this->assertGreaterThan(20, $cont['languages']->count());
        $this->assertGreaterThan(2, $cont['continents']->count());
    }

}