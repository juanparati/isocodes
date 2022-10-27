<?php

namespace Juanparati\ISOCodes\Tests\test;

use Juanparati\ISOCodes\ISOCodes;
use PHPUnit\Framework\TestCase;

class ContinentsTest extends TestCase
{

    public function testAllContinents() {
        $iso = new ISOCodes();
        $cont = $iso->continents()->all();
        $this->assertEquals(7, $cont->count());
    }


    public function testContinentCode() {
        $iso = new ISOCodes();
        $cont = $iso->continents()->findByCode('EU');

        $this->assertArrayHasKey('countries', $cont);
        $this->assertArrayHasKey('languages', $cont);
        $this->assertArrayHasKey('currencies', $cont);

        $this->assertGreaterThan(20, $cont['countries']->count());
        $this->assertGreaterThan(20, $cont['languages']->count());
        $this->assertGreaterThan(20, $cont['currencies']->count());
    }

}