<?php

namespace Juanparati\ISOCodes\Models;

use Illuminate\Support\Collection;

class ByCurrencyNumberModel extends ModelBase
{

    /**
     *
     * @return Collection
     */
    public function list(): Collection
    {
        return collect(
            (new $this->models['currencyNumbers'])->all()
        );
    }
}