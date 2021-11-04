<?php

namespace Juanparati\ISOCodes\Models;

use Illuminate\Support\Collection;

class ByCurrencyNumberModel extends ByCurrencyModel
{
    protected string $database = 'currencyNumbers';

    protected array $assocNodes = [
        'languages',
        'continents',
    ];

    public function all(): Collection
    {
        $countries = $this->iso->byCountry()->setCurrencyAsNumber(true);

        foreach ($this->nodeResolution as $nodeName => $nodeFormat)
            $countries->setResolution($nodeName, $nodeFormat);

        $list = $this->list();
        $list = $this->iso->byCurrency()
            ->list()
            ->mapWithKeys(fn($cur, $key) => [$list[$key] => $cur]);

        return $countries->all()
            ->groupBy('currencies')
            ->map(function ($cur, $code) use ($list) {
                $base = [
                    'code'       => (string) $code,
                    'name'       => $list[$code] ?? null,
                    'countries'  => $cur,
                ];

                foreach ($this->assocNodes as $assocNode)
                    $base[$assocNode] = $cur->pluck($assocNode)->filter()->collapse()->unique();

                return $base;
            });
    }
}