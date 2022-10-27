<?php

namespace Juanparati\ISOCodes\Models;

class CurrencyModel extends ModelBase
{
    protected string $database = 'currencies';

    protected array $assocNodes = [
        'languages',
        'continents',
    ];
}
