<?php

namespace Juanparati\ISOCodes\Models;

use Illuminate\Support\Collection;


class ByLanguageModel extends ModelBase
{

    protected array $codesOpt = [
        'currencies' => self::NODE_AS_CODE,
        'languages'  => self::NODE_AS_CODE,
    ];


    public function all() : Collection
    {

    }


    /**
     * Return the list.
     *
     * @return Collection
     */
    public function list(): Collection
    {
        return collect($this->iso->getDatabaseInstance('languages')->all());
    }
}