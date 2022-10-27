<?php

namespace Juanparati\ISOCodes\Tests\test;

use Juanparati\ISOCodes\Exceptions\ISORecordAttributeNotFound;
use Juanparati\ISOCodes\ISOCodes;
use PHPUnit\Framework\TestCase;

class ModelRecordTest extends TestCase
{

    /**
     * Basic country attributes.
     */
    protected const COUNTRY_ATTRIBS = [
        'alpha2',
        'alpha3',
        'numeric',
        'tld',
        'currencies',
        'languages',
        'continents',
        'capital',
        'flag',
        'phone_code',
        'name',
        'eu_member',
    ];

    /**
     * Test that it's possible to access using attributes and also test the country
     * data integrity.
     *
     * @return void
     */
    public function testAttributeGetter() : void {
        $iso = new ISOCodes();

        $countries = $iso->countries()->all();

        foreach ($countries as $country) {
            foreach (static::COUNTRY_ATTRIBS as $attr) {
                $result = $country->{$attr} ?: true;
                $this->assertNotEmpty($result);
            }
        }
    }


    /**
     * Test missing attribute.
     *
     * @return void
     */
    public function testAttributeGetterError() {
        $this->expectException(ISORecordAttributeNotFound::class);

        (new ISOCodes())
            ->countries()
            ->all()
            ->first()
            ->foobar;
    }

}