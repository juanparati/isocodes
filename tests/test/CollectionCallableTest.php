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


    public function testMacroable() {
        \Juanparati\ISOCodes\Models\CountryModel::macro('allEUMembers', function () {
            return $this->where('eu_member', true)->all();
        });

        $iso = new ISOCodes();

        $euMembers = $iso->countries()->allEUMembers();
        $this->assertCount(27, $euMembers);

        // GB leave us 😢
        $this->assertTrue($iso->countries()->where('eu_member', false)->contains(fn($c) => $c->alpha2 === 'GB'));
    }

}