<?php

namespace Juanparati\ISOCodes\Models;

use Illuminate\Support\Collection;


class ByLanguageModel extends ByNodeBase
{
    protected string $database = 'languages';

    protected array $assocNodes = [
        'currencies',
        'continents',
    ];
}