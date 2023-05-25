<?php

namespace Juanparati\ISOCodes\Tests\test;

use Illuminate\Support\Collection;
use Juanparati\ISOCodes\Enums\NodeResolution;
use Juanparati\ISOCodes\ISOCodes;
use Juanparati\ISOCodes\Models\CountryModel;
use Juanparati\ISOCodes\Models\ModelRecord;
use PHPUnit\Framework\TestCase;

class ImmutabilityTest extends TestCase
{

    /**
     * Test immutability
     */
    public function testImmutableResolutions() {
        $iso = new ISOCodes();

        $modified = $iso->countries()
            ->setResolution('currencies', NodeResolution::NODE_AS_ALL)
            ->setResolution('continents', NodeResolution::NODE_AS_ALL)
            ->setResolution('languages', NodeResolution::NODE_AS_ALL)
            ->findByAlpha2('de');

        $original = $iso->countries()->findByAlpha2('de');

        $this->assertNotEquals($original->currencies, $modified->currencies);
        $this->assertNotEquals($original->continents, $modified->continents);
        $this->assertNotEquals($original->languages, $modified->languages);

        $modified = $iso->countries()
            ->setResolution('currencies', NodeResolution::NODE_AS_NONE)
            ->setResolution('continents', NodeResolution::NODE_AS_NONE)
            ->setResolution('languages', NodeResolution::NODE_AS_NONE)
            ->findByAlpha2('de')
            ->toArray();


        $this->assertFalse(isset($modified['currencies']));
        $this->assertFalse(isset($modified['continents']));
        $this->assertFalse(isset($modified['languages']));

        $modified = $iso->countries()
            ->findByAlpha2('de')
            ->toArray();

        $this->assertTrue(isset($modified['currencies']));
        $this->assertTrue(isset($modified['continents']));
        $this->assertTrue(isset($modified['languages']));
    }


    /**
     * Test mutability on cloned models.
     */
    public function testMutableClone() {
        $iso = new ISOCodes(defaultResolutions: [
            'currencies' => NodeResolution::NODE_AS_NAME
        ]);

        $currencies = $iso->currencies()
            ->all()
            ->toArray();

        $this->assertArrayHasKey('Afghani', $currencies);

        $cloned = $iso->currencies()
            ->setResolution('currencies', NodeResolution::NODE_AS_CODE)
            ->clone();

        $currencies = $cloned
            ->all()
            ->toArray();

        $this->assertArrayHasKey('AFN', $currencies);
    }

}