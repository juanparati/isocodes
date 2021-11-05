<?php

namespace Juanparati\ISOCodes\Databases\Continents;

use Juanparati\ISOCodes\Databases\ISODatabaseBase;

/**
 * List of continents (English).
 *
 * @see https://datahub.io/core/continent-codes
 */
class ContinentsEN extends ISODatabaseBase
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
