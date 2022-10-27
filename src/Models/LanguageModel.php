<?php

namespace Juanparati\ISOCodes\Models;

use Illuminate\Support\Collection;

class LanguageModel extends ModelBase
{
    protected string $database = 'languages';

    protected array $assocNodes = [
        'currencies',
        'continents',
    ];
}
