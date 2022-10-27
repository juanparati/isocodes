<?php

namespace Juanparati\ISOCodes\Data\Continents;

use Juanparati\ISOCodes\Data\ISODataBase;

/**
 * List of continents (English).
 *
 * @see https://datahub.io/core/continent-codes
 */
class ContinentsEN extends ISODataBase
{
    protected array $db = [
        'AF' => 'Africa',
        'AN' => 'Antartica',
        'AS' => 'Asia',
        'EU' => 'Europe',
        'NA' => 'North America',
        'OC' => 'Oceania',
        'SA' => 'South America',
    ];
}
