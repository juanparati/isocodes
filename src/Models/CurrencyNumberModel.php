<?php

namespace Juanparati\ISOCodes\Models;

use Illuminate\Support\Collection;

class CurrencyNumberModel extends CurrencyModel
{
    protected string $database = 'currencyNumbers';

    protected array $assocNodes = [
        'languages',
        'continents',
    ];


    /**
     * Get all the currencies number codes.
     *
     * @param bool $asArray
     * @return Collection
     * @throws \Juanparati\ISOCodes\Exceptions\ISONodeAttributeMissing
     */
    public function all(bool $asArray = false): Collection
    {
        $countries = $this->iso->countries()->setCurrencyAsNumber(true);

        foreach ($this->nodeResolution as $nodeName => $nodeFormat) {
            $countries->setResolution($nodeName, $nodeFormat);
        }

        $list = $this->list();
        $list = $this->iso->currencies()
            ->list()
            ->mapWithKeys(fn ($cur, $key) => [$list[$key] => $cur]);

        return $countries->all()
            ->groupBy('currencies')
            ->map(function ($cur, $code) use ($list, $asArray) {
                $base = [
                    'code'       => (string) $code,
                    'name'       => $list[$code] ?? null,
                    'countries'  => $cur,
                ];

                foreach ($this->assocNodes as $assocNode) {
                    $base[$assocNode] = $cur->pluck($assocNode)->filter()->collapse()->unique();
                }

                return $asArray ? $base : new ModelRecord($base);
            });
    }
}
