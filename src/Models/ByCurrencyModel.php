<?php

namespace Juanparati\ISOCodes\Models;

class ByCurrencyModel extends ByNodeBase
{
    protected string $database = 'currencies';

    protected array $assocNodes = [
        'languages',
        'continents',
    ];
}
