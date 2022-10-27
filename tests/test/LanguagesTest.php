<?php

namespace Juanparati\ISOCodes\Tests\test;

use Juanparati\ISOCodes\ISOCodes;
use PHPUnit\Framework\TestCase;

class LanguagesTest extends TestCase
{

    public function testAllLanguages() {
        $iso = new ISOCodes();
        $cont = $iso->languages()->all();

        $this->assertGreaterThan(40, $cont->count());
    }


    public function testByLanguageCode() {
        $iso = new ISOCodes();

        $cont = $iso->languages()->findByCode('ES');

        $this->assertArrayHasKey('countries', $cont);
        $this->assertArrayHasKey('currencies', $cont);
        $this->assertArrayHasKey('continents', $cont);

        $this->assertGreaterThan(10, $cont['countries']->count());
        $this->assertGreaterThan(10, $cont['currencies']->count());
        $this->assertGreaterThan(2, $cont['continents']->count());
    }

}