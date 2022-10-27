<?php

namespace Juanparati\ISOCodes\Tests\test;

use Juanparati\ISOCodes\ISOCodes;
use Juanparati\ISOCodes\Models\CountryModel;
use PHPUnit\Framework\TestCase;

class CollectionCallableTest extends TestCase
{

    public function testCallablesModel() {
        $iso = new ISOCodes();

        $this->assertNotEmpty($iso->countries()->first());
        $this->assertEquals(1, $iso->countries()->where('name', 'Spain')->count());
        $this->assertEquals(1, $iso->currencies()->where(fn($c) => $c->code === 'BBD')->count());
    }

}