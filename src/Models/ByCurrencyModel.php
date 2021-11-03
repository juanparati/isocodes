<?php

namespace Juanparati\ISOCodes\Models;

use Illuminate\Support\Collection;

class ByCurrencyModel extends ModelBase
{

    public function list(): Collection
    {
        return collect(
            $this->iso->getDatabaseInstance('currencies')->all()
        );
    }
}