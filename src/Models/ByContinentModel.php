<?php

namespace Juanparati\ISOCodes\Models;

class ByContinentModel extends ByNodeBase
{
    protected string $database = 'continents';

    protected array $assocNodes = [
        'currencies',
        'languages',
    ];
}
