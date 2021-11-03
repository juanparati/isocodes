<?php

namespace Juanparati\ISOCodes\Models;

use Illuminate\Support\Collection;

class ByContinentModel extends ModelBase
{


    /**
     *
     * @return Collection
     * @throws \Juanparati\ISOCodes\Exceptions\ISONodeAttributeMissing
     */
    public function all() : Collection
    {
        $countries = new ByCountryModel($this->dbs);

        foreach ($this->nodeResolution as $nodeName => $nodeFormat)
            $countries->setResolution($nodeName, $nodeFormat);

        return $countries->all()->groupBy('continents');

    }

    /**
     * Return the raw list.
     *
     * @return Collection
     */
    public function list(): Collection
    {
        return collect(
            $this->iso->getDatabaseInstance('continents')->all()
        );
    }
}