<?php

namespace Juanparati\ISOCodes\Models;

class ContinentModel extends ModelBase
{
    protected string $database = 'continents';

    protected array $assocNodes = [
        'currencies',
        'languages',
    ];
}
